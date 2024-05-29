<?php
session_start();
include('config/config.php');
include('config/checklogin.php');
check_login();

if (isset($_GET['delete'])) {
    $id = $_GET['delete'];

    $adn = "DELETE FROM magazzino_prodotti_finiti WHERE ID_Prodotto_Finito = ?";
    $stmt = $mysqli->prepare($adn);
    if ($stmt === false) {
        die("Errore nella preparazione: " . $mysqli->error);
    }
    $stmt->bind_param('i', $id);
    $stmt->execute();

    if ($mysqli->affected_rows > 0) {
        $success = "Deleted" && header("refresh:1; url=magazzino_prod_finiti.php");
    } else {
        $err = "Try Again Later";
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
                            <a href="add_magazzino_prodotti_finiti.php" class="btn btn-outline-success">
                                <i class="fas fa-plus"></i> Aggiungi Prodotto Finito
                            </a>
                        </div>
                        <div class="table-responsive">
                            <table class="table align-items-center table-flush">
                                <thead class="thead-light">
                                    <tr>
                                        <th scope="col">ID Prodotto</th>
                                        <th scope="col">Nome</th>
                                        <th scope="col">Quantità</th>
                                        <th scope="col">Azioni</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $query = "
                                        SELECT
                                            mpf.ID_Prodotto_Finito,
                                            ap.Nome,
                                            mpf.Quantita
                                        FROM
                                            magazzino_prodotti_finiti mpf
                                        LEFT JOIN
                                            anagrafica_prodotti ap
                                        ON
                                            mpf.ID_Prodotto_Finito = ap.ID_Prodotto
                                        ORDER BY
                                            mpf.ID_Prodotto_Finito DESC
                                    ";
                                    $stmt = $mysqli->prepare($query);
                                    $stmt->execute();
                                    $res = $stmt->get_result();
                                    while ($prodotto = $res->fetch_object()) {
                                    ?>
                                    <tr>
                                        <td><?php echo $prodotto->ID_Prodotto_Finito; ?></td>
                                        <td><?php echo $prodotto->Nome; ?></td>
                                        <td><?php echo $prodotto->Quantita; ?></td>
                                        <td>
                                            <a href="magazzino_prod_finiti.php?delete=<?php echo htmlspecialchars($prodotto->ID_Prodotto_Finito); ?>">
                                                <button class="btn btn-sm btn-danger">
                                                    <i class="fas fa-trash"></i> Elimina
                                                </button>
                                            </a>
                                            <a href="update_magazzino_prod_finiti.php?update=<?php echo htmlspecialchars($prodotto->ID_Prodotto_Finito); ?>">
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
