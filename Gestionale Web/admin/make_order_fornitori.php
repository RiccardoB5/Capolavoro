<?php
session_start();
include('config/config.php');
include('config/checklogin.php');
check_login();

// Elabora l'invio del form
if (isset($_POST['placeOrder'])) {
    // Ottieni i dati dal form
    $ID_Fornitore = $_POST['ID_Fornitore'];
    $ID_Articolo = $_POST['ID_Articolo'];
    $Data_Acquisto = $_POST['Data_Acquisto'];
    $Quantità = $_POST['Quantità'];
    $Costo = $_POST['Costo'];
    $Stato = $_POST['Stato'];

    // Prepara la query SQL per inserire i dati
    $query = "INSERT INTO acquisti_fornitori (ID_Fornitore, ID_Articolo, Data_Acquisto, Quantità, Costo, Stato) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $mysqli->prepare($query);
    if ($stmt) {
        // Collega i parametri e esegui
        $stmt->bind_param("iisids", $ID_Fornitore, $ID_Articolo, $Data_Acquisto, $Quantità, $Costo, $Stato);
        $stmt->execute();
        if ($stmt) {
            $success = "Order Placed" && header("refresh:1; url=order_fornitori.php");
        } else {
            $err = "Please Try Again Or Try Later";
        }
    } else {
        $err = "Errore nella preparazione della query: " . $mysqli->error;
    }
}

// Ottieni i fornitori
$query_fornitori = "SELECT ID_Fornitore, Nome FROM anagrafica_fornitori";
$fornitori = $mysqli->query($query_fornitori);

// Ottieni gli articoli
$query_articoli = "SELECT ID_Prodotto, Nome FROM anagrafica_prodotti";
$articoli = $mysqli->query($query_articoli);

require_once('partials/_head.php');
?>

<body>
    <?php
    require_once('partials/_sidebar.php');
    ?>
    <div class="main-content">
        <?php require_once('partials/_topnav.php'); ?>
        <div style="background-image: url(../admin/assets/img/theme/restro00.jpg); background-size: cover;" class="header pb-8 pt-5 pt-md-8">
            <span class="mask bg-gradient-dark opacity-8"></span>
            <div class="container-fluid">
                <div class="header-body">
                </div>
            </div>
        </div>
        <div class="container-fluid mt--7">
            <div class="row">
                <div class="col">
                    <div class="card shadow">
                        <div class="card-header bg-transparent">
                            <h3 class="mb-0">Effettua un Ordine</h3>
                        </div>
                        <div class="card-body">
                            <form method="POST">
                                <div class="form-group">
                                    <label for="ID_Fornitore">Fornitore</label>
                                    <select class="form-control" id="ID_Fornitore" name="ID_Fornitore" required>
                                        <?php while ($fornitore = $fornitori->fetch_assoc()) { ?>
                                            <option value="<?php echo $fornitore['ID_Fornitore']; ?>"><?php echo $fornitore['Nome']; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="ID_Articolo">Articolo</label>
                                    <select class="form-control" id="ID_Articolo" name="ID_Articolo" required>
                                        <?php while ($articolo = $articoli->fetch_assoc()) { ?>
                                            <option value="<?php echo $articolo['ID_Prodotto']; ?>"><?php echo $articolo['Nome']; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="Data_Acquisto">Data Acquisto</label>
                                    <input type="date" class="form-control" id="Data_Acquisto" name="Data_Acquisto" required>
                                </div>
                                <div class="form-group">
                                    <label for="Quantità">Quantità</label>
                                    <input type="number" class="form-control" id="Quantità" name="Quantità" required>
                                </div>
                                <div class="form-group">
                                    <label for="Costo">Costo</label>
                                    <input type="text" class="form-control" id="Costo" name="Costo" required>
                                </div>
                                <div class="form-group">
                                    <label for="Stato">Stato</label>
                                    <select class="form-control" id="Stato" name="Stato" required>
                                        <option value="in consegna">In consegna</option>
                                        <option value="consegnato">Consegnato</option>
                                    </select>
                                </div>
                                <button type="submit" name="placeOrder" class="btn btn-primary">Effettua Ordine</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php require_once('partials/_footer.php'); ?>
        <?php require_once('partials/_scripts.php'); ?>
</body>

</html>
