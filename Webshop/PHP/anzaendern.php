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
    $pro_ID = $_GET['ID'];
    $aus = $_GET['auswahl'];

    $sql = "SELECT anzahl FROM produkte WHERE ID = ?;";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $pro_ID);
    $stmt->execute();
    $result = $stmt->get_result();
    $anzahl = $result->fetch_assoc()['anzahl'];

    $sql = "SELECT wAnzahl FROM warenkorb WHERE ID_produkt = ? AND ID_user = ?;";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $pro_ID, $uID);
    $stmt->execute();
    $result = $stmt->get_result();
    $wAnzahl = $result->fetch_assoc()['wAnzahl'];

    $delta = $wAnzahl - $aus;

    $sql = "UPDATE warenkorb SET wAnzahl = $aus WHERE ID_produkt = $pro_ID AND ID_user = $uID;";
    $conn->query($sql);

    $sql = "UPDATE produkte SET anzahl = anzahl + $delta WHERE ID = $pro_ID;";
    $conn->query($sql);

    $sql = "SELECT preis, wAnzahl FROM produkte, warenkorb WHERE produkte.ID = warenkorb.ID_produkt AND warenkorb.ID_user = ?;";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $uID);
    $stmt->execute();
    $result = $stmt->get_result();

    $gpreis = 0;

    while ($row = $result->fetch_assoc()){
        $preis = $row['preis'];
        $anz = $row['wAnzahl'];

        $gpreis = $gpreis + $preis * $anz;
    }
    echo $gpreis;
?>