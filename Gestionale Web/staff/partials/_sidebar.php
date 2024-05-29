<nav class="navbar navbar-vertical fixed-left navbar-expand-md navbar-light bg-white" id="sidenav-main">
    <div class="container-fluid">
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#sidenav-collapse-main"
            aria-controls="sidenav-main" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <a class="navbar-brand pt-0" href="dashboard.php">
            <img src="assets/img/brand/New_Project.png" class="navbar-brand-img" alt="...">
        </a>
        <ul class="nav align-items-center d-md-none">
            <li class="nav-item dropdown">
                <a class="nav-link nav-link-icon" href="#" role="button" data-toggle="dropdown" aria-haspopup="true"
                    aria-expanded="false">
                    <i class="ni ni-bell-55"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-arrow dropdown-menu-right"
                    aria-labelledby="navbar-default_dropdown_1">
                </div>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link" href="#" role="button" data-toggle="dropdown" aria-haspopup="true"
                    aria-expanded="false">
                    <div class="media align-items-center">
                        <span class="avatar avatar-sm rounded-circle">
                            <img alt="Image placeholder" src="assets/img/theme/team-1-800x800.jpg">
                        </span>
                    </div>
                </a>
                <div class="dropdown-menu dropdown-menu-arrow dropdown-menu-right">
                    <div class="dropdown-header noti-title">
                        <h6 class="text-overflow m-0">Welcome!</h6>
                    </div>
                    <a href="change_profile.php" class="dropdown-item">
                        <i class="ni ni-single-02"></i>
                        <span>My profile</span>
                    </a>
                    <div class="dropdown-divider"></div>
                    <a href="logout.php" class="dropdown-item">
                        <i class="ni ni-user-run"></i>
                        <span>Logout</span>
                    </a>
                </div>
            </li>
        </ul>
        <div class="collapse navbar-collapse" id="sidenav-collapse-main">
            <form class="mt-4 mb-3 d-md-none">
                <div class="input-group input-group-rounded input-group-merge">
                    <input type="search" class="form-control form-control-rounded form-control-prepended"
                        placeholder="Search" aria-label="Search">
                    <div class="input-group-prepend">
                        <div class="input-group-text">
                            <span class="fa fa-search"></span>
                        </div>
                    </div>
                </div>
            </form>
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="dashboard.php">
                        <i class="ni ni-tv-2 text-primary"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="production.php">
                        <i class="fas fa-file-invoice-dollar text-primary"></i> Produzione
                    </a>
                </li>
            </ul>
            <hr class="my-3">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="fornitori.php">
                        <i class="fas fa-users text-primary"></i> Fornitori
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="order_fornitori.php">
                        <i class="fas fa-file-invoice-dollar text-primary"></i> Ordini Fornitori
                    </a>
                </li>
            </ul>
            <hr class="my-3">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="customes.php">
                        <i class="fas fa-users text-primary"></i> Clienti
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="order_customes.php">
                        <i class="fas fa-users text-primary"></i> Acquisti Clienti
                    </a>
                </li>
            </ul>
            <hr class="my-3">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="magazzino_componenti.php">
                        <i class="fas fa-cogs text-primary"></i> Magazzino Componenti
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="magazzino_prod_finiti.php">
                        <i class="fas fa-boxes text-primary"></i> Magazzino Prodotti Finiti
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="comp_marmitte.php">
                        <i class="fas fa-car text-primary"></i> Composizione Marmitte
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="prodotti.php">
                        <i class="fas fa-box text-primary"></i> Prodotti
                    </a>
                </li>

            </ul>
            <hr class="my-3">
            <ul class="navbar-nav mb-md-3">
                <li class="nav-item">
                    <a class="nav-link" href="logout.php">
                        <i class="fas fa-sign-out-alt text-danger"></i> Log Out
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>