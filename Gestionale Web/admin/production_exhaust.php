<?php
session_start();
include('config/config.php');
include('config/checklogin.php');
check_login();

$conn = new mysqli("localhost", "root", "", "fabbricamarmitte");

if ($conn->connect_error) {
    die("Connessione fallita: " . $conn->connect_error);
}

function aggiornaMagazzino($conn, $idMarmitta, $quantita)
{
    $conn->begin_transaction();
    try {
        $sqlComponenti = "SELECT ID_Componente1, Quantità_Componente1, ID_Componente2, Quantità_Componente2, ID_Componente3, Quantità_Componente3 
                          FROM composizione_marmitte 
                          WHERE ID_Marmitta = ?";

        $stmtComponenti = $conn->prepare($sqlComponenti);
        $stmtComponenti->bind_param("i", $idMarmitta);
        $stmtComponenti->execute();
        $resultComponenti = $stmtComponenti->get_result();

        if ($row = $resultComponenti->fetch_assoc()) {
            for ($i = 1; $i <= 3; $i++) {
                $idComponente = $row["ID_Componente$i"];
                $quantitaComponente = $row["Quantità_Componente$i"] * $quantita;

                $sqlMagazzino = "SELECT Quantità AS Quantità_Disponibile FROM magazzino_componenti WHERE ID_Componente = ?";
                $stmtMagazzino = $conn->prepare($sqlMagazzino);
                $stmtMagazzino->bind_param("i", $idComponente);
                $stmtMagazzino->execute();
                $resultMagazzino = $stmtMagazzino->get_result()->fetch_assoc();

                if ($quantitaComponente > $resultMagazzino["Quantità_Disponibile"]) {
                    throw new Exception("Materiale non disponibile per il componente: " . $idComponente);
                }

                $stmtUpdate = $conn->prepare("UPDATE magazzino_componenti SET Quantità = Quantità - ? WHERE ID_Componente = ?");
                $stmtUpdate->bind_param("ii", $quantitaComponente, $idComponente);
                $stmtUpdate->execute();
            }
        }

        $stmtInsert = $conn->prepare("INSERT INTO magazzino_prodotti_finiti (ID_Prodotto_Finito, Quantita) VALUES (?, ?) ON DUPLICATE KEY UPDATE Quantita = Quantita + ?");
        $stmtInsert->bind_param("iii", $idMarmitta, $quantita, $quantita);
        $stmtInsert->execute();

        $conn->commit();
        global $success;
        $success = "Produzione aggiunta" && header("refresh:1; url=production.php");
    } catch (Exception $e) {
        $conn->rollback();
        global $err;
        $err = "Errore: " . $e->getMessage();
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["idMarmitta"])) {
    aggiornaMagazzino($conn, $_POST["idMarmitta"], $_POST["quantita"]);
}

require_once('partials/_head.php');
?>

<body>
    <?php include('partials/_sidebar.php'); ?>
    <div class="main-content">
        <?php require_once('partials/_topnav.php'); ?>
        <div style="background-image: url(assets/img/theme/restro00.jpg); background-size: cover;" class="header pb-8 pt-5 pt-md-8">
            <span class="mask bg-gradient-dark opacity-8"></span>
            <div class="container-fluid">
                <div class="header-body">
                </div>
            </div>
        </div>
        <div class="container-fluid mt--6">
            <div class="row">
                <div class="col">
                    <div class="card">
                        <div class="card-header border-0">
                            <h3 class="mb-0">Gestione Produzione Marmitte</h3>
                        </div>
                        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                            <div class="card-body">
                                <select name="idMarmitta" class="form-control">
                                    <?php
                                    $result = $conn->query("SELECT ID_Prodotto, Nome FROM anagrafica_prodotti WHERE Tipo = 'Marmitta'");
                                    if ($result->num_rows > 0) {
                                        while ($row = $result->fetch_assoc()) {
                                            echo "<option value='" . $row["ID_Prodotto"] . "'>" . $row["Nome"] . "</option>";
                                        }
                                    } else {
                                        echo "<option>Nessuna marmitta disponibile</option>";
                                    }
                                    ?>
                                </select>
                                <input type="number" name="quantita" class="form-control mt-3" placeholder="Quantità" required min="1">
                            </div>
                            <div class="card-footer py-4">
                                <button type="submit" class="btn btn-primary">Produci Marmitta</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
