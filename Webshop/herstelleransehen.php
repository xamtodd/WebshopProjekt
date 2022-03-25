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
<html>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Hersteller ansehen</title>
    <link rel="stylesheet" href="CSS/menue.css">
    <link rel="stylesheet" href="CSS/footer.css">
    <link rel="stylesheet" href="CSS/herstelleransehen.css">
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
            <li><a class="aktuell" href="shop.php">Shop</a></li>
            <li><a href="rub.php">Rezensionen / Bewertungen</a></li>
            <li><a href="meinkonto.php">Mein Konto</a></li>
            <li><a href="warenkorb.php">Warenkorb (<?=$anz?>)</a> </li>
            <li><a href="impressum.php">Impressum</a></li>
        </ul>
    </div>
</nav>
<div id = "content">
    <?php
        $sql = "SELECT * FROM hersteller;";
        $result = $conn->query($sql);
        $hersteller_ID = $_GET['hersteller_ID'];
        $name = "";
        while($row = $result->fetch_assoc()){
        if($row['ID'] == $hersteller_ID){
            $name = "ausgewaehlt";
        }else{
            $name = "hersteller";
        }
    ?>
    <div class = '<?=$name?>'>
        <div class = 'FName'>
            <p>Firmenname:</p>
            <p><?=$row['Firmenname']?></p>
        </div>
        <div class = 'Webadresse'>
            <p>Webadresse:</p>
            <p><?=$row['Webadresse']?></p>
        </div>
        <div class = 'EMail'>
            <p>E-Mail:</p>
            <p><?=$row['EMail']?></p>
        </div>
    </div>
    <?php
        }
        $kat_ID = $_GET['kat_ID'];
   ?>
   <br>
   <a href='info.php?kat_ID=<?=$kat_ID?>'><button>Zurück</button></a>
</div>
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
</body>
</html>
