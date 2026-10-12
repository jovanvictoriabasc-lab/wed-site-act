<?php
include __DIR__ . '/db.php';

if (isset($_POST['login'])) {
    $u = $_POST['username'];
    $p = $_POST['password'];
    $q = $conn->query("SELECT * FROM users WHERE username='$u' AND password='$p'");
    if ($q->num_rows > 0) {
        $_SESSION['user'] = $q->fetch_assoc();
        header("Location: home.php");
        exit();
    } else {
        $error = "Wrong username or password!";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - FIX-IT</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #3769b5, #003080);
            min-height: 100vh;
        }
    </style>
</head>
<body class="d-flex align-items-center justify-content-center">

<div class="container">
    <div class="row justify-content-center">
        <div class="col-12 col-sm-8 col-md-5 col-lg-4">

            <div class="card shadow-lg border-0 rounded-4 p-4">

                <div class="text-center mb-4">
                    <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center" style="width:60px; height:60px;">
                        <i class="bi bi-tools fs-3"></i>
                    </div>
                    <h4 class="mt-3 fw-bold text-primary mb-1">FIX-IT System</h4>
                    <small class="text-muted">Classroom Complaint & Maintenance</small>
                </div>

                <?php if (isset($_GET['logout'])): ?>
                    <div class="alert alert-success py-2 small text-center rounded-3">
                        Logged out successfully!
                    </div>
                <?php endif; ?>

                <?php if (isset($error)): ?>
                    <div class="alert alert-danger py-2 small text-center rounded-3">
                        <?php echo $error; ?>
                    </div>
                <?php endif; ?>

                <form method="POST">
                    <div class="form-floating mb-3">
                        <input type="text" name="username" class="form-control rounded-3" placeholder="Username" required>
                        <label>Username</label>
                    </div>

                    <div class="form-floating mb-3">
                        <input type="password" name="password" class="form-control rounded-3" placeholder="Password" required>
                        <label>Password</label>
                    </div>

                    <button name="login" class="btn btn-primary w-100 py-2 fw-bold rounded-pill">
                        LOGIN
                    </button>
                </form>
            </div>

            <p class="text-center text-white-50 small mt-3">© 2026 FIX-IT Classroom System</p>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>