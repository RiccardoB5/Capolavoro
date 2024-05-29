<div class="navbar">
        <div class="logo">
            <a href="index.php"><img src="logo.png" alt="Logo"></a>
        </div>
        <div class="nav-links">
            <a href="index.php"><i class="fas fa-home"></i>Home</a>
            <a href="catalogo.php"><i class="fas fa-th-list"></i>Catalogo</a>
            <a href="acquista.php"><i class="fas fa-shopping-bag"></i>Acquista</a>
        </div>
        <div class="actions">
            <a href="carrello.php"><i class="fas fa-shopping-cart cart-icon"></i></a>
            <a href="login.php"><i class="fas fa-sign-in-alt login-icon"></i></a>
        </div>
    </div>

    <style>
    .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 20px;
            background-color: #333;
            color: #fff;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        .navbar a {
            color: #fff;
            text-decoration: none;
            padding: 8px 16px;
            border-radius: 4px;
            transition: background-color 0.3s, transform 0.3s;
            display: flex;
            align-items: center;
        }
        .navbar a:hover {
            background-color: #ff6600;
            transform: scale(1.1);
        }
        .navbar .nav-links, .navbar .actions {
            display: flex;
            align-items: center;
        }
        .navbar .logo img {
            height: 40px;
        }
        .navbar i {
            margin-right: 8px;
        }
 </style>