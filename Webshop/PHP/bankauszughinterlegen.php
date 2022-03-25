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

    $kontonummer = $_POST['kontonummer'];
    $BLZ = $_POST['blz'];

    $sql = "SELECT * FROM bankauszug_kreditkarte_infos WHERE ID_user = ?;";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $uID);
    $stmt->execute();
    $result = $stmt->get_result();
    $anz = $result->num_rows;;


    if($anz == 0){
         $sql = "INSERT INTO bankauszug_kreditkarte_infos(ID_user, Kontonummer, BLZ, Kreditkartennummer, Gueltigkeit, Pruefnummer)
         VALUES (?,?,?, 0000, 0000, 0000);";
         $stmt = $conn->prepare($sql);
         $stmt->bind_param("iii", $uID, $kontonummer, $BLZ);
         $stmt->execute();
    }else{
        $sql = "UPDATE bankauszug_kreditkarte_infos SET Kontonummer = ?, BLZ = ? WHERE ID_user = ?;";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iii", $kontonummer, $BLZ, $uID);
         $stmt->execute();
    }
    header('Location: ../kaufabschliessen.php?art=1');
?>