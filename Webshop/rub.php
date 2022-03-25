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
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Rezensionen / Bewertungen</title>
    <link rel="stylesheet" href="CSS/menue.css">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="CSS/rub.css">
    <link rel="stylesheet" href="CSS/footer.css">
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
            <li><a class="aktuell" href="rub.php">Rezensionen / Bewertungen</a></li>
            <li><a href="meinkonto.php">Mein Konto</a></li>
            <li><a href="warenkorb.php">Warenkorb (<?=$anz?>)</a></li>
            <li><a href="impressum.php">Impressum</a></li>
        </ul>
    </div>
</nav>
<div id="content">
    <div id = 'Main'>
    <?php
        if(!isset($_SESSION['loggedin'])){
    ?>
    <div class = 'nichtangemeldet'>
        <h1>Um eine Rezension zu schrieben, müssen Sie sich anmelden!</h1>
        <a href='anmelden.html'><button>anmelden</button></a>
    </div>
    <?php
       }else{
    ?>
    <form id = 'form'>
            <div class = 'Produktauswahl'>
                <p>Welche Bewertung wollen Sie durchführen?</p>
                <select name = 'auswahl' id = 'sel'>
                      <option value = 'Allgemiene Shop Bewertung'>Allgemiene Shop Bewertung</option>
                      <optgroup label = 'Produktkatikorie'>
                        <?php
                            $sql = "SELECT * FROM katikorien";
                            $result = $conn->query($sql);
                            while($row = $result->fetch_assoc()){
                        ?>
                        <option value = '<?=$row['produktname']?>'><?=$row['produktname']?></option>
                        <?php
                            }
                        ?>
                      </optgroup>
                </select>
            </div>
            <div class = 'Fische'>
                <p>Wie viele Fische vergeben Sie?</p>
                <div class = 'rating'>
                    <a onclick = 'fisch(1)'><img id = 'img1' src='IMG/fisch1.png'></a>
                    <a onclick = 'fisch(2)'><img id = 'img2' src='IMG/fisch1.png'></a>
                    <a onclick = 'fisch(3)'><img id = 'img3' src='IMG/fisch1.png'></a>
                    <a onclick = 'fisch(4)'><img id = 'img4' src='IMG/fisch1.png'></a>
                    <a onclick = 'fisch(5)'><img id = 'img5' src='IMG/fisch1.png'></a>
                    <a onclick = 'fisch(6)'><img id = 'img6' src='IMG/fisch1.png'></a>
                </div>
            </div>
            <div>
            </div>
            <div class ='Text'>
                <div class = 'Inhalt'>
                    <p>Geben Sie einen Kommentar ab:</p>
                    <textarea rows='10' id='Kommentar'>Dies ist meine Bewertung!</textarea>
                </div>
                <button onclick="bewerten()">Senden</button>
            </div>
            <input id = 'anzFische' type = 'hidden' value='0'>
        </form>
        <?php
            }
        ?>
         <hr>
         <div id = 'comments'>
             <?php
                 $sql = "SELECT *, user.benutzername
                 FROM bewertungen, user
                 WHERE user.ID = bewertungen.user_ID ORDER BY bewertungen.stempel DESC;";

                 $result = $conn->query($sql);
                 while($row = $result->fetch_assoc()){
             ?>
            <div class ='box'>
                <div class = 'Nutzer'>
                    <p>Nutzer:</p>
                    <p><?=$row['benutzername']?>, <?=$row['stempel']?></p>
                </div>
                <div class = 'Art'>
                    <p>Art der Bewertung:</p>
                    <p><?=$row['art']?></p>
                </div>
                <div class = 'Bewertung'>
                    <p>Bewertung:</p>
                    <div class ='AnzeigeFi'>
                        <?php
                            $anzFische = $row['fische'];
                            $saver = 1;
                            for($i = 0; $i <= $anzFische - 1; $i++){
                                echo "<a><img src='IMG/fisch2.png'></a>";
                                $saver++;
                            }
                            for($i = $saver; $i <= 6; $i++){
                                echo "<a><img src='IMG/fisch1.png'></a>";
                            }
                        ?>
                   </div>
                </div>
                <div class = 'Kommentar'>
                    <p>Kommentar:</p>
                    <p><?=$row['kommentar']?></p>
                </div>
            </div>
            <?php
               }
            ?>
        </div>
    </div>
    <script>
        function fisch(a){
            document.getElementById('anzFische').value = a;
            var counter = 0;
            for(var i = 0; i <= a - 1; i++){
               var pfad = 'img' + (i + 1);
               document.getElementById(pfad).src = "IMG/fisch2.png";
               counter++;
            }

            for(var i = counter; i < 6; i++){
               var pfad = 'img' + (i + 1);
               document.getElementById(pfad).src = "IMG/fisch1.png";
            }
        }
        function bewerten(){
               var bew = document.getElementById('sel').value;
               var anzFische = document.getElementById('anzFische').value;
               var kom = document.getElementById('Kommentar').value;

               var ajax = new XMLHttpRequest();

               ajax.open("GET", "PHP/bewerten.php?bew="+bew+"&anzFische="+anzFische+"&kom="+kom, true);
               ajax.send();



               ajax.onreadystatechange = function () {
                   if (this.readyState == 4 && this.status == 200) {
                        document.getElementById('comments').innerHTML = this.responseText;
                   }
               }
               document.form.reset();
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
</body>
</html>