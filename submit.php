<?php
include __DIR__ . '/db.php';
if(!isset($_SESSION['user'])){ header("Location: login.php"); exit(); }

$name = $_POST['name'];
$room = $_POST['room'];
$category = $_POST['category'];
$description = $_POST['description'];
$urgency = $_POST['urgency'];

$conn->query("INSERT INTO complaints (name, room, category, description, urgency) VALUES ('$name', '$room', '$category', '$description', '$urgency')");
header("Location: dashboard.php");
exit();
?>