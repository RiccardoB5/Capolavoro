<?php
session_start();
include('config/config.php');
include('config/checklogin.php');
check_login();

if (isset($_GET['delete'])) {
    $id = $_GET['delete'];

    $adn = "DELETE FROM magazzino_componenti WHERE ID_Componente = ?";
    $stmt = $mysqli->prepare($adn);
    if ($stmt === false) {
        die("Errore nella preparazione: " . $mysqli->error);
    }
    $stmt->bind_param('i', $id);
    $stmt->execute();
    if ($mysqli->affected_rows > 0) {
        $success = "Componente eliminato con successo!";
        header("refresh:1; url=magazzino_componenti.php");
    } else {
        $err = "Errore: il componente non è stato eliminato. Riprova più tardi.";
    }
    $stmt->close();
}

require_once('partials/_head.php');
?>

<body>
    <?php require_once('partials/_sidebar.php'); ?>
    <div class="main-content">
        <?php require_once('partials/_topnav.php'); ?>
        <div style="background-image: url(assets/img/theme/restro00.jpg); background-size: cover;"
            class="header pb-8 pt-5 pt-md-8">
            <span class="mask bg-gradient-dark opacity-8"></span>
            <div class="container-fluid">
                <div class="header-body">
                </div>
            </div>
        </div>
        <div class="container-fluid mt--8">
            <div class="row">
                <div class="col">
                    <div class="card shadow">
                        <div class="card-header border-0">
                            <a href="add_magazzino_componenti.php" class="btn btn-outline-success">
                                <i class="fas fa-plus"></i> Aggiungi Componente
                            </a>
                        </div>
                        <div class="table-responsive">
                            <table class="table align-items-center table-flush">
                                <thead class="thead-light">
                                    <tr>
                                        <th scope="col">ID Componente</th>
                                        <th scope="col">Nome Componente</th>
                                        <th scope="col">Quantità</th>
                                        <th scope="col">Azioni</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $query = "
                                        SELECT
                                            mc.ID_Componente,
                                            ap.Nome,
                                            mc.Quantità
                                        FROM
                                            magazzino_componenti mc
                                        LEFT JOIN
                                            anagrafica_prodotti ap
                                        ON
                                            mc.ID_Componente = ap.ID_Prodotto
                                        WHERE
                                            ap.Tipo = 'Componente'
                                        ORDER BY
                                            mc.ID_Componente DESC
                                    ";
                                    $stmt = $mysqli->prepare($query);
                                    $stmt->execute();
                                    $res = $stmt->get_result();
                                    while ($componente = $res->fetch_object()) {
                                    ?>
                                    <tr>
                                        <td><?php echo $componente->ID_Componente; ?></td>
                                        <td><?php echo $componente->Nome; ?></td>
                                        <td><?php echo $componente->Quantità; ?></td>
                                        <td>
                                            <a href="magazzino_componenti.php?delete=<?php echo htmlspecialchars($componente->ID_Componente); ?>">
                                                <button class="btn btn-sm btn-danger">
                                                    <i class="fas fa-trash"></i> Elimina
                                                </button>
                                            </a>
                                            <a href="update_magazzino_componenti.php?update=<?php echo htmlspecialchars($componente->ID_Componente); ?>">
                                                <button class="btn btn-sm btn-primary">
                                                    <i class="fas fa-edit"></i> Modifica
                                                </button>
                                            </a>
                                        </td>
                                    </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php require_once('partials/_footer.php'); ?>
    </div>
    <?php require_once('partials/_scripts.php'); ?>
</body>
</html>
