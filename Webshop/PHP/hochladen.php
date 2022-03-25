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

       $ktext = $_POST['textarea'];
       $ltext = $_POST['textarea2'];
       $name = $_POST['Name'];
       $file = $_FILES['file'];
       $filename = $_FILES['file']['name'];
       $h = $_POST['hsel'];

        if(($ktext == null) || ($ltext == null) || ($name == null) || ($filename == null)){
            header('Location: ../hinzufuegen.php');
        }

       $temp = $_FILES['file']['tmp_name'];
       $type = $_FILES['file']['type'];

       $fileExt = explode('.', $filename);
       $fileAE = strtolower(end($fileExt));

       $fileNameNeu = $_POST['Name'].".".$fileAE;

       $allwoed = array('jpg');

       if(in_array($fileAE, $allwoed)){
           $fileDE = '../IMG/'.$fileNameNeu;
           move_uploaded_file($temp, $fileDE);

            $sql = "INSERT INTO katikorien(produktname, kbeschreibung, lbeschreibung,imgsrc, hersteller_ID, stempel)
            VALUES (?,?,?,?,?, CURRENT_TIMESTAMP())";
             $stmt = $conn->prepare($sql);
             $stmt->bind_param("ssssi", $name, $ktext, $ltext, $fileNameNeu, $h);
             $stmt->execute();

             header('Location: ../hinzufuegen.php');
       }else{
       }
     ?>