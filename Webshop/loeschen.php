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
    <title>Löschen</title>
    <link rel="stylesheet" href="CSS/menue.css">
    <link rel="stylesheet" href="CSS/loeschen.css">
    <link rel="stylesheet" href="CSS/footer.css">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="JS/menue.js" defer></script>
    <script src="JS/warenkorb.js" defer></script>
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
            <li><a href ='shop.php'>Shop</a></li>
            <li><a href="rub.php">Rezensionen / Bewertungen</a></li>
            <li><a href="meinkonto.php" class='aktuell'>Mein Konto</a></li>
            <li><a href="warenkorb.php">Warenkorb (<?=$anz?>)</a></li>
            <li><a href="impressum.php">Impressum</a></li>
        </ul>
    </div>
</nav>
<div id="content">
    <div class="main">
        <div class = 'hersteller'>
            <h1>Hersteller</h1>
            <form method='POST', action='PHP/herstellerloeschen.php'>
            <table class = 'hers'>
                <th class = 'neunz'>Firmenname</th>
                <th class ='wa'>Webadresse</th>
                <th class ='cb'></th>
                <?php
                    $sql = "SELECT * FROM hersteller";
                    $result = $conn->query($sql);
                    while($row = $result->fetch_assoc()){
                ?>
                <tr>
                    <td><?=$row['Firmenname']?></td>
                    <td class ='wa'><?=$row['Webadresse']?></td>
                    <td class = 'inp'><input type='checkbox' value="<?=$row['ID']?>" name = 'hID[]'></td>
                </tr>
                <?php
                    }
                ?>
                  <tr>
                        <td class = 'inp'></td>
                        <td class='inp2'></td>
                        <td class = 'inp'><button>löschen</button></td>
                  </tr>
                  </form>
            </table>
        </div>
        <div class = 'katikorie'>
            <form method='POST' action ='PHP/katikorienloeschen.php'>
            <h1>Kategorie</h1>
            <table class = 'katiko'>
                <th class = 'neunz'>Kategorie</th>
                <th class ='wa'>Vorschau</th>
                <th class ='cb'></th>
                <?php
                    $sql = "SELECT * FROM katikorien";
                    $result = $conn->query($sql);
                    while($row = $result->fetch_assoc()){
                ?>
                <tr>
                    <td><?=$row['produktname']?></td>
                    <td class ='wa'><img src="IMG/<?=$row['imgsrc']?>"></td>
                    <td class = 'inp'><input type='checkbox' value="<?=$row['ID']?>" name = 'kID[]'></td>
                </tr>
                <?php
                    }
                ?>
                  <tr>
                        <td class = 'inp'></td>
                        <td class = 'inp2'></td>
                        <td class = 'inp'><button>löschen</button></td>
                  </tr>
                  </form>
            </table>
        </div>
        <div class = 'produkte'>
              <h1>Produkte</h1>
              <form method='POST' action='PHP/produktloeschen.php'>
              <table class = 'prod'>
                  <th class = 'neunz'>Produkt</th>
                  <th class = 'wa'>Anzahl</th>
                  <th class ='cb'></th>
                <?php
                    $sql = "SELECT produkte.ID, katikorien.produktname, produkte.anzahl, produkte.groesse FROM produkte, katikorien WHERE produkte.katikorien_ID = katikorien.ID";
                    $result = $conn->query($sql);
                    while($row = $result->fetch_assoc()){
                ?>
                  <tr>
                      <td><?=$row['produktname']?>, <?=$row['groesse']?></td>
                      <td class = 'wa'><?=$row['anzahl']?></td>
                      <td class ='inp'><input type='checkbox' name = 'proid[]' value="<?=$row['ID']?>"></td>
                  </tr>
                  <?php
                    }
                  ?>
                <tr>
                      <td class = 'inp'></td>
                      <td class = 'inp2'></td>
                      <td class = 'inp'><button type = 'submit'>löschen</button></td>
                </tr>
                </form>
              </table>
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