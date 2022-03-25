<?php
    session_start();

    //Falls noch nicht angemeldet
    if(!isset($_SESSION['loggedin'])){
        header('Location: ../anmelden.html');
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

    //userID hohlen
    $benutzername = $_SESSION['benutzername'];

    $sql = "SELECT ID FROM user WHERE benutzername = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $benutzername);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()){
        $user_ID = $row['ID'];
    }

    $kat_ID = $_POST['kat_ID'];
    $groesse = $_POST['groesse'];

    //Produkt_ID hohlen
    $sql = "SELECT ID FROM produkte WHERE katikorien_ID = ? AND groesse = ?;";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("is", $kat_ID, $groesse);
    $stmt->execute();
    $result = $stmt->get_result();
    $pro_ID = $result->fetch_assoc()['ID'];

    //anzahl hohlen
    $sql = "SELECT anzahl FROM produkte WHERE ID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $pro_ID);
    $stmt->execute();
    $result = $stmt->get_result();
    $anzahl = $result->fetch_assoc()['anzahl'];

    //anzahl verringern
    $anzahl--;

    //anzahl aktualliesieren
    $sql = "UPDATE produkte SET anzahl = $anzahl WHERE ID = $pro_ID";
    $conn->query($sql);

    //In den Warenkorb
    $sql = "SELECT * FROM warenkorb WHERE ID_produkt = ? AND ID_user = ?;";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $pro_ID, $user_ID);
    $stmt->execute();
    $result = $stmt->get_result();
    $ergebisse = $result->num_rows;

    if($ergebisse == null){
        $sql = "INSERT INTO warenkorb(ID_user, ID_produkt, wAnzahl) VALUES (?,?,1);";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $user_ID, $pro_ID);
        $stmt->execute();
    }else{
        $sql = "SELECT wAnzahl FROM warenkorb WHERE ID_produkt = ? AND ID_user = ?;";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $pro_ID, $user_ID);
        $stmt->execute();
        $result = $stmt->get_result();
        $wAnzahl = $result->fetch_assoc()['wAnzahl'];
        $wAnzahl++;
        $sql = "UPDATE warenkorb SET wAnzahl = $wAnzahl WHERE ID_produkt = $pro_ID AND ID_user = $user_ID";
        $conn->query($sql);
    }
    echo "<script>alert('Das Produkt wurde erfolgreich zum Warenkorb hinzugefuegt!');</script>";
    echo "<script>location.replace('../info.php?kat_ID=".$kat_ID."');</script>";
    exit();
?>
