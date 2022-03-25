
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
    <link rel="stylesheet" href="CSS/warenkorb.css">
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
            <li><a class="aktuell" href="warenkorb.php"><div id= 'anz'>Warenkorb (<?=$anz?>)</div></a></li>
            <li><a href="impressum.php">Impressum</a></li>
        </ul>
    </div>
</nav>
<div id="content">
    <div class='Main'>
        <form method="post" action="PHP/produktauswarenkorbloeschen.php">
            <table>
                <th></th>
                <th class="resp">Vorschau</th>
                <th>Produktname</th>
                <th>Größe</th>
                <th>Anzahl</th>
                <th>Stückpreis</th>
                <th></th>
                <?php
                $benutzername = $_SESSION['benutzername'];

                $sql = "SELECT ID FROM user WHERE benutzername = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("s", $benutzername);
                $stmt->execute();
                $result = $stmt->get_result();

                while ($row = $result->fetch_assoc()){
                    $ID = $row['ID'];
                }

                $sql = "SELECT warenkorb.ID, warenkorb.wAnzahl, warenkorb.ID_produkt, katikorien.imgsrc, katikorien.produktname, produkte.groesse, produkte.anzahl, produkte.preis
                                                                                                                     FROM warenkorb, produkte, katikorien WHERE warenkorb.ID_produkt = produkte.ID
                                                                                                                     AND katikorien.ID = produkte.katikorien_ID AND warenkorb.ID_user = ?;";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("i", $ID);
                $stmt->execute();
                $result = $stmt->get_result();

                $zahl = 1;

                while ($row = $result->fetch_assoc()){
                    ?>
                    <tr>
                        <td>
                            <p><?=$zahl?></p>
                        </td>
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
                            <?php
                            $anzahl = $row['anzahl'];
                            $wAnzahl = $row['wAnzahl'];
                            $ID_produkt = $row['ID_produkt'];

                            $anzahl = $wAnzahl > $anzahl ? $wAnzahl - 1 : $anzahl;
                            echo "<select id='selection$zahl' onchange='aendern($ID_produkt, $zahl)'>";
                            for ($i = 1; $i <= $anzahl + 1; $i++) {
                                if ($wAnzahl == $i) {
                                    echo "<option value='$i' selected>$i</option>";
                                } else {
                                    echo "<option value='$i'>$i</option>";
                                }
                            }
                            ?>
                            </select>
                        </td>
                        <td>
                            <p><?=$row['preis']?>&euro;</p>
                        </td>
                        <td>
                            <input type = 'checkbox' name="loeschen[]" value="<?=$row['ID']?>">
                        </td>
                    </tr>
                    <?php
                    $zahl++;
                }
                ?>
                <tr>
                    <td class="letzteReihe"></td>
                    <td class="vors"></td>
                    <td class="letzteReihe"></td>
                    <td class="letzteReihe"></td>
                    <td class="letzteReihe"></td>
                    <td class="letzteReihe"></td>
                    <td><button type="submit">löschen</button></td>
                </tr>
            </table>
            <input type="hidden" name ='uID' value="<?=$ID?>">
        </form>
        <div class="summe">
            <p>Gesammtsumme:</p>
            <p id = "gesammtsumme"><script></script></p>
        </div>
        <div class ='optionen'>
            <a href="shop.php"><button>Zum Shop</button></a>
            <a href="zurkasse_daten.php"><button>Zur Kasse</button></a>
        </div>
    </div>
    <script>
        function aendern(a, b) {
            var ID = a;
            var classnum = b.toString();
            var c = 'selection' + classnum;
            var aus = document.getElementById(c).value;

            var ajax = new XMLHttpRequest();

            ajax.open("GET", "PHP/anzaendern.php?auswahl="+aus + "&ID="+ID, true);
            ajax.send();

            ajax.onreadystatechange = function () {
                if (this.readyState == 4 && this.status == 200) {
                    document.getElementById('gesammtsumme').innerHTML = this.response + " &euro;";
                }
            }

            var ajax = new XMLHttpRequest();

            ajax.open("GET", "PHP/getAnz.php", true);
            ajax.send();

            ajax.onreadystatechange = function () {
                if (this.readyState == 4 && this.status == 200) {
                    document.getElementById('anz').innerHTML = "Warenkorb (" + this.response + ")";
                }
            }
        }

        function preisBerechnen(){
            var ajax = new XMLHttpRequest();

            ajax.open("GET", "PHP/preisberechnen.php", true);
            ajax.send();

            ajax.onreadystatechange = function () {
                if (this.readyState == 4 && this.status == 200) {
                    document.getElementById('gesammtsumme').innerHTML = this.response + " &euro;";
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