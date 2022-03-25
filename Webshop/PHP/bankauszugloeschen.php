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

       $sql = "UPDATE bankauszug_kreditkarte_infos SET Kontonummer = 0, BLZ = 0 WHERE ID_user = $uID";
       $conn->query($sql);
   }
?>