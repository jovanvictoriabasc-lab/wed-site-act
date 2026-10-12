<?php
include __DIR__ . '/db.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$isAdmin = $_SESSION['user']['role'] == 'admin';

if (isset($_GET['fixed']) && $isAdmin) {
    $id = intval($_GET['fixed']);
    $conn->query("UPDATE complaints SET status='Fixed' WHERE id=$id");
    header("Location: dashboard.php");
    exit();
}

if (isset($_GET['delete']) && $isAdmin) {
    $id = intval($_GET['delete']);
    $conn->query("DELETE FROM complaints WHERE id=$id");
    header("Location: dashboard.php");
    exit();
}

$res = $conn->query("SELECT * FROM complaints ORDER BY id DESC");
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard - FIX-IT</title>
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

<nav class="navbar navbar-dark bg-primary px-3 shadow-sm sticky-top">
    <div class="d-flex w-100 justify-content-between align-items-center">
        <button class="btn btn-light btn-sm mobile-btn" data-bs-toggle="offcanvas" data-bs-target="#mNav">
            <i class="bi bi-list"></i>
        </button>
        <b class="navbar-brand m-0"><i class="bi bi-tools"></i>FIX-IT</b>
        <span class="badge bg-white text-primary"><?php echo strtoupper($_SESSION['user']['role']); ?></span>
    </div>
</nav>

<div class="offcanvas offcanvas-start" id="mNav">
    <div class="offcanvas-header bg-primary text-white">
        <b>FIX-IT</b>
        <button class="btn-close btn-close-white" data-bs-dismiss="offcanvas"></button>
    </div>
    <div class="list-group list-group-flush">
        <a href="home.php" class="list-group-item">Home</a>
        <a href="index.php" class="list-group-item">Submit Complaint</a>
        <a href="schedule.php" class="list-group-item">Room Schedule</a>
        <a href="dashboard.php" class="list-group-item active">All Complaints</a>
    </div>
</div>

<div class="container-fluid">
    <div class="row">

        <div class="col-md-2 bg-white shadow-sm p-0 sidebar-desktop" style="min-height:90vh">
            <div class="list-group list-group-flush">
                <a href="home.php" class="list-group-item">Home</a>
                <a href="index.php" class="list-group-item">Submit Complaint</a>
                <a href="schedule.php" class="list-group-item">Room Schedule</a>
                <a href="dashboard.php" class="list-group-item active">All Complaints</a>
            </div>
        </div>

        <div class="col-md-10 p-3 p-md-4">

            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="fw-bold text-primary mb-0">All Complaints</h5>
                <a href="index.php" class="btn btn-primary btn-sm rounded-pill">+ New</a>
            </div>

            <div class="d-md-none">
                <?php while ($r = $res->fetch_assoc()): ?>
                    <div class="card border-0 shadow-sm rounded-4 p-3 mb-2">
                        <div class="d-flex justify-content-between align-items-center">
                            <b class="text-primary"><?php echo $r['room']; ?></b>
                            <span class="badge <?php echo $r['status'] == 'Fixed' ? 'bg-success' : 'bg-warning text-dark'; ?>">
                                <?php echo $r['status']; ?>
                            </span>
                        </div>
                        <small class="text-muted"><?php echo $r['name']; ?> • <?php echo $r['category']; ?> • <?php echo $r['urgency']; ?></small>
                        <p class="small my-2"><?php echo $r['description']; ?></p>
                        <?php if ($isAdmin): ?>
                            <div class="d-flex gap-2">
                                <a href="?fixed=<?php echo $r['id']; ?>" class="btn btn-success btn-sm rounded-pill flex-fill">Fixed</a>
                                <a href="?delete=<?php echo $r['id']; ?>" class="btn btn-outline-danger btn-sm rounded-pill">Delete</a>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endwhile; ?>
            </div>

            <div class="d-none d-md-block card border-0 shadow rounded-4 p-3">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-primary">
                            <tr>
                                <th>Room</th>
                                <th>Student</th>
                                <th>Category</th>
                                <th>Description</th>
                                <th>Status</th>
                                <?php if ($isAdmin) echo "<th>Action</th>"; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $res->data_seek(0); while ($r = $res->fetch_assoc()): ?>
                                <tr>
                                    <td><span class="badge bg-light text-primary border"><?php echo $r['room']; ?></span></td>
                                    <td><?php echo $r['name']; ?></td>
                                    <td><?php echo $r['category']; ?></td>
                                    <td><?php echo $r['description']; ?></td>
                                    <td>
                                        <span class="badge <?php echo $r['status'] == 'Fixed' ? 'bg-success' : 'bg-warning text-dark'; ?>">
                                            <?php echo $r['status']; ?>
                                        </span>
                                    </td>
                                    <?php if ($isAdmin): ?>
                                        <td>
                                            <div class="d-flex gap-1">
                                                <a href="?fixed=<?php echo $r['id']; ?>" class="btn btn-success btn-sm rounded-pill">Fixed</a>
                                                <a href="?delete=<?php echo $r['id']; ?>" class="btn btn-outline-danger btn-sm rounded-pill">X</a>
                                            </div>
                                        </td>
                                    <?php endif; ?>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>