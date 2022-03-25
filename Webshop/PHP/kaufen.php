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

    $benutzername = $_SESSION['benutzername'];

    $sql = "SELECT ID FROM user WHERE benutzername = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $benutzername);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $uID = $row['ID'];
    }

    $art = $_GET['art'];
    $preis = $_GET['preis'];

    if(($art == 1) || ($art == 2)){
        $sql = "INSERT INTO bestellungen(user_ID, bezahlt, preis, stempel) VALUES ($uID, true, $preis, CURRENT_TIMESTAMP);";
        $conn->query($sql);
    }else{
        $sql = "INSERT INTO bestellungen(user_ID, bezahlt, preis, stempel) VALUES ($uID, false, $preis, CURRENT_TIMESTAMP);";
        $conn->query($sql);
    }

    $sql = "SELECT ID FROM bestellungen WHERE user_ID = ? ORDER By ID DESC LIMIT 1;";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $uID);
    $stmt->execute();
    $result = $stmt->get_result();
    $bID = $result->fetch_assoc()['ID'];

    $sql = "SELECT * FROM warenkorb WHERE ID_user = ?;";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $uID);
    $stmt->execute();
    $result = $stmt->get_result();
    $anz = $result->num_rows;

    $sql = "SELECT ID_produkt, wAnzahl FROM warenkorb WHERE ID_user = ?;";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $uID);
    $stmt->execute();
    $arrayID = array();
    $arrayAnz = array();
    $result = $stmt->get_result();
    $i = 0;
    while($row = $result->fetch_assoc()){
        $arrayID[$i] = $row['ID_produkt'];
        $arrayAnz[$i] = $row['wAnzahl'];
        $i++;
    }

    for($i = 0; $i <= $anz - 1; $i++){
       $sql = "INSERT INTO bestellungen_produkte(ID_bestellung, ID_produkt, anzahl) VALUES ($bID, $arrayID[$i], $arrayAnz[$i]);";
       $conn->query($sql);

       $sql = "DELETE FROM warenkorb WHERE ID_user = $uID;";
       $conn->query($sql);
    }
    header('Location: ../dankefuerdenkauf.php?bID='.$bID.'&art='.$art.'');
?>