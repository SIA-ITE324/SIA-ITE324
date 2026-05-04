<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Flower Shop Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root {
            --flower-pink: #ff69b4;
            --flower-pink-light: #ffb6d9;
            --flower-pink-soft: #ffe4ec;
        }
        body {
            min-height: 100vh;
            background: linear-gradient(135deg, #fff 0%, var(--flower-pink-soft) 50%, #fff 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1rem;
        }
        .login-card {
            border: none;
            border-radius: 16px;
            box-shadow: 0 10px 40px rgba(255, 105, 180, 0.15);
            overflow: hidden;
        }
        .login-card .card-header {
            background: linear-gradient(135deg, var(--flower-pink) 0%, var(--flower-pink-light) 100%);
            color: #fff;
            font-weight: 600;
            padding: 1.25rem;
            border: none;
        }
        .login-card .card-body {
            padding: 2rem;
        }
        .btn-flower {
            background: linear-gradient(135deg, var(--flower-pink) 0%, var(--flower-pink-light) 100%);
            border: none;
            color: #fff;
            font-weight: 500;
            padding: 0.6rem 1.5rem;
        }
        .btn-flower:hover {
            background: linear-gradient(135deg, #e55a9e 0%, var(--flower-pink) 100%);
            color: #fff;
            box-shadow: 0 4px 15px rgba(255, 105, 180, 0.4);
        }
        .form-control:focus {
            border-color: var(--flower-pink-light);
            box-shadow: 0 0 0 0.2rem rgba(255, 105, 180, 0.25);
        }
        .brand-icon {
            font-size: 2.5rem;
            margin-bottom: 0.5rem;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-sm-10 col-md-6 col-lg-5">
                <div class="card login-card">
                    <div class="card-header text-center">
                        <div class="brand-icon"><i class="bi bi-flower2"></i></div>
                        <h5 class="mb-0">Flower Shop Management</h5>
                        <small>Sign in to your account</small>
                    </div>
                    <div class="card-body">
                        <?php if (session()->getFlashdata('error')): ?>
                            <div class="alert alert-danger py-2" role="alert">
                                <?= esc(session()->getFlashdata('error')) ?>
                            </div>
                        <?php endif; ?>

                        <form action="<?= base_url('auth/authenticate') ?>" method="post">
                            <?= csrf_field() ?>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" placeholder="you@example.com" required autofocus>
                            </div>
                            <div class="mb-4">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control" id="password" name="password" placeholder="••••••••" required>
                            </div>
                            <button type="submit" class="btn btn-flower w-100">Login</button>
                        </form>
                    </div>
                </div>
                <p class="text-center text-muted small mt-3">Flower Shop Management System</p>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
