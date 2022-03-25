<?php
    session_start();

    if(!isset($_SESSION['loggedin'])){
        header('Location: anmelden.html');
    }
    echo "          <label>PLZ*:</label>
                    <input type='number' name='r_plz' placeholder='' required><br>
                    <label>Ort*:</label>
                    <input type='text' name='r_ort' placeholder='' required><br>
                    <label>Straße*:</label>
                    <input type='text' name='r_strasse' placeholder='' required><br>
                    <label>Hausnummer*:</label>
                    <input type='text' name='r_hausnummer' placeholder='' required><br>";
?>