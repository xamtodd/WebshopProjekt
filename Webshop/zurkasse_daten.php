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

    $sql = "SELECT * FROM liefer_rechnungs_adresse WHERE ID_user = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $uID);
    $stmt->execute();
    $result = $stmt->get_result();
    $anzadresse = $result->num_rows;

    if($anzadresse == 1){
        header('Location: zurkasse_daten_anzeigen.php');
        exit();
    }

    $sql = "SELECT vorname, nachname FROM user WHERE ID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $uID);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()){
        $vn = $row['vorname'];
        $nn = $row['nachname'];
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
        <link rel="stylesheet" href="CSS/zurkasse-daten.css">
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
        <div class="Box">
            <form action="PHP/lieferadresse_rechnungsadresse.php" method="post">
                <h1>Lieferadresse</h1>
                <div class ="Land">
                    <label>Land:*</label>
                    <select name = "l_land">
                        <option>Deutschland</option>
                        <option>Österreich</option>
                        <option>Schweiz</option>
                    </select>
                </div>
                <br>
                <label>PLZ:*</label>
                <input type="number" name="l_plz" placeholder="" required><br>
                <label>Ort:*</label>
                <input type="text" name="l_ort" placeholder="" required><br>
                <label>Straße:*</label>
                <input type="text" name="l_strasse" placeholder="" required><br>
                <label>Hausnummer:*</label>
                <input type="number" name="l_hausnummer" placeholder="" required><br>
                <h1>Rechnungsadresse</h1>
                <div class ="gleich">
                    <label>Gleich Lieferadresse?</label>
                    <input type="checkbox" name = 'cb' id = 'cb' checked onchange="anzeigen()"/>
                </div>
                <br>
                <div id="zusatz">

                </div>
                <br>
                <br>
                <script>
                    function anzeigen(){
                        var b = document.getElementById('cb');
                        if(b.checked){
                            document.getElementById('zusatz').innerHTML = '';
                        }else{
                            var ajax = new XMLHttpRequest();

                            ajax.open("GET", "PHP/zusatzanzeigen.php", true);
                            ajax.send();

                            ajax.onreadystatechange = function () {
                                if (this.readyState == 4 && this.status == 200) {
                                    document.getElementById('zusatz').innerHTML = this.response;
                                }
                            }
                        }
                    }
                </script>
                <hr>
                *Pflichfelder
                <br>
                <br>
                <input type="submit" value="weiter">
            </form>
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
