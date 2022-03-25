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
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Kundenkontakt</title>
    <link rel="stylesheet" href="CSS/menue.css">
    <link rel="stylesheet" href="CSS/footer.css">
    <link rel="stylesheet" href="CSS/kundenkontakt.css">
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
        <?php
            $sql = "SELECT *, user.benutzername, user.email FROM kundenanliegen, user WHERE user.ID = kundenanliegen.user_ID GROUP BY kundenanliegen.stempel DESC;";
            $result = $conn->query($sql);
            while($row = $result->fetch_assoc()){
        ?>
        <div class = 'teile'>
            <div>
                <p><?=$row['benutzername']?>, <?=$row['stempel']?></p>
                <p><?=$row['text']?></p>
                <div class = 'email'>
                    <p>Kontakt:</p>
                    <p><?=$row['email']?></p>
                </div>
            </div>
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