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
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Produkte - Infos</title>
    <link rel="stylesheet" href="CSS/menue.css">
    <link rel="stylesheet" href="CSS/footer.css">
    <link rel="stylesheet" href="CSS/info.css">
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
        <?php
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
        <ul>
            <li><a href="index.php">Startseite</a></li>
            <li><a class="aktuell" href="shop.php">Shop</a></li>
            <li><a href="rub.php">Rezensionen / Bewertungen</a></li>
            <li><a href="meinkonto.php">Mein Konto</a></li>
            <li><a href="warenkorb.php">Warenborb (<?=$anz?>)</a> </li>
            <li><a href="impressum.php">Impressum</a></li>
        </ul>
    </div>
</nav>
<div id="content">
    <div class = "main">
        <div class="infos">
            <form method="post" action="PHP/indenwarenkorb.php">
                <?php
                $ID = $_GET['kat_ID'];

                $sql = "SELECT *, (SELECT Firmenname FROM hersteller WHERE ID = katikorien.hersteller_ID) AS hersteller FROM katikorien WHERE ID = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("i", $ID);
                $stmt->execute();
                $result = $stmt->get_result();

                while ($row = $result->fetch_assoc()){
                ?>
                <input type="hidden" name="kat_ID" value="<?=$ID?>">
                <div class="produktname">
                    <p class='lable'>Produktname:</p>
                    <p><?=$row['produktname']?></p>
                </div>
                <div class="beschreibung">
                    <p class='lable'>Beschreibung:</p>
                    <p><?=$row['lbeschreibung']?></p>
               </div>
               <div class="hersteller">
                   <p class='lable'>Hersteller:</p>
                   <p class='name'><a href="herstelleransehen.php?hersteller_ID=<?=$row['hersteller_ID']?>&kat_ID=<?=$ID?>"><?=$row['hersteller']?></a></p>
               </div>
                <?php
                }
                ?>
                <div class="groesse">
                    <p class = 'lable'>Größen:</p>
                    <select name="groesse">
                        <?php
                            $sql = "SELECT groesse FROM produkte WHERE katikorien_ID = $ID AND anzahl > 0;";
                            $result = $conn->query($sql);

                            while ($row = $result->fetch_assoc()){
                        ?>
                        <option><?=$row['groesse']?></option>
                        <?php
                            }
                        ?>
                    </select>
                </div>
                <div class="tabelle">
                    <table border="1">
                        <th>
                            Größe
                        </th>
                        <th>
                            Auf Lager
                        </th>
                         <th>
                             Stückpreis
                         </th>
                        <?php
                            $sql = "SELECT groesse, anzahl, preis FROM produkte WHERE katikorien_ID  = $ID;";
                            $result = $conn->query($sql);

                            while ($row = $result->fetch_assoc()){
                        ?>
                        <tr>
                            <td><?=$row['groesse']?></td>
                            <td><?=$row['anzahl']?></td>
                            <td><?=$row['preis']?> &euro;</td>
                        </tr>
                        <?php
                            }
                        ?>
                    </table>
                </div>
                <?php
                //Falls noch nicht angemeldet
                    if(isset($_SESSION['loggedin'])){
                        echo "<input type='submit' value='in den Warenkorb'>";
                    }
                ?>
            </form>
            <?php
            //Falls noch nicht angemeldet
            if(!isset($_SESSION['loggedin'])){
                echo "<a href='anmelden.html'><button>zum Shoppen anmelden!</button></a>";
            }
            ?>
            <a href='shop.php'><button>Zurück zum Shop</button></a>
        </div>
        <div class="bild">
            <?php
                $sql = "SELECT * FROM katikorien WHERE ID = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("i", $ID);
                $stmt->execute();
                $result = $stmt->get_result();

                while ($row = $result->fetch_assoc()){
                    $srcbild = $row['imgsrc'];
                }
            ?>
            <img src='IMG/<?=$srcbild?>' alt=''<?=$srcbild?>'>
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