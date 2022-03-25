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
    <title>Impressum</title>
    <link rel="stylesheet" href="CSS/menue.css">
    <link rel="stylesheet" href="CSS/footer.css">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="CSS/impressum.css">
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
            <li><a href="meinkonto.php">Mein Konto</a></li>
            <li><a href="warenkorb.php">Warenkorb (<?=$anz?>)</a></li>
            <li><a class="aktuell" href="impressum.php">Impressum</a></li>
        </ul>
    </div>
</nav>
<div id="content">
    <div class = 'main'>
        <div class = 'Infos'>
            <div class = 'Firmenname'>
                <p class = 'bold'>Firmename:</p>
                <p>Max Tauchershop GmbH</p>
            </div>
            <div class = 'Adresse'>
                <p class = 'bold'>Adresse:</p>
                <p>Deutschland, 1324, Taucherstadt, Am See 9</p>
            </div>
            <div class = 'EMail'>
                <p class = 'bold'>E-Mail:</p>
                <p>max-tauchershop@icloud.com</p>
            </div>
            <div class = 'Telefonummer'>
                <p class = 'bold'>Telefonnummer:</p>
                <p>0049 123 23452345234</p>
            </div>
            <div class = 'WebsideEntwickler'>
                <p class = 'bold'>Webside Entwickler:</p>
                <p>Gerbeth Software GmbH</p>
            </div>
            <div class = 'Kontakt'>
                    <form>
                    <div class ='felder'>
                        <p class = 'bold'>Kontaktiere uns einfach:</p>
                        <div class = 'inp'>
                            <textarea id='texta' rows='10'>Hier dein Anliegen</textarea>
                            <a onclick='senden()'><button>Senden</button></a>
                        </div>
                    </div>
                    </form>
            </div>
        </div>
    </div>
    <script>
        function senden(){
            var t = document.getElementById('texta').value;
            var ajax = new XMLHttpRequest();

            ajax.open("GET", "PHP/anliegensenden.php?t="+t, true);
            ajax.send();

            alert('Mitteilung gesendet!');

            ajax.onreadystatechange = function () {
                if (this.readyState == 4 && this.status == 200) {
                    document.form.reset();
                }
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