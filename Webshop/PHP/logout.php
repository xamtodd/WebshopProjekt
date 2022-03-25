<?php
    session_start();
    $benutzername = $_SESSION['benutzername'];
    session_destroy();
    echo "<script>alert('$benutzername, Du bist nun erfolgreich abgemeldet!');</script>";
    echo "<script>location.replace('../index.php');</script>";
    exit();
?>