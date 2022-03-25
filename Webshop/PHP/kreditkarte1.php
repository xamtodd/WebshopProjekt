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

     $Kreditkartennummer = null;
     $Gueltigkeit = null;
     $Pruefnummer = null;

    $sql = "SELECT Kreditkartennummer, Gueltigkeit, Pruefnummer FROM bankauszug_kreditkarte_infos WHERE ID_user = ?;";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $uID);
    $stmt->execute();
    $result = $stmt->get_result();
    while($row = $result->fetch_assoc()){
        $Kreditkartennummer = $row['Kreditkartennummer'];
        $Gueltigkeit = $row['Gueltigkeit'];
        $Pruefnummer = $row['Pruefnummer'];
    }
            if($Kreditkartennummer == null){
                $Kontonummer = 0;
            }
            if($Gueltigkeit == null){
                $BLZ = 0;
            }
            if($Pruefnummer){
            }
            if(($Kreditkartennummer == 0) && ($Gueltigkeit == 0) && ($Pruefnummer == 0)){
                $a = 0;
            }else{
                $a = 1;
            }

    if($a == 0){
         echo "<form method = 'POST' action='PHP/kreditkartehinterlegen.php'>
                          <lable>Kreditkartennummer:*</lable>
                          <br>
                          <input type='number' name='kreditkartennummer' required>
                          <br><br>
                          <lable>Gültigkeitsdatum:* (Angabe ohne Punkte)</lable>
                          <br>
                          <input type='number' name='gueltigkeitsdatum' required>
                          <br><br>
                          <lable>Prüfnummer:*</lable>
                          <br>
                          <input type='number' name='pruefnummer' required>
                          <input type='submit' value='weiter'>
                </form>";
    }else{
            $sql = "SELECT Kreditkartennummer, Gueltigkeit, Pruefnummer FROM bankauszug_kreditkarte_infos WHERE ID_user = ?;";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $uID);
            $stmt->execute();
            $result = $stmt->get_result();
            while($row = $result->fetch_assoc()){
                $Kreditkartennummer = $row['Kreditkartennummer'];
                $Gueltigkeit = $row['Gueltigkeit'];
                $Pruefnummer = $row['Pruefnummer'];
            }
         echo "
                <div class = 'kreditkartennummer'>
                <lable>Kreditkartennummer:</lable>
               <p>$Kreditkartennummer</p>
                </div>
                <div class='gueltigkeitsdatum'>
                <lable>Gültigkeitsdatum:</lable>
                <p>$Gueltigkeit</p>
                 </div>
                 <div class='pruefnummer'>
                 <lable>Prüfnummer:</lable>
                 <p>$Pruefnummer</p>
                 </div>
                 <a><button onclick = 'loeschenkreditkarte(); gedrueckt(2)' class = 'aew'>ändern</button></a>
                <a href='kaufabschliessen.php?art=2'><button class = 'aew'>weiter</button></a>";
    }
?>