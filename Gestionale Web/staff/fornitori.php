<?php
session_start();
include('config/config.php');
include('config/checklogin.php');
check_login();

if (isset($_GET['delete'])) {
    $id = $_GET['delete'];

    $adn = "DELETE FROM acquisti_fornitori WHERE ID_Fornitore = ?";
    $stmt = $mysqli->prepare($adn);
    if ($stmt === false) {
        die("Errore nella preparazione: " . $mysqli->error);
    }
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $stmt->close();

    $adn = "DELETE FROM anagrafica_fornitori WHERE ID_Fornitore = ?";
    $stmt = $mysqli->prepare($adn);
    if ($stmt === false) {
        die("Errore nella preparazione: " . $mysqli->error);
    }
    $stmt->bind_param('i', $id);
    $executeSuccess = $stmt->execute();
    $stmt->close();

    if ($executeSuccess) {
        $success = "Deleted" && header("refresh:1; url=fornitori.php");
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
                            <a href="add_fornitore.php" class="btn btn-outline-success">
                                <i class="fas fa-user-plus"></i> Aggiungi fornitore
                            </a>
                            <a href="make_order_fornitori.php" class="btn btn-outline-success">
                                <i class="fas fa-user-plus"></i> Crea ordinazione
                            </a>
                        </div>
                        <div class="table-responsive">
                            <table class="table align-items-center table-flush">
                                <thead class="thead-light">
                                    <tr>
                                        <th scope="col">Name</th>
                                        <th scope="col">Phone Number</th>
                                        <th scope="col">Email</th>
                                        <th scope="col">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $ret = "SELECT * FROM anagrafica_fornitori ORDER BY ID_Fornitore DESC";
                                    $stmt = $mysqli->prepare($ret);
                                    $stmt->execute();
                                    $res = $stmt->get_result();
                                    while ($fornitore = $res->fetch_object()) {
                                    ?>
                                    <tr>
                                        <td><?php echo $fornitore->Nome; ?></td>
                                        <td><?php echo $fornitore->Telefono; ?></td>
                                        <td><?php echo $fornitore->Email; ?></td>
                                        <td>
                                            <a href="fornitori.php?delete=<?php echo htmlspecialchars($fornitore->ID_Fornitore); ?>">
                                                <button class="btn btn-sm btn-danger">
                                                    <i class="fas fa-trash"></i> Delete
                                                </button>
                                            </a>
                                            <a href="update_fornitore.php?update=<?php echo htmlspecialchars($fornitore->ID_Fornitore); ?>">
                                                <button class="btn btn-sm btn-primary">
                                                    <i class="fas fa-user-edit"></i> Update
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
