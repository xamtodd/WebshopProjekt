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

    $benutzername = $_POST['benutzername'];
    $vorname = $_POST['vorname'];
    $nachname = $_POST['nachname'];
    $geburtsdatum = $_POST['geburtsdatum'];
    $email = $_POST['email'];
    $passworteingabe = $_POST['passworteingabe'];
    $passwortwiederhohlung = $_POST['passwortwiederhohlung'];
    $pwhash = hash('sha256', $passworteingabe);

    if($passworteingabe == $passwortwiederhohlung){
            //nutzernamen pruefen
            $sql = "SELECT * FROM user WHERE Benutzername = ?;";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $benutzername);
            $stmt->execute();
            $result = $stmt->get_result();
            $anz = $result->num_rows;

            if($anz == 0){
                //ACC anlegen
                $sql = "INSERT INTO user(Benutzername, Vorname, Nachname, Geburtsdatum, Email, Passwort, Zeitstempel) VALUES (?,?,?,?,?,?, CURRENT_TIMESTAMP());";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ssssss", $benutzername, $vorname, $nachname,$geburtsdatum, $email, $pwhash);
                $stmt->execute();

                //user ID hohlen
                $sql = "SELECT ID FROM user WHERE Benutzername = ?;";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("s", $benutzername);
                $stmt->execute();
                $result = $stmt->get_result();
                $ID = $result->fetch_assoc()['ID'];

                //rolle anlgen
                $sql = "INSERT INTO rollen(user_ID, level, admin_anfrage, admin) VALUES (?,1,0,0)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("i", $ID);
                $stmt->execute();

                $conn->close();
                echo "<script>alert('Hallo, $benutzername! Sie sind nun erfolgreich registiert!');</script>";
                echo "<script>location.replace('../index.php');</script>";
                exit();
            }else{
                $conn->close();
                echo "<script>alert('Dieser Nutzername ist schon vergeben!');</script>";
                echo "<script>location.replace('../registieren.html');</script>";
                exit();
            }
    }else{
        echo "<script>alert('Es ist ein Fehler aufgetreten!');</script>";
        echo "<script>location.replace('../registieren.html');</script>";
        exit();
    }
?>