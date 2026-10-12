<?php
include __DIR__ . '/db.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Home - FIX-IT</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <style>
        @media (max-width: 768px) {
            .sidebar-desktop { display: none !important; }
        }
        @media (min-width: 769px) {
            .mobile-btn { display: none !important; }
        }
    </style>
</head>
<body class="bg-light">

<nav class="navbar navbar-dark bg-primary px-3 shadow sticky-top">
    <div class="d-flex w-100 justify-content-between align-items-center">
        <button class="btn btn-light btn-sm mobile-btn" data-bs-toggle="offcanvas" data-bs-target="#mNav">
            <i class="bi bi-list"></i>
        </button>
        <b class="navbar-brand m-0"><i class="bi bi-tools"></i> FIX-IT</b>
        <div>
            <small class="text-white me-2 d-none d-md-inline"><?php echo $_SESSION['user']['username']; ?></small>
            <button class="btn btn-light btn-sm rounded-pill" data-bs-toggle="modal" data-bs-target="#out">Logout</button>
        </div>
    </div>
</nav>

<div class="offcanvas offcanvas-start" id="mNav">
    <div class="offcanvas-header bg-primary text-white">
        <b>FIX-IT</b>
        <button class="btn-close btn-close-white" data-bs-dismiss="offcanvas"></button>
    </div>
    <div class="list-group list-group-flush">
        <a href="home.php" class="list-group-item active">Home</a>
        <a href="index.php" class="list-group-item">Submit</a>
        <a href="schedule.php" class="list-group-item">Schedule</a>
        <a href="dashboard.php" class="list-group-item">Complaints</a>
    </div>
</div>

<div class="container-fluid">
    <div class="row">

        <div class="col-md-2 bg-white shadow-sm p-0 sidebar-desktop" style="min-height:90vh">
            <div class="list-group list-group-flush">
                <a href="home.php" class="list-group-item active">Home</a>
                <a href="index.php" class="list-group-item">Submit Complaint</a>
                <a href="schedule.php" class="list-group-item">Room Schedule</a>
                <a href="dashboard.php" class="list-group-item">All Complaints</a>
            </div>
        </div>

        <div class="col-md-10 p-3">
            <div class="bg-primary text-white p-4 rounded-4 shadow mb-3 text-center">
                <h5>Welcome, <?php echo $_SESSION['user']['username']; ?>! 👋</h5>
                <p class="small mb-0">Report issues quickly and track real-time status.</p>
            </div>

            <div class="row g-2">
                <div class="col-6 col-md-4">
                    <div class="card border-0 shadow-sm rounded-4 p-3 text-center">
                        <h3 class="text-primary mb-0"><?php echo $conn->query("SELECT COUNT(*) c FROM complaints")->fetch_assoc()['c']; ?></h3>
                        <small>Total</small>
                    </div>
                </div>
                <div class="col-6 col-md-4">
                    <div class="card border-0 shadow-sm rounded-4 p-3 text-center">
                        <h3 class="text-warning mb-0"><?php echo $conn->query("SELECT COUNT(*) c FROM complaints WHERE status='Pending'")->fetch_assoc()['c']; ?></h3>
                        <small>Pending</small>
                    </div>
                </div>
                <div class="col-12 col-md-4">
                    <div class="card border-0 shadow-sm rounded-4 p-3 text-center">
                        <h3 class="text-success mb-0"><?php echo $conn->query("SELECT COUNT(*) c FROM complaints WHERE status='Fixed'")->fetch_assoc()['c']; ?></h3>
                        <small>Fixed</small>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm rounded-4 p-4 mt-3 text-center">
                <a href="index.php" class="btn btn-primary rounded-pill w-100">+ New Complaint</a>
            </div>
        </div>

    </div>
</div>

<div class="modal fade" id="out">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 p-3 text-center">
            <p>Are you sure?</p>
            <div class="d-flex gap-2 justify-content-center">
                <button class="btn btn-light rounded-pill" data-bs-dismiss="modal">Cancel</button>
                <a href="logout.php" class="btn btn-primary rounded-pill">Logout</a>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>