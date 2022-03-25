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

     $hID = $_POST['hID'];

     foreach($hID as $i){
        $sql = "SELECT ID FROM katikorien WHERE hersteller_ID = $i";
        $result = $conn->query($sql);

        $array = array();
        while($row = $result->fetch_assoc()){
            array_push($array, $row);
        }

        foreach($array as $a){
             $sql = "SELECT imgsrc FROM katikorien WHERE ID = ".$a['ID'];
             $result = $conn->query($sql);

             $array = array();
             while($row = $result->fetch_assoc()){
                 array_push($array, $row);
             }

             foreach($array as $c){
                  $path = "../IMG/".$c['imgsrc'];
                  unlink($path);
             }

            $sql = "SELECT ID FROM produkte WHERE katikorien_ID = ".$a['ID'];
            $result = $conn->query($sql);

            $array = array();
            while($row = $result->fetch_assoc()){
                array_push($array, $row);
            }

            foreach($array as $b){
                $sql = "DELETE FROM warenkorb WHERE ID_produkt = ".$b['ID'];
                $conn->query($sql);
            }
            $sql = "DELETE FROM produkte WHERE katikorien_ID = ".$b['ID'];
            $conn->query($sql);

            $sql = "DELETE FROM katikorien WHERE ID = ".$a['ID'];
            $conn->query($sql);
        }

        $sql ="DELETE FROM hersteller WHERE ID = $i";
        $conn->query($sql);
     }

    header('Location: ../loeschen.php');
    //bildlöschen
    //aus anderen Tabellen raus
?>