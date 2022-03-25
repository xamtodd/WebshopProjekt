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
}else {
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

    if ($anz == null) {
        $anz = 0;
        header('Location: shop.php');
    }
}
?>
<html>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>zur Kasse</title>
    <link rel="stylesheet" href="CSS/menue.css">
    <link rel="stylesheet" href="CSS/footer.css">
    <link rel="stylesheet" href="CSS/zurkasse-daten-anzeigen.css">
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
            <li><a href="meinkonto.php">Mein Konto</a></li>
            <li><a class="aktuell" href="warenkorb.php">Warenkorb (<?=$anz?>)</a> </li>
            <li><a href="impressum.php">Impressum</a></li>
        </ul>
    </div>
</nav>
<div id = "content">
    <?php
         $sql = "SELECT * FROM liefer_rechnungs_adresse WHERE ID_user = ?;";
         $stmt = $conn->prepare($sql);
         $stmt->bind_param("i", $uID);
         $stmt->execute();
         $result = $stmt->get_result();
         while($row = $result->fetch_assoc()){
    ?>
    <div class="Box">
                <div class = "lieferadresse">
                <h1>Lieferadresse</h1>
                <div class ="Land">
                      <label class="lable">Land:</label>
                      <p2><?=$row['Land']?></p2>
                </div>
                <br>
                <div class = "plz">
                    <label class="lable">PLZ:</label>
                    <p2><?=$row['liefer_PLZ']?></p2>
                </div>
                <br>
                <div class="ort">
                    <label class="lable">Ort:</label>
                    <p2><?=$row['liefer_Ort']?></p2>
                </div>
                <br>
                <div class="strasse">
                    <label class="lable">Straße:</label>
                    <p2><?=$row['liefer_Strasse']?></p2>
                </div>
                <br>
                <div class = "hausnummer">
                    <label class="lable">Hausnummer:</label>
                    <p2><?=$row['liefer_Hausnummer']?></p2>
                </div>
                </div>
                <div class="rechnungsadresse">
                <h1>Rechnungsadresse</h1>
                 <div class = "plz">
                     <label class="lable">PLZ:</label>
                     <p2><?=$row['rechnungs_PLZ']?></p2>
                 </div>
                 <br>
                 <div class="ort">
                     <label class="lable">Ort:</label>
                     <p2><?=$row['rechnungs_Ort']?></p2>
                 </div>
                 <br>
                 <div class = "strasse">
                     <label class="lable">Straße:</label>
                     <p2><?=$row['rechnungs_Strasse']?></p2>
                 </div>
                 <br>
                 <div class="hausnummer">
                     <label class="lable">Hausnummer:</label>
                     <p2><?=$row['rechnungs_Hausnummer']?></p2>
                 </div>
                 </div>
                 <hr>
                 <div class="btn">
                 <a href="PHP/liefer_rechnungsadresse_aendern.php"><button>ändern</button></a>
                 <a href="bezahlungsmethoden.php"><button>weiter</button></a>
                 </div>
                 <?php
                    }
                 ?>
    </div>
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
