<?php
session_start();
include('config/config.php');
include('config/checklogin.php');
check_login();

if (isset($_GET['delete'])) {
    $id = $_GET['delete'];

    $adn = "DELETE FROM composizione_marmitte WHERE ID_Marmitta = ?";
    $stmt = $mysqli->prepare($adn);
    if ($stmt === false) {
        die("Errore nella preparazione: " . $mysqli->error);
    }
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $stmt->close();

    if ($stmt->affected_rows > 0) {
        $success = "Deleted" && header("refresh:1; url=composizione_marmitte.php");
    } else {
        $err = "Try Again Later";
    }
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
                            <a href="add_comp_marmitte.php" class="btn btn-outline-success">
                                <i class="fas fa-plus"></i> Aggiungi Composizione Marmitta
                            </a>
                        </div>
                        <div class="table-responsive">
                            <table class="table align-items-center table-flush">
                                <thead class="thead-light">
                                    <tr>
                                        <th scope="col">ID Marmitta</th>
                                        <th scope="col">Nome Componente 1</th>
                                        <th scope="col">Quantità Componente 1</th>
                                        <th scope="col">Nome Componente 2</th>
                                        <th scope="col">Quantità Componente 2</th>
                                        <th scope="col">Nome Componente 3</th>
                                        <th scope="col">Quantità Componente 3</th>
                                        <th scope="col">Azioni</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $query = "
                                        SELECT
                                            cm.ID_Marmitta,
                                            c1.Nome AS Nome_Componente1,
                                            cm.Quantità_Componente1,
                                            c2.Nome AS Nome_Componente2,
                                            cm.Quantità_Componente2,
                                            c3.Nome AS Nome_Componente3,
                                            cm.Quantità_Componente3
                                        FROM
                                            composizione_marmitte cm
                                        LEFT JOIN
                                            anagrafica_prodotti c1
                                        ON
                                            cm.ID_Componente1 = c1.ID_Prodotto
                                        LEFT JOIN
                                            anagrafica_prodotti c2
                                        ON
                                            cm.ID_Componente2 = c2.ID_Prodotto
                                        LEFT JOIN
                                            anagrafica_prodotti c3
                                        ON
                                            cm.ID_Componente3 = c3.ID_Prodotto
                                        ORDER BY
                                            cm.ID_Marmitta DESC
                                    ";
                                    $stmt = $mysqli->prepare($query);
                                    $stmt->execute();
                                    $res = $stmt->get_result();
                                    while ($marmitta = $res->fetch_object()) {
                                    ?>
                                    <tr>
                                        <td><?php echo $marmitta->ID_Marmitta; ?></td>
                                        <td><?php echo $marmitta->Nome_Componente1; ?></td>
                                        <td><?php echo $marmitta->Quantità_Componente1; ?></td>
                                        <td><?php echo $marmitta->Nome_Componente2; ?></td>
                                        <td><?php echo $marmitta->Quantità_Componente2; ?></td>
                                        <td><?php echo $marmitta->Nome_Componente3; ?></td>
                                        <td><?php echo $marmitta->Quantità_Componente3; ?></td>
                                        <td>
                                            <a href="composizione_marmitte.php?delete=<?php echo htmlspecialchars($marmitta->ID_Marmitta); ?>">
                                                <button class="btn btn-sm btn-danger">
                                                    <i class="fas fa-trash"></i> Elimina
                                                </button>
                                            </a>
                                            <a href="update_composizione_marmitta.php?update=<?php echo htmlspecialchars($marmitta->ID_Marmitta); ?>">
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
