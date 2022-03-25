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