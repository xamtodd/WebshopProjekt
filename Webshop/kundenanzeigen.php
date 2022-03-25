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
    <title>Kundenliste</title>
    <link rel="stylesheet" href="CSS/menue.css">
    <link rel="stylesheet" href="CSS/footer.css">
    <link rel="stylesheet" href="CSS/kundenanzeigen.css">
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
        <?php
            $sql = "SELECT COUNT(*) FROM user";
            $result = $conn->query($sql);
            $anzKu = $result->fetch_assoc()['COUNT(*)'];
        ?>
        <div class = 'anz'>
            <p>Anzahl der Kunden:</p>
            <p><?=$anzKu?></p>
        </div>
        <div class = 'Tabelle'>
            <table>
                <th class="resp">Kunden Nr.</th>
                <th>Nutzername</th>
                <th class="resp">Vorname</th>
                <th class="resp">Nachname</th>
                <th>Anz. B.</th>

                <?php
                    $sql = "SELECT *, (SELECT COUNT(*) FROM bestellungen WHERE bestellungen.user_ID = user.ID) AS anzBe FROM user;";
                    $result = $conn->query($sql);
                    while($row = $result->fetch_assoc()){
                ?>

                <tr>
                    <td class="resp"><?=$row['ID']?></td>
                    <td><?=$row['Benutzername']?></td>
                    <td class="resp"><?=$row['Vorname']?></td>
                    <td class="resp"><?=$row['Nachname']?></td>
                    <td><?=$row['anzBe']?></td>
                </tr>
                <?php
                   }
                ?>
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