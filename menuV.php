<?php
session_start();
if (!isset($_SESSION['id_usuario']) || $_SESSION['tipo_usuario'] != 1) {
    // Si no está logueado o no es un administrador, redirigir al login
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administrador - Bling Compras</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="style.css">
    <link rel="icon" href="imgs/logo.png">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="menuV.php">
                <img src="imgs/logo.png" alt="Logo" width="30" height="30" class="d-inline-block align-top">
                Bling Compra
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <button id="darkModeToggle" class="btn btn-outline-light toggle-btn">
                            <i class="fas fa-moon"></i>
                        </button>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php"><i class="fas fa-sign-out-alt"></i> Cerrar Sesión</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container-fluid">
        <div class="row">
            <nav id="sidebar" class="col-md-3 col-lg-2 d-md-block sidebar">
                <div class="position-sticky">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link active" href="./Usuario/validarusuario.php">
                                <i class="fas fa-users"></i> Usuarios
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="./GestionVentas/gestionVentasLista.php">
                                <i class="fas fa-chart-line"></i> Ventas
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="./Inventario/listaInventario.php">
                                <i class="fas fa-box"></i> Inventario
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="./Pedido/validarpedido.php">
                                <i class="fas fa-clipboard-list"></i> Pedidos
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="./Pagos/pago.php">
                                <i class="fas fa-credit-card"></i> Pagos
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>

            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">Panel de Administrador</h1>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-4">
                        <div class="card h-100 shadow-sm">
                            <div class="card-header">
                                <i class="fas fa-users"></i> Gestión de Usuarios
                            </div>
                            <div class="card-body">
                                <p class="card-text">Administre los usuarios registrados en la plataforma.</p>
                                <a href="./Usuario/validarusuario.php" class="btn btn-primary">Ir a Usuarios</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-4">
                        <div class="card h-100 shadow-sm">
                            <div class="card-header">
                                <i class="fas fa-chart-line"></i> Gestión de Ventas
                            </div>
                            <div class="card-body">
                                <p class="card-text">Revise y administre las ventas realizadas.</p>
                                <a href="./GestionVentas/gestionVentasLista.php" class="btn btn-primary">Ir a Ventas</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-4">
                        <div class="card h-100 shadow-sm">
                            <div class="card-header">
                                <i class="fas fa-box"></i> Gestión de Inventario
                            </div>
                            <div class="card-body">
                                <p class="card-text">Administre el inventario de productos.</p>
                                <a href="./Inventario/listaInventario.php" class="btn btn-primary">Ir a Inventario</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-4">
                        <div class="card h-100 shadow-sm">
                            <div class="card-header">
                                <i class="fas fa-clipboard-list"></i> Gestión de Pedidos
                            </div>
                            <div class="card-body">
                                <p class="card-text">Revise y administre los pedidos realizados por los clientes.</p>
                                <a href="./Pedido/validarpedido.php" class="btn btn-primary">Ir a Pedidos</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-4">
                        <div class="card h-100 shadow-sm">
                            <div class="card-header">
                                <i class="fas fa-credit-card"></i> Gestión de Pagos
                            </div>
                            <div class="card-body">
                                <p class="card-text">Revise y administre los pagos</p>
                                <a href="./Pagos/pago.php" class="btn btn-primary">Ir a Pagos</a>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="script.js"></script>
</body>
</html>