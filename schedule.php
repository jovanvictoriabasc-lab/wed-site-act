<?php
include __DIR__ . '/db.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$user = $_SESSION['user'];
$isAdmin = ($user['role'] === 'admin');

if (isset($_POST['add_schedule'])) {
    if (!$isAdmin) {
        die("Access Denied");
    }
    $room = $conn->real_escape_string($_POST['room_name']);
    $prof = $conn->real_escape_string($_POST['professor_name']);
    $section = $conn->real_escape_string($_POST['section']);
    $subject = $conn->real_escape_string($_POST['subject']);
    $day = $conn->real_escape_string($_POST['day_of_week']);
    $start = $_POST['time_start'];
    $end = $_POST['time_end'];

    $conn->query("INSERT INTO rooms_schedule (room_name, professor_name, section, subject, day_of_week, time_start, time_end) 
                  VALUES ('$room','$prof','$section','$subject','$day','$start','$end')");
    header("Location: schedule.php?added=1");
    exit();
}

if (isset($_GET['delete'])) {
    if (!$isAdmin) {
        die("Access Denied");
    }
    $conn->query("DELETE FROM rooms_schedule WHERE id=" . intval($_GET['delete']));
    header("Location: schedule.php");
    exit();
}

$filter = isset($_GET['room']) ? $conn->real_escape_string($_GET['room']) : '';

if ($filter) {
    $result = $conn->query("SELECT * FROM rooms_schedule WHERE room_name='$filter' 
                             ORDER BY FIELD(day_of_week,'Monday','Tuesday','Wednesday','Thursday','Friday','Saturday'), time_start");
} else {
    $result = $conn->query("SELECT * FROM rooms_schedule ORDER BY room_name, time_start");
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Schedule - FIX-IT</title>
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
        <b class="navbar-brand m-0"><i class="bi bi-calendar-week"></i> FIX-IT</b>
        <span class="badge bg-white text-primary"><?php echo strtoupper($user['role']); ?></span>
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
        <a href="schedule.php" class="list-group-item active">Room Schedule</a>
        <a href="dashboard.php" class="list-group-item">All Complaints</a>
    </div>
</div>

<div class="container-fluid">
    <div class="row">

        <div class="col-md-2 bg-white shadow-sm p-0 sidebar-desktop" style="min-height:90vh">
            <div class="list-group list-group-flush">
                <a href="home.php" class="list-group-item">Home</a>
                <a href="index.php" class="list-group-item">Submit Complaint</a>
                <a href="schedule.php" class="list-group-item active">Room Schedule</a>
                <a href="dashboard.php" class="list-group-item">All Complaints</a>
            </div>
        </div>

        <div class="col-md-10 p-3 p-md-4">

            <?php if (isset($_GET['added'])): ?>
                <div class="alert alert-success py-2 small rounded-pill text-center">
                    <i class="bi bi-check-circle"></i> Schedule added successfully!
                </div>
            <?php endif; ?>

            <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                <h5 class="fw-bold text-primary mb-0">
                    <?php echo $isAdmin ? 'Manage Schedules' : 'Room Schedules'; ?>
                </h5>
                <?php if ($isAdmin): ?>
                    <button class="btn btn-primary rounded-pill btn-sm px-3" data-bs-toggle="modal" data-bs-target="#addModal">
                        <i class="bi bi-plus"></i> Add Schedule
                    </button>
                <?php else: ?>
                    <span class="text-muted small"><i class="bi bi-eye"></i> View Only</span>
                <?php endif; ?>
            </div>

            <div class="card border-0 shadow rounded-4 p-3">
                <form method="GET" class="row g-2 mb-3">
                    <div class="col-8 col-md-4">
                        <select name="room" class="form-select form-select-sm" onchange="this.form.submit()">
                            <option value="">All Rooms</option>
                            <option <?php if($filter=='ICS Lab 1') echo 'selected'; ?>>ICS Lab 1</option>
                            <option <?php if($filter=='ICS Lab 2') echo 'selected'; ?>>ICS Lab 2</option>
                            <option <?php if($filter=='Room 101') echo 'selected'; ?>>Room 101</option>
                            <option <?php if($filter=='Room 102') echo 'selected'; ?>>Room 102</option>
                            <option <?php if($filter=='Room 103') echo 'selected'; ?>>Room 103</option>
                        </select>
                    </div>
                    <div class="col-4 col-md-2">
                        <a href="schedule.php" class="btn btn-sm btn-light w-100">Show All</a>
                    </div>
                </form>

                <div class="d-md-none">
                    <?php while ($r = $result->fetch_assoc()): ?>
                        <div class="card border rounded-3 p-3 mb-2">
                            <div class="d-flex justify-content-between">
                                <b class="text-primary"><?php echo $r['room_name']; ?></b>
                                <small><?php echo $r['day_of_week']; ?></small>
                            </div>
                            <div class="small mt-1">
                                <div><b>Prof:</b> <?php echo $r['professor_name']; ?></div>
                                <div><b>Section:</b> <?php echo $r['section']; ?> | <b>Sub:</b> <?php echo $r['subject']; ?></div>
                                <div class="text-muted">
                                    <?php echo date("h:i A", strtotime($r['time_start'])) . ' - ' . date("h:i A", strtotime($r['time_end'])); ?>
                                </div>
                            </div>
                            <?php if ($isAdmin): ?>
                                <a href="?delete=<?php echo $r['id']; ?>" onclick="return confirm('Delete?')" class="btn btn-outline-danger btn-sm rounded-pill mt-2 w-100">
                                    <i class="bi bi-trash"></i> Delete
                                </a>
                            <?php endif; ?>
                        </div>
                    <?php endwhile; $result->data_seek(0); ?>
                </div>

                <div class="d-none d-md-block table-responsive">
                    <table class="table table-hover">
                        <thead class="table-primary">
                            <tr>
                                <th>Room</th>
                                <th>Professor</th>
                                <th>Section</th>
                                <th>Subject</th>
                                <th>Day / Time</th>
                                <?php if ($isAdmin) echo '<th>Action</th>'; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($r = $result->fetch_assoc()): ?>
                                <tr>
                                    <td><span class="badge bg-light text-primary border"><?php echo $r['room_name']; ?></span></td>
                                    <td><?php echo $r['professor_name']; ?></td>
                                    <td><?php echo $r['section']; ?></td>
                                    <td><?php echo $r['subject']; ?></td>
                                    <td>
                                        <?php echo $r['day_of_week']; ?><br>
                                        <small><?php echo date("h:i A", strtotime($r['time_start'])) . ' - ' . date("h:i A", strtotime($r['time_end'])); ?></small>
                                    </td>
                                    <?php if ($isAdmin): ?>
                                        <td>
                                            <a href="?delete=<?php echo $r['id']; ?>" onclick="return confirm('Delete?')" class="btn btn-sm btn-outline-danger rounded-pill">
                                                <i class="bi bi-trash"></i>
                                            </a>
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

<?php if ($isAdmin): ?>
<div class="modal fade" id="addModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content rounded-4">
            <div class="modal-header bg-primary text-white">
                <h6 class="modal-title fw-bold">Add Schedule</h6>
                <button class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST">
                <div class="modal-body">
                    <div class="mb-2">
                        <label class="form-label">Room</label>
                        <select name="room_name" class="form-select" required>
                            <option>ICS Lab 1</option>
                            <option>ICS Lab 2</option>
                            <option>Room 101</option>
                            <option>Room 102</option>
                            <option>Room 103</option>
                        </select>
                    </div>
                    <div class="mb-2">
                        <label class="form-label">Professor Name</label>
                        <input name="professor_name" class="form-control" required>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <label class="form-label">Section</label>
                            <input name="section" class="form-control" required placeholder="BSIT 3E">
                        </div>
                        <div class="col-6">
                            <label class="form-label">Subject</label>
                            <input name="subject" class="form-control" required>
                        </div>
                    </div>
                    <div class="mt-2">
                        <label class="form-label">Day</label>
                        <select name="day_of_week" class="form-select" required>
                            <option>Monday</option>
                            <option>Tuesday</option>
                            <option>Wednesday</option>
                            <option>Thursday</option>
                            <option>Friday</option>
                            <option>Saturday</option>
                        </select>
                    </div>
                    <div class="row mt-2">
                        <div class="col-6">
                            <label class="form-label">Start</label>
                            <input type="time" name="time_start" class="form-control" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label">End</label>
                            <input type="time" name="time_end" class="form-control" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-light rounded-pill" data-bs-dismiss="modal">Cancel</button>
                    <button name="add_schedule" class="btn btn-primary rounded-pill">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php endif; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>