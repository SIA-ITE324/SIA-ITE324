<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Flower Shop Management') ?></title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root {
            --flower-pink: #ff69b4;
            --flower-pink-light: #ffb6d9;
            --flower-pink-soft: #ffe4ec;
        }
        body {
            background-color: #fefefe;
            min-height: 100vh;
        }
        .navbar-flower {
            background: linear-gradient(135deg, var(--flower-pink) 0%, var(--flower-pink-light) 100%) !important;
            box-shadow: 0 2px 10px rgba(255, 105, 180, 0.2);
        }
        .navbar-flower .navbar-brand,
        .navbar-flower .nav-link {
            color: #fff !important;
            font-weight: 500;
        }
        .navbar-flower .nav-link:hover {
            color: rgba(255,255,255,0.9) !important;
            background-color: rgba(255,255,255,0.2);
            border-radius: 8px;
        }
        .sidebar {
            min-height: calc(100vh - 56px);
            background: #fff;
            box-shadow: 2px 0 12px rgba(0,0,0,0.06);
        }
        .sidebar .nav-link {
            color: #333;
            padding: 0.75rem 1.25rem;
            border-radius: 8px;
            margin: 2px 8px;
        }
        .sidebar .nav-link:hover {
            background-color: var(--flower-pink-soft);
            color: var(--flower-pink);
        }
        .sidebar .nav-link i {
            margin-right: 10px;
            width: 20px;
            text-align: center;
        }
        .main-content {
            background-color: #fafafa;
            min-height: calc(100vh - 56px);
            padding: 1.5rem;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-flower">
        <div class="container-fluid">
            <a class="navbar-brand fw-bold" href="<?= base_url('dashboard') ?>">
                <i class="bi bi-flower2 me-2"></i>Flower Shop Management
            </a>
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="<?= base_url('auth/logout') ?>">
                            <i class="bi bi-box-arrow-right me-1"></i>Logout
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container-fluid p-0">
        <div class="row g-0">
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 sidebar d-none d-md-block">
                <nav class="nav flex-column py-3">
                    <a class="nav-link" href="<?= base_url('dashboard') ?>">
                        <i class="bi bi-speedometer2"></i>Dashboard
                    </a>
                    <a class="nav-link" href="#">
                        <i class="bi bi-cart-check"></i>Orders
                    </a>
                    <a class="nav-link" href="#">
                        <i class="bi bi-box-seam"></i>Inventory
                    </a>
                    <a class="nav-link" href="#">
                        <i class="bi bi-flower2"></i>Flower Types
                    </a>
                    <a class="nav-link text-danger" href="<?= base_url('auth/logout') ?>">
                        <i class="bi bi-box-arrow-right"></i>Logout
                    </a>
                </nav>
            </div>
            <!-- Main content area -->
            <main class="col-md-9 col-lg-10 main-content">
