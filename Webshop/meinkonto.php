<?php
    session_start();

    if(!isset($_SESSION['loggedin'])){
        header('Location: anmelden.html');
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

    if(!isset($_SESSION['loggedin'])){
        $anz = 0;
    }else{
        $benutzername = $_SESSION['benutzername'];

        $sql = "SELECT ID FROM user WHERE benutzername = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $benutzername);
        $stmt->execute();
        $result = $stmt->get_result();

        while ($row = $result->fetch_assoc()){
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

    $benutzername = $_SESSION['benutzername'];
    $sql = "SELECT * FROM user WHERE Benutzername = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $benutzername);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()){
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Mein Konto</title>
    <link rel="stylesheet" href="CSS/menue.css">
    <link rel="stylesheet" href="CSS/footer.css">
    <link rel="stylesheet" href="CSS/meinkonto.css">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="JS/menue.js" defer></script>
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
            <li><a href="index.php">Startseite</a></li>
            <li><a href="shop.php">Shop</a></li>
            <li><a href="rub.php">Rezensionen / Bewertungen</a></li>
            <li><a class="aktuell" href="meinkonto.php">Mein Konto</a></li>
            <li><a href="warenkorb.php">Warenkorb (<?=$anz?>)</a> </li>
            <li><a href="impressum.php">Impressum</a></li>
        </ul>
    </div>
</nav>
<div id="content">
    <div class="main">
        <h1>Mein Konto - Informationen</h1>
        <hr>
        <div class="felder">
            <div class="username">
                <p class="lable">Benutzername:</p>
                <p><?=$row['Benutzername']?></p>
            </div>
            <div class="vorname">
                <p class="lable">Vorname:</p>
                <p><?=$row['Vorname'];?></p>
            </div>
            <div class="nachname">
                <p class="lable">Nachname:</p>
                <p><?=$row['Nachname'];?></p>
            </div>
            <div class="geburstag">
                <p class="lable">Geburstag:</p>
                <p><?=$row['Geburtsdatum'];?></p>
            </div>
            <div class="email">
                <p class="lable">Email:</p>
                <p><?=$row['Email'];?></p>
            </div>
            <div class="passwortaendern">
                <p class="lable">Passwort:</p>
                <p><a href=""><button>Passwort ändern</button></a></p>
            </div>
        </div>
        <?php
            $ID = $row['ID'];
            }
            $benutzername = $_SESSION['benutzername'];
            $sql = "SELECT level FROM rollen WHERE user_ID = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $ID);
            $stmt->execute();
            $result = $stmt->get_result();
            $rolle = $result->fetch_assoc()['level'];

            if($rolle == 1){
                $status = 'Bronze';
            }
            if($rolle == 2){
                $status = 'Silber';
            }
            if($rolle == 3){
                $status = 'Gold';
            }

            $sql = "SELECT admin FROM rollen WHERE user_ID = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $ID);
            $stmt->execute();
            $result = $stmt->get_result();
            $adminstaus = $result->fetch_assoc()['admin'];
        ?>
        <hr>
        <div class="bestellungenuebersicht">
            <div class="kundenstatus">
                <p class="lable">Kundenstatus:</p>
                <p><?php echo $status;?></p>
            </div>
            <div class="bestellungen">
                <p class="lable">Meine Bestellungen:</p>
                <p><a href="bestellungenAnsehen.php"><button>ansehen</button></a></p>
            </div>
        </div>
        <?php
            if($adminstaus == 1){
                echo "<div class='abminbereich'>";
                echo "<hr>";
                echo "<a onclick = 'admin()'><button>Shop verwalten</button></a>";
                echo "</div>";
            }

            $conn->close();
        ?>
        <div id = 'optionen'></div>
    </div>
    <script>
         function admin(){
           if(document.getElementById('optionen').innerHTML === ""){
                document.getElementById('optionen').innerHTML = "<a href='kundenanzeigen.php'><button>Kundenliste ansehen</button></a><br><a href='kundenkontakt.php'><button>Kundenkontakt</button></a><br><a href='hinzufuegen.php'><button>Zum Shop hinzufügen</button></a><br><a href='loeschen.php'><button>Produkte löschen</button></a>";
           }else{
                document.getElementById('optionen').innerHTML = "";
           }
         }
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