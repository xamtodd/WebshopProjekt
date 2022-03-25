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

    $bew = $_GET['bew'];
    $anzFische = $_GET['anzFische'];
    $kom = $_GET['kom'];

    $sql = "INSERT INTO bewertungen(user_ID, art, fische, kommentar, stempel) VALUES ($uID, ?, $anzFische, ?, CURRENT_TIMESTAMP());";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $bew, $kom);
    $stmt->execute();


     $sql = "SELECT *, user.benutzername
     FROM bewertungen, user
     WHERE user.ID = bewertungen.user_ID ORDER BY bewertungen.stempel DESC;";

     $result = $conn->query($sql);
     while($row = $result->fetch_assoc()){

     echo"           <div class ='box'>
                    <div class = 'Nutzer'>
                        <p>Nutzer:</p>
                        <p>".$row['benutzername'].", ".$row['stempel']."</p>
                    </div>
                    <div class = 'Art'>
                        <p>Art der Bewertung:</p>
                        <p>".$row['art']."</p>
                    </div>
                    <div class = 'Bewertung'>
                        <p>Bewertung:</p>
                        <div class ='AnzeigeFi'>
                            ";
                                $anzFische = $row['fische'];
                                $saver = 1;

                         for($i = 0; $i <= $anzFische - 1; $i++){
                                    echo "<a><img src='IMG/fisch2.png'></a>";
                                    $saver++;
                                }
                                for($i = $saver; $i <= 6; $i++){
                                    echo "<a><img src='IMG/fisch1.png'></a>";
                                }
    echo"                   </div>
                    </div>
                    <div class = 'Kommentar'>
                        <p>Kommentar:</p>
                        <p>".$row['kommentar']."</p>
                    </div>
                </div>";
                }

  ?>

