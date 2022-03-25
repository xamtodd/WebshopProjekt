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

    if (!isset($_SESSION['loggedin'])) {
        $anz = 0;
    } else {
        $benutzername = $_SESSION['benutzername'];

        $sql = "SELECT ID FROM user WHERE benutzername = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $benutzername);
        $stmt->execute();
        $result = $stmt->get_result();

        while ($row = $result->fetch_assoc()) {
            $uID = $row['ID'];
        }
    }

    $t = $_GET['t'];

    $sql = "INSERT INTO kundenanliegen(user_ID, text, stempel) VALUES ($uID, ?, CURRENT_TIMESTAMP());";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $t);
    $stmt->execute();
?>