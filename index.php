<?php
include __DIR__ . '/db.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name        = $conn->real_escape_string($_POST['name']);
    $room        = $conn->real_escape_string($_POST['room']);
    $category    = $conn->real_escape_string($_POST['category']);
    $description = $conn->real_escape_string($_POST['description']);
    $urgency     = $conn->real_escape_string($_POST['urgency']);

    $sql = "INSERT INTO complaints (name, room, category, description, urgency, status, created_at) 
            VALUES ('$name', '$room', '$category', '$description', '$urgency', 'Pending', NOW())";
    
    $conn->query($sql);
    header("Location: dashboard.php?success=1");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Submit Complaint - FIX-IT</title>
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
        <button class="btn btn-light btn-sm mobile-btn" data-bs-toggle="offcanvas" data-bs-target="#mobileNav">
            <i class="bi bi-list"></i>
        </button>
        <b class="navbar-brand m-0"><i class="bi bi-tools"></i> FIX-IT</b>
        <a href="dashboard.php" class="btn btn-light btn-sm rounded-pill">
            <i class="bi bi-table"></i> View
        </a>
    </div>
</nav>

<div class="offcanvas offcanvas-start" id="mobileNav">
    <div class="offcanvas-header bg-primary text-white">
        <b><i class="bi bi-tools"></i> FIX-IT</b>
        <button class="btn-close btn-close-white" data-bs-dismiss="offcanvas"></button>
    </div>
    <div class="list-group list-group-flush">
        <a href="home.php" class="list-group-item">Home</a>
        <a href="index.php" class="list-group-item active">Submit Complaint</a>
        <a href="schedule.php" class="list-group-item">Room Schedule</a>
        <a href="dashboard.php" class="list-group-item">All Complaints</a>
    </div>
</div>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-2 bg-white shadow-sm p-0 sidebar-desktop" style="min-height: 90vh;">
            <div class="list-group list-group-flush">
                <a href="home.php" class="list-group-item">Home</a>
                <a href="index.php" class="list-group-item active">Submit Complaint</a>
                <a href="schedule.php" class="list-group-item">Room Schedule</a>
                <a href="dashboard.php" class="list-group-item">All Complaints</a>
            </div>
        </div>

        <div class="col-md-10 p-4">
            <div class="row justify-content-center">
                <div class="col-12 col-md-7">
                    <div class="card border-0 shadow rounded-4 p-4">
                        <h5 class="fw-bold text-primary text-center">
                            <i class="bi bi-pencil-square"></i> Submit Complaint
                        </h5>
                        <p class="text-muted small text-center mb-4">
                            Type your name - complaint 
                        </p>

                        <form method="POST">
                            <div class="mb-3">
                                <label class="form-label fw-bold small">Full Name *</label>
                                <input type="text" name="name" class="form-control rounded-3" placeholder="Enter full name" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold small">Room / Lab</label>
                                <select name="room" class="form-select rounded-3" required>
                                    <option value="">-- Select Room --</option>
                                    <option>ICS Lab 1</option>
                                    <option>ICS Lab 2</option>
                                    <option>Room 101</option>
                                    <option>Room 102</option>
                                </select>
                            </div>

                            <div class="row g-2 mb-3">
                                <div class="col-6">
                                    <label class="form-label fw-bold small">Category</label>
                                    <select name="category" class="form-select rounded-3" required>
                                        <option>Aircon</option>
                                        <option>Fan</option>
                                        <option>Chair</option>
                                        <option>Projector</option>
                                        <option>Light</option>
                                        <option>computer</option>
                                        <option>Others</option>
                                    </select>
                                </div>
                                <div class="col-6">
                                    <label class="form-label fw-bold small">Urgency</label>
                                    <select name="urgency" class="form-select rounded-3" required>
                                        <option>Low</option>
                                        <option>Medium</option>
                                        <option>High</option>
                                        <option>Critical</option>
                                    </select>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-bold small">Description</label>
                                <textarea name="description" class="form-control rounded-3" rows="4" placeholder="Describe the issue..." required></textarea>
                            </div>

                            <button type="submit" class="btn btn-primary w-100 rounded-pill py-2 fw-bold">
                                <i class="bi bi-send"></i> Submit Report
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>