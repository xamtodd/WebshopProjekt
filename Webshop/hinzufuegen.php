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

?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Hinzufügen</title>
    <link rel="stylesheet" href="CSS/menue.css">
    <link rel="stylesheet" href="CSS/footer.css">
    <link rel="stylesheet" href="CSS/hinzufuegen.css">
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
    <div class = 'Main'>
        <div class ='katikorie'>
        <h1>Hersteller hinzufügen</h1>
        <div class ='herstellerbox'>
        <form id = 'form1'>
            <div class='Firmename'>
                <p>Firmenname:</p>
                <input type='text' placeholder='Beispiel GmbH' id='fn'>
            </div>
            <div class='Webadresse'>
                <p>Webadresse:</p>
                <input type='text' placeholder='www.firma-beispiel.de' id ='wa'>
            </div>
            <div class='EMail'>
                <p>E-Mail:</p>
                <input type='mail' placeholder='firma@beispiel.de' id='em'>
            </div>
        </div>
        </form>
                <div class = 'btn'>
                    <a onclick='hersteller()'><button>Hersteller hinfuegen</button></a>
                </div>
        </div>
        <hr>
        <div class ='katikorie'>
        <h1>Katikorie hinzufügen</h1>
        <form method = 'POST' action='PHP/hochladen.php' enctype='multipart/form-data'>
            <div class ='katikobox'>
                <div class='ProName'>
                    <p>Produktname:</p>
                    <input type='text' placeholder='Taucherbrille' name ='Name'>
                </div>
                <div class='Bild'>
                    <p>Bild:</p>
                    <input type='file' id = 'bild' name = 'file'>
                </div>
                <div class='Kurzbeschreibung'>
                    <p>Kurzbeschreibung:</p>
                    <textarea name ='textarea'>Kurzbeschreibung</textarea>
                </div>
                <div class='langebeschreibung'>
                    <p>Ausführliche Produktbeschreibung:</p>
                    <textarea name ='textarea2'>Ausführliche Produktbeschreibung</textarea>
                </div>
                <div class='hersteller'>
                <p>Hersteller:</p>
                <select name ='hsel'>
                    <?php
                         $sql = "SELECT * FROM hersteller";
                         $result = $conn->query($sql);
                         while($row = $result->fetch_assoc()){
                    ?>
                    <option value='<?=$row['ID']?>'><?=$row['Firmenname']?></option>
                    <?php
                    }
                    ?>
                </select>
                </div>
            </div>
            <div class = 'btn'>
                <button type='submit'>Katikorie hinfuegen</button>
            </div>
        </form>
        </div>
        <hr>
        <div class = 'box'>
            <h1>Produkte hinzufügen</h1>
            <div class = 'hinz'>
                <div class ='inp'>
                <form id = 'form3'>
                    <div class = 'kat'>
                        <p>Katikorie auswählen</p>
                        <select id = 'katsel'>
                            <?php
                                $sql = "SELECT * FROM katikorien";
                                $result = $conn->query($sql);
                                while($row = $result->fetch_assoc()){
                            ?>
                            <option value='<?=$row['ID']?>'><?=$row['produktname']?></option>
                            <?php
                            }
                            ?>
                        </select>
                    </div>
                    <div class = 'groesse'>
                        <p>Größe:</p>
                        <input type= 'text' placeholder='m' id = 'g'>
                    </div>
                    <div class = 'Preis'>
                        <p>Preis(in &euro;):</p>
                        <input type= 'number' placeholder='20 &euro;' id ='p'>
                    </div>
                    <div class = 'Anzahl'>
                        <p>Anzahl:</p>
                        <input type= 'number' placeholder='10' id = 'a'>
                    </div>
                </div>
                </form>
                <div class = 'btn'>
                    <a onclick = 'produkte()'><button>Produkte hinfuegen</button></a>
                </div>
            </div>
        </div>
        <script>
            function hersteller(){
                var fn = document.getElementById('fn').value;
                var wa = document.getElementById('wa').value;
                var em = document.getElementById('em').value;

                var ajax = new XMLHttpRequest();

                ajax.open("GET", "PHP/herstellerhinzufuegen.php?fn="+fn+"&wa="+wa+"&em="+em, true);
                ajax.send();

                ajax.onreadystatechange = function () {
                    if (this.readyState == 4 && this.status == 200) {
                        alert(this.response);
                        document.getElementById('form1').reset();
                    }
                }
            }
            function produkte(){
                var katsel = document.getElementById('katsel').value;
                var g = document.getElementById('g').value;
                var p = document.getElementById('p').value;
                var a = document.getElementById('a').value;

                var ajax = new XMLHttpRequest();

                ajax.open("GET", "PHP/produktehinzufuegen.php?katsel="+katsel+"&g="+g+"&p="+p+"&a="+a, true);
                ajax.send();

                ajax.onreadystatechange = function () {
                    if (this.readyState == 4 && this.status == 200) {
                        alert(this.response);
                        document.getElementById('form3').reset();
                    }
                }
            }
        </script>
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