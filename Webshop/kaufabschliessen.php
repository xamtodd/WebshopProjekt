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

    if($anz == 0){
        header('Location: leererwarenkorb.php');
    }
}
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Warenkorb</title>
    <link rel="stylesheet" href="CSS/menue.css">
    <link rel="stylesheet" href="CSS/footer.css">
    <link rel="stylesheet" href="CSS/kaufabschliessen.css">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="JS/menue.js" defer></script>
</head>
<body onload="preisBerechnen()">
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
            <li><a class="aktuell" href="warenkorb.php">Warenkorb (<?=$anz?>)</a></li>
            <li><a href="impressum.php">Impressum</a></li>
        </ul>
    </div>
</nav>
<div id="content">
    <div class='Main'>
        <table>
                <th class="resp">Vorschau</th>
                <th>Produktname</th>
                <th>Größe</th>
                <th>Anzahl</th>
                <th>Stückpreis</th>
                 <?php
                     $sql = "SELECT warenkorb.wAnzahl, katikorien.imgsrc, katikorien.produktname, produkte.groesse, produkte.preis
                     FROM warenkorb, produkte, katikorien WHERE warenkorb.ID_produkt = produkte.ID
                     AND katikorien.ID = produkte.katikorien_ID AND warenkorb.ID_user = ?";
                     $stmt = $conn->prepare($sql);
                     $stmt->bind_param("i", $uID);
                     $stmt->execute();
                     $result = $stmt->get_result();

                     $gPreis = 0;

                     while($row = $result->fetch_assoc()){
                 ?>
                <tr>
                        <td class="resp">
                            <div class="Bild">
                                <img src='IMG/<?=$row['imgsrc']?>' alt = 'brille'>
                            </div>
                        </td>
                        <td>
                            <p><?=$row['produktname']?></p>
                        </td>
                        <td>
                            <p><?=$row['groesse']?></p>
                        </td>
                        <td>
                            <?php $anz =$row['wAnzahl']?>
                             <p><?=$anz?>x</p>
                        </td>
                        <td>
                            <?php $preis = $row['preis']?>
                            <p><?=$preis?>&euro;</p>
                        </td>
                    </tr>
                    <?php
                        $gPreis = $gPreis + $preis * $anz;
                        }
                    ?>
        </table>
        <div class='Preis'>
           <p>Gesammtpreis:</p>
           <p><?=$gPreis?>&euro;</p>
        </div>
        <div class = 'optionen'>
            <a href = 'warenkorb.php'><button>bearbeiten</button></a>
            <?php $art = $_GET['art']?>
            <a href = 'PHP/kaufen.php?art=<?=$art?>&preis=<?=$gPreis?>'><button>Kauf abschießen</button></a>
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
</div>
</body>
</html>