<?php
    session_start();

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

    $benutzername = $_POST['benutzername'];
    $beutzerpasswort = $_POST['beutzerpasswort'];
    $passwordindb = hash('sha256', $beutzerpasswort);

    $sql = "SELECT * FROM user WHERE Benutzername = ?;";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $benutzername);
    $stmt->execute();
    $result = $stmt->get_result();
    $anz = $result->num_rows;

    if($anz != 0){
        $sql = "SELECT Passwort FROM user WHERE Benutzername = ?;";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $benutzername);
        $stmt->execute();
        $result = $stmt->get_result();
        $passwort = $result->fetch_assoc()['Passwort'];

        $conn->close();

        if($passwort == $passwordindb){
            $_SESSION['benutzername'] = $benutzername;
            $_SESSION['loggedin'] = true;
            echo "<script>alert('Hallo $benutzername. Du bist nun erfolgreich angemeldet!');</script>";
            echo "<script>location.replace('../index.php');</script>";
            exit();
        }else{
            echo "<script>alert('Das Passwort stimmt nicht überein!');</script>";
            echo "<script>location.replace('../anmelden.html');</script>";
            exit();
        }
    }else{
        echo "<script>alert('Es ist ein Fehler aufgetreten!');</script>";
        echo "<script>location.replace('../anmelden.html');</script>";
        exit();
    }
?>