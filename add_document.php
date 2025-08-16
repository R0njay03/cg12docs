<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $document_nr = $_POST['document_nr'];
    $scanned_at = $_POST['scanned_at'];
    $doc_type = $_POST['doc_type'];
    $subject = $_POST['subject'];
    $encoder = $_POST['encoder'];
    $receiving_office = $_POST['receiving_office'];
    $time_in = $_POST['time_in'];
    $time_out = $_POST['time_out'];
    $remarks = $_POST['remarks'];
    $forwarded_to = $_POST['forwarded_to'];
    $status = $_POST['status'];

    $stmt = $conn->prepare("INSERT INTO documents (document_nr, scanned_at, doc_type, subject, encoder, receiving_office, time_in, time_out, remarks, forwarded_to, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssssssss", $document_nr, $scanned_at, $doc_type, $subject, $encoder, $receiving_office, $time_in, $time_out, $remarks, $forwarded_to, $status);
    $stmt->execute();
    $stmt->close();

    header("Location: dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Add Document - CG12 Monitoring</title>
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
<style>
body { font-family: 'Roboto', sans-serif; background-color: #f4f6f8; margin:0; padding:0;}
.container { max-width: 700px; margin: 50px auto; background: #fff; padding: 30px 40px; border-radius: 12px; box-shadow: 0 10px 25px rgba(0,0,0,0.1);}
h2 { text-align: center; margin-bottom: 25px; color: #2c3e50;}
form label { display:block; margin-bottom:5px; font-weight:500; color:#34495e;}
form input, form select, form textarea { width:100%; padding:10px 15px; margin-bottom:20px; border:1px solid #ccc; border-radius:8px; font-size:15px; transition: all 0.3s ease;}
form input:focus, form select:focus, form textarea:focus { border-color:#2980b9; box-shadow:0 0 5px rgba(41,128,185,0.3); outline:none;}
form textarea { resize: vertical; min-height: 80px;}
.btn { display:inline-block; padding:12px 25px; background:#2980b9; color:#fff; border:none; border-radius:8px; font-size:16px; cursor:pointer; transition: all 0.3s ease; text-align:center;}
.btn:hover { background:#3498db; transform: translateY(-2px);}
</style>
</head>
<body>

<div class="container">
<h2>Add New Document</h2>
<form method="POST">
    <label>Document Number</label>
    <input type="text" name="document_nr" required>

    <label>Scanned At</label>
    <input type="datetime-local" name="scanned_at" required>

    <label>Document Type</label>
    <input type="text" name="doc_type" required>

    <label>Subject</label>
    <textarea name="subject" required></textarea>

    <label>Encoder</label>
    <input type="text" name="encoder" required>

    <label>Receiving By</label>
    <input type="text" name="receiving_office" required>

    <label>Time In</label>
    <input type="time" name="time_in">

    <label>Time Out</label>
    <input type="time" name="time_out">

    <label>Remarks</label>
    <textarea name="remarks"></textarea>

    <label>Forwarded To</label>
    <input type="text" name="forwarded_to">

    <label>Status</label>
    <select name="status" required>
        <option value="pending">Pending</option>
        <option value="in-progress">In Progress</option>
        <option value="completed">Completed</option>
    </select>

    <button type="submit" class="btn">Add Document</button>
    <button type="submit" class="btn">
    <a href="index.php" class="btn-back">← Back to Dashboard</a></button>

</form>
</div>

</body>
</html>
