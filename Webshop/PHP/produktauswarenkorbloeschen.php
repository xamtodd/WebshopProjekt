<?php
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

    $ch = $_POST['loeschen'];
    $uID = $_POST['uID'];

    //echo "Erster Wert: $ch[0] <br>";
    //echo "Zweiter Wert: $ch[1] <br>";


    foreach ($ch as $i){
        //anzahl der löschenden Elemnte
        $sql = "SELECT wAnzahl, ID_produkt FROM warenkorb WHERE ID = '$i' AND ID_user = ?;";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $uID);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $anzahl1 = $row['wAnzahl'];
        $pro_ID = $row['ID_produkt'];

        $stmt->close();

        $sql = "UPDATE produkte SET anzahl = anzahl + $anzahl1 WHERE ID = $pro_ID";
        $conn->query($sql);

        $sql = "DELETE FROM warenkorb WHERE ID = $i AND ID_user = $uID;";
        $conn->query($sql);
    }
    header('Location: ../warenkorb.php');
    exit();
?>