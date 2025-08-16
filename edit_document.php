<?php
include 'db.php';
$id = $_GET['id'] ?? 0;

// Fetch current document data
$stmt = $conn->prepare("SELECT * FROM documents WHERE id=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$doc = $result->fetch_assoc();

// Update submission
if($_SERVER['REQUEST_METHOD'] == 'POST'){
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

    // Handle file upload
    $uploaded_file = $doc['uploaded_file'];
    if(isset($_FILES['uploaded_file']) && $_FILES['uploaded_file']['error'] == 0){
        $target_dir = "uploads/";
        if (!is_dir($target_dir)) { mkdir($target_dir, 0755, true); }
        $uploaded_file = time() . '_' . basename($_FILES['uploaded_file']['name']);
        move_uploaded_file($_FILES['uploaded_file']['tmp_name'], $target_dir . $uploaded_file);
    }

    $stmt = $conn->prepare("UPDATE documents SET document_nr=?, scanned_at=?, doc_type=?, subject=?, encoder=?, receiving_office=?, time_in=?, time_out=?, remarks=?, forwarded_to=?, status=?, uploaded_file=? WHERE id=?");
    $stmt->bind_param("ssssssssssssi", $document_nr, $scanned_at, $doc_type, $subject, $encoder, $receiving_office, $time_in, $time_out, $remarks, $forwarded_to, $status, $uploaded_file, $id);
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
<title>Edit Document - CG12 Monitoring</title>
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
.btn-back { display: inline-block; margin-bottom: 20px; padding: 10px 20px; background: #7f8c8d; color: #fff; text-decoration: none; border-radius: 8px; transition: 0.3s;}
.btn-back:hover { background: #95a5a6;}
</style>
</head>
<body>

<div class="container">
    <a href="dashboard.php" class="btn-back">← Back to Dashboard</a>
    <h2>Edit Document</h2>
    <form method="POST" enctype="multipart/form-data">
        <label>Document Number</label>
        <input type="text" name="document_nr" value="<?php echo htmlspecialchars($doc['document_nr']); ?>" required>

        <label>Scanned At</label>
        <input type="datetime-local" name="scanned_at" value="<?php echo date('Y-m-d\TH:i', strtotime($doc['scanned_at'])); ?>" required>

        <label>Document Type</label>
        <input type="text" name="doc_type" value="<?php echo htmlspecialchars($doc['doc_type']); ?>" required>

        <label>Subject</label>
        <textarea name="subject" required><?php echo htmlspecialchars($doc['subject']); ?></textarea>

        <label>Encoder</label>
        <input type="text" name="encoder" value="<?php echo htmlspecialchars($doc['encoder']); ?>" required>

        <label>Receiving Office</label>
        <input type="text" name="receiving_office" value="<?php echo htmlspecialchars($doc['receiving_office']); ?>" required>

        <label>Time In</label>
        <input type="time" name="time_in" value="<?php echo $doc['time_in']; ?>">

        <label>Time Out</label>
        <input type="time" name="time_out" value="<?php echo $doc['time_out']; ?>">

        <label>Remarks</label>
        <textarea name="remarks"><?php echo htmlspecialchars($doc['remarks']); ?></textarea>

        <label>Forwarded To</label>
        <input type="text" name="forwarded_to" value="<?php echo htmlspecialchars($doc['forwarded_to']); ?>">

        <label>Status</label>
        <select name="status" required>
            <option value="pending" <?php if($doc['status']=='pending') echo 'selected'; ?>>Pending</option>
            <option value="in-progress" <?php if($doc['status']=='in-progress') echo 'selected'; ?>>In Progress</option>
            <option value="completed" <?php if($doc['status']=='completed') echo 'selected'; ?>>Completed</option>
        </select>

        <label>Upload File / Picture</label>
        <?php if($doc['uploaded_file']): ?>
            <p>Current: <a href="uploads/<?php echo $doc['uploaded_file']; ?>" target="_blank">View</a></p>
        <?php endif; ?>
        <input type="file" name="uploaded_file" accept=".jpg,.png,.pdf,.doc,.docx">

        <button type="submit" class="btn">Update Document</button>
    </form>
</div>

</body>
</html>
