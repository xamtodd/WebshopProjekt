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

     $Kontonummer = null;
     $BLZ = null;

    $sql = "SELECT Kontonummer, BLZ FROM bankauszug_kreditkarte_infos WHERE ID_user = ?;";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $uID);
    $stmt->execute();
    $result = $stmt->get_result();
    while($row = $result->fetch_assoc()){
        $Kontonummer = $row['Kontonummer'];
        $BLZ = $row['BLZ'];
    }
            if($Kontonummer == null){
                $Kontonummer = 0;
            }
            if($BLZ == null){
                $BLZ = 0;
            }
            if(($BLZ == 0) && ($Kontonummer == 0)){
                $a = 0;
            }else{
                $a = 1;
            }

    if($a == 0){
       echo "<form method = 'POST' action='PHP/bankauszughinterlegen.php'>
                            <lable>Kontonummer:*</lable>
                            <br>
                            <input type='number' name='kontonummer' required>
                            <br><br>
                            <lable>BLZ:*</lable>
                            <br>
                            <input type='number' name='blz' required>
                           <input type= 'submit' value = 'weiter'>
              </form>";
    }else{
        $sql = "SELECT Kontonummer, BLZ FROM bankauszug_kreditkarte_infos WHERE ID_user = ?;";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $uID);
        $stmt->execute();
        $result = $stmt->get_result();
        while($row = $result->fetch_assoc()){
            $Kontonummer = $row['Kontonummer'];
            $BLZ = $row['BLZ'];
        }


        echo "<div class='kontonummer'>
                  <lable>Kontonummer:</lable>
                  <p>$Kontonummer</p>
              </div>
              <div class='blz'>
              <lable>BLZ:</lable>
                 <p>$BLZ</p>
              </div>
              <a><button onclick = 'loeschen(); gedrueckt(1)' class = 'aew'>ändern</button></a>
              <a href='kaufabschliessen.php?art=1'><button class = 'aew'>weiter</button></a>";
    }
?>