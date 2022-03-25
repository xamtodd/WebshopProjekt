<?php
 session_start();

    //Falls noch nicht angemeldet
    if(!isset($_SESSION['loggedin'])){
        header('Location: ../anmelden.html');
    }

    $servername = "localhost";
    $username = "user";
    $password = "password";
    $db = "webshop";

    // Create connection
    $conn = new mysqli($servername, $username, $password, $db);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    //userID hohlen
    $benutzername = $_SESSION['benutzername'];

    $sql = "SELECT ID FROM user WHERE benutzername = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $benutzername);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()){
        $user_ID = $row['ID'];
    }
    if (!isset($_SESSION['loggedin'])) {
        $anz = 0;
    } else {
        $benutzername = $_SESSION['benutzername'];

        $sql = "SELECT ID FROM user WHERE benutzername = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $benutzername);
        $stmt->execute();
        $result = $stmt->get_result();

        while ($row = $result->fetch_assoc()) {
            $uID = $row['ID'];
        }

        $sql = "SELECT SUM(wAnzahl) FROM warenkorb WHERE ID_user = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $uID);
        $stmt->execute();
        $result = $stmt->get_result();
        $anz = $result->fetch_assoc()['SUM(wAnzahl)'];

        if($anz == null){
            $anz = 0;
        }
        }
?>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Deine Bestellungen</title>
        <link rel="stylesheet" href="CSS/menue.css">
        <link rel="stylesheet" href="CSS/footer.css">
        <link rel="stylesheet" href="CSS/bestellungAnsehen.css">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <script src="JS/menue.js" defer></script>
    </head>
    <body onload='aenderrung()'>
        <nav class="menue">
            <div class="firmenname">Tauchershop</div>
            <a href="#" class="menuebutton">
                <span class="linie"></span>
                <span class="linie"></span>
                <span class="linie"></span>
            </a>
            <div class="navigations-links">
                <ul>
                    <li><a href="index.php">Startseite</a></li>
                    <li><a href="shop.php">Shop</a></li>
                    <li><a href="rub.php">Rezensionen / Bewertungen</a></li>
                    <li><a class="aktuell" href="meinkonto.php">Mein Konto</a></li>
                    <li><a href="warenkorb.php">Warenkorb (<?=$anz?>)</a></li>
                    <li><a href="impressum.php">Impressum</a></li>
                </ul>
            </div>
        </nav>
        <div class = 'alles'>
        <?php
            $sql = "SELECT Count(*) FROM bestellungen WHERE user_ID = $uID";
            $result = $conn->query($sql);
            $a = $result->fetch_assoc()['Count(*)'];
            if($a != 0){
        ?>
        <div class ='auswahl'>
        <h2>Wähle eine Bestellung:</h2>
         <select id = 'seldaten' onchange = 'aenderrung()'>
        <?php
            $sql = "SELECT ID, stempel FROM bestellungen WHERE user_ID = ? ORDER BY stempel ASC";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $uID);
            $stmt->execute();
            $result = $stmt->get_result();

            $i = 0;

            while($row = $result->fetch_assoc()){
            if($i == 0){
           ?>
           <option value = "<?=$row['ID']?>" selected><?=$row['stempel']?></option>
           <?php
            }else{
           ?>
          <option value = "<?=$row['ID']?>"><?=$row['stempel']?></option>
           }
           <?php
           $i++;
           }
           }
           ?>
           </select>
        </div>
        <br>
        <div id = 'rechnung'>
        </div>
        <script>
            function aenderrung(){
                var d = document.getElementById('seldaten').value;


                var ajax = new XMLHttpRequest();

                ajax.open("GET", "PHP/rechnungAjax.php?bID="+d, true);
                ajax.send();

                ajax.onreadystatechange = function () {
                    if (this.readyState == 4 && this.status == 200) {
                        document.getElementById('rechnung').innerHTML = this.responseText;
                    }
                }
            }
        </script>
        </div>
        <?php
        }else{
        ?>
        <div class ='nichta'>
            <h2>Du hast noch keine Bestellung getätigt!</h2>
            <a href='shop.php'><button>Zum Shop</button></a>
        </div>
        <?php
           }
        ?>
            <footer class="footer">
                <a class="links" href="https://github.com/xamtodd">&copy; xamtodd</a>
                <a class="mitte" href="#">www.max-tauchershop.de</a>
                <?php
                if(!isset($_SESSION['loggedin'])){
                    echo "<a class='rechts' href='anmelden.html'>anmelden</a>";
                }else{
                    echo "<a class='rechts' href='PHP/logout.php'>ausloggen</a>";
                }
                ?>
            </footer>
        </div>
        </div>
    </body>
</html>