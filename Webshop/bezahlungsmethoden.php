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
    <link rel="stylesheet" href="CSS/bezahlungsmethoden.css">
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
<div class= "bezahlbox">
    <h1>Bezahlungsmethoden wählen</h1>
    <a><button onclick="gedrueckt(1)">Bankauszug</button></a>
    <div id = 'ba'></div>
    <br>
    <a><button onclick="gedrueckt(2)">Kreditkarte</button></a>
    <div id = "kk"></div>
    <br>
    <a href="kaufabschliessen.php?art=3"><button>Vorkasse</button></a>
    <br>
    <br>
    <a href="kaufabschliessen.php?art=4" ><button>Rechnung</button></a>
</div>
<script>
     function loeschen(){
            var ajax = new XMLHttpRequest();

            ajax.open("GET", "PHP/bankauszugloeschen.php", true);
            ajax.send();
            document.getElementById('ba').innerHTML = "";
     }
    function loeschenkreditkarte(){
        var ajax = new XMLHttpRequest();

        ajax.open("GET", "PHP/kreditkarteloeschen.php", true);
        ajax.send();
        document.getElementById('kk').innerHTML = "";
    }
    function gedrueckt(a){
        if(a === 1){
           if(document.getElementById('ba').innerHTML === ""){
                document.getElementById('kk').innerHTML = "";

                var ajax = new XMLHttpRequest();

                ajax.open("GET", "PHP/bankauszug1.php", true);
                ajax.send();

                ajax.onreadystatechange = function () {
                    if (this.readyState == 4 && this.status == 200) {
                        document.getElementById('ba').innerHTML = this.response;
                    }
                }
           }else{
                document.getElementById('ba').innerHTML = "";
           }
        }
        if(a === 2){
           if(document.getElementById('kk').innerHTML === ""){
                document.getElementById('ba').innerHTML = "";
                var ajax = new XMLHttpRequest();

                ajax.open("GET", "PHP/kreditkarte1.php", true);
                ajax.send();

                ajax.onreadystatechange = function () {
                    if (this.readyState == 4 && this.status == 200) {
                        document.getElementById('kk').innerHTML = this.response;
                    }
                }
           }else{
                document.getElementById('kk').innerHTML = "";
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
</body>
</html>
