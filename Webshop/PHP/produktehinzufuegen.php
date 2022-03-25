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
    $katsel = $_GET['katsel'];
    $g = $_GET['g'];
    $p = $_GET['p'];
    $a = $_GET['a'];

    if($katsel == null || $g == null || $p == null || ($a == null)){
        echo "Es fehlen Eingaben!";
    }else{
        $sql = "INSERT INTO produkte(katikorien_ID, groesse, anzahl, preis)
        VALUES (?,?,?,?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("isis", $katsel, $g, $a, $p);
        $stmt->execute();

        echo "Produkt wurde erfolgreich eingetragen!";
    }
?>