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

    $bID = intval($_GET['bID']);
?>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Rechnungdrucken</title>
        <link rel="stylesheet" href="CSS/rechnungdrucken.css">
        <meta name="viewport" content="width=device-width, initial-scale=1">
    </head>
    <body>
    <div id = "content">
        <h1>Rechnung - Tauchershop</h1>
        <br>
        <table>
                <tr>
                    <th>Produktname</th>
                    <th>Größe</th>
                    <th>Anzahl</th>
                    <th>Stückpreis</th>
                </tr>
                <?php
                        $sql = "SELECT katikorien.produktname, bestellungen_produkte.anzahl, produkte.groesse, produkte.preis
                        FROM bestellungen_produkte, produkte,katikorien
                        WHERE bestellungen_produkte.ID_produkt = produkte.ID
                        AND produkte.katikorien_ID = katikorien.ID
                        AND bestellungen_produkte.ID_bestellung = ?
                        GROUP BY bestellungen_produkte.ID;";

                        $stmt = $conn->prepare($sql);
                        $stmt->bind_param("i", $bID);
                        $stmt->execute();
                        $result = $stmt->get_result();
                        while($row = $result->fetch_assoc()){
                ?>
                <tr>
                        <td>
                            <p><?=$row['produktname']?></p>
                        </td>
                        <td>
                            <p><?=$row['groesse']?></p>
                        </td>
                        <td>
                            <?php $anz =$row['anzahl']?>
                             <p><?=$anz?>x</p>
                        </td>
                        <td>
                            <?php $preis = $row['preis']?>
                            <p><?=$preis?>&euro;</p>
                        </td>
                </tr>
                <?php
                    }
                ?>
        </table>
        <br>
        <br>
        <?php
             $sql = "SELECT preis, bezahlt, stempel FROM bestellungen WHERE ID = ?;";
             $stmt = $conn->prepare($sql);
             $stmt->bind_param("i", $bID);
             $stmt->execute();
             $result = $stmt->get_result();
             while($row = $result->fetch_assoc()){
                $preis = $row['preis'];
                $bezahlt = $row['bezahlt'];
                $stempel = $row['stempel'];
             }
        ?>
        <div class = 'Informatioen'>
            <div class = 'Preis'>
                <lable>Gesammtpreis:</lable>
                <p><?=$preis?>&euro;</p>
            </div>
            <div class = 'Zeitstempel'>
                <lable>Zeitstempel:</lable>
                <p><?=$stempel?></p>
            </div>
            <div class = 'Status'>
                <lable>Status:</lable>
                <p>Rechnung <?php if($bezahlt == 1){echo "bezahlt";} else{echo "ausstehend";}?></p>
            </div>
            <?php
                 $sql = "SELECT * FROM liefer_rechnungs_adresse WHERE ID_user = ?;";
                 $stmt = $conn->prepare($sql);
                 $stmt->bind_param("i", $user_ID);
                 $stmt->execute();
                 $result = $stmt->get_result();
                 while($row = $result->fetch_assoc()){
            ?>
            <div class = 'Rechnungsadresse'>
                <lable>Rechnungsadresse:</lable>
                <p><?=$row['Land']?>, <?=$row['rechnungs_PLZ']?>, <?=$row['rechnungs_Ort']?>, <?=$row['rechnungs_Strasse']?> <?=$row['rechnungs_Hausnummer']?></p>
                </div>
            <div class = 'Lieferadresse'>
                <lable>Lieferadresse:</lable>
                <p><?=$row['Land']?>, <?=$row['liefer_PLZ']?>, <?=$row['liefer_Ort']?>, <?=$row['liefer_Strasse']?> <?=$row['liefer_Hausnummer']?></p>
            </div>
           <?php
                }
            ?>
        </div>
        <br>
        <br>
        <div class='danke'>
            <p>Danke für Ihren Einkauf! Ihr Tauchershop!<p>
        </div>
    </div>
    <script>
        self.print();
      	if (window.matchMedia) {
              var mediaQueryList = window.matchMedia('print');
              mediaQueryList.addListener(function(mql) {
                  if (!mql.matches){
                      window.close();
                  }
              });
          }
    </script>
    </body>
</html>