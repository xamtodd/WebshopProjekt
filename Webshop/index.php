<?php
    session_start();

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
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Startseite</title>
    <link rel="stylesheet" href="CSS/menue.css">
    <link rel="stylesheet" href="CSS/footer.css">
    <link rel="stylesheet" href="CSS/startseite.css">
    <script src="JS/menue.js" defer></script>
    <script src="JS/textanimation.js" defer></script>
</head>
<body>
        <nav class="menue">
            <div class="firmenname">Tauchershop</div>
            <a href="#" class="menuebutton">
                <span class="linie"></span>
                <span class="linie"></span>
                <span class="linie"></span>
            </a>
            <div class="navigations-links">
                <ul>
                    <li><a class="aktuell" href="index.php">Startseite</a></li>
                    <li><a href="shop.php">Shop</a></li>
                    <li><a href="rub.php">Rezensionen / Bewertungen</a></li>
                    <li><a href="meinkonto.php">Mein Konto</a></li>
                    <li><a href="warenkorb.php">Warenkorb (<?=$anz?>)</a></li>
                    <li><a href="impressum.php">Impressum</a></li>
                </ul>
            </div>
        </nav>
    <div id="content">
        <div id="text">

        </div>
        <div class="abschitt">
            <p>Hier gibt es alles, was das Taucherherz begehrt...</p>
            <a href="shop.php"><button>Zum Shop</button></a>
            <a href="rub.php"><button>Unsere Rezensionen</button></a>
        </div>
        <script type="text/javascript">
            var i = 0,text;
            text = "Herzlich Willkommen!";

            function typing(){
                if(i < text.length){
                    document.getElementById("text").innerHTML += text.charAt(i);
                    i++;
                    setTimeout(typing,150);
                }
            }
            typing();
        </script>
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
</body>
</html>