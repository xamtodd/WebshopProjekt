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

    $l_land = $_POST['l_land'];
    $l_plz = $_POST['l_plz'];
    $l_ort = $_POST['l_ort'];
    $l_strasse = $_POST['l_strasse'];
    $l_hausnummer = $_POST['l_hausnummer'];

    if(isset($_POST['cb'])) {
        $r_plz = $_POST['l_plz'];
        $r_ort = $_POST['l_ort'];
        $r_strasse = $_POST['l_strasse'];
        $r_hausnummer = $_POST['l_hausnummer'];
    }else{
        $r_plz = $_POST['r_plz'];
        $r_ort = $_POST['r_ort'];
        $r_strasse = $_POST['r_strasse'];
        $r_hausnummer = $_POST['r_hausnummer'];
    }

    $sql = "INSERT INTO liefer_rechnungs_adresse(ID_user, Land, liefer_PLZ, liefer_Ort, liefer_Strasse, liefer_Hausnummer, 
    rechnungs_PLZ, rechnungs_Ort, rechnungs_Strasse, rechnungs_Hausnummer)
     VALUES ($uID, ?,?,?,?,?,?,?,?,?);";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sisssisss", $l_land, $l_plz, $l_ort, $l_strasse, $l_hausnummer, $r_plz, $r_ort, $r_strasse, $r_hausnummer);
    $stmt->execute();

    header('Location: ../bezahlungsmethoden.php');
?>