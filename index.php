<?php
include 'db.php';

// Fetch documents
$sql = "SELECT * FROM documents ORDER BY created_at DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>CG-12 Document Monitoring</title>
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
<style>
body { font-family: 'Roboto', sans-serif; background: #f4f6f8; margin:0; padding:0;}
.container { max-width: 1000px; margin: 30px auto; background: #fff; padding: 20px 30px; border-radius: 12px; box-shadow: 0 10px 20px rgba(0,0,0,0.1);}
h2 { text-align: center; color: #2c3e50; margin-bottom: 20px;}
table { width: 100%; border-collapse: collapse; margin-bottom: 20px;}
table th, table td { border: 1px solid #ccc; padding: 10px; text-align: left;}
table th { background: #2980b9; color: #fff;}
table tr:nth-child(even) { background: #f9f9f9;}
a.btn { display: inline-block; padding: 10px 20px; background: #27ae60; color: #fff; text-decoration: none; border-radius: 8px; transition: 0.3s;}
a.btn:hover { background: #2ecc71; }
/* Body background with gradient + subtle pattern */
body {
    font-family: 'Roboto', sans-serif;
    margin: 0;
    padding: 0;
    background: linear-gradient(135deg, #2980b9, #6dd5fa);
    background-attachment: fixed;
    min-height: 100vh;
    display: flex;
    justify-content: center;
    align-items: flex-start;
}

/* Optional subtle pattern overlay */
body::before {
    content: "";
    position: fixed;
    top:0; left:0; right:0; bottom:0;
    background-image: repeating-linear-gradient(
        45deg,
        rgba(255,255,255,0.05) 0px,
        rgba(255,255,255,0.05) 1px,
        transparent 1px,
        transparent 10px
    );
    pointer-events: none;
}

/* Container card */
.container {
    background: #fff;
    padding: 30px 95px;
    margin: 50px auto;
    border-radius: 12px;
    box-shadow: 0 15px 30px rgba(0,0,0,0.15);
    max-width: 900px;
    width: 90%;
}

/* Headings */
h2 {
    text-align: center;
    color: #2c3e50;
    margin-bottom: 25px;
}

/* Buttons */
.btn, .btn-back, .btn-edit {
    transition: all 0.3s ease;
}

.btn:hover { transform: translateY(-2px); }
.btn-back:hover { transform: translateY(-2px); }
.btn-edit:hover { transform: translateY(-2px); }

/* Table styles */
table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
    font-size: 14px;
}

table th {
    background: #2980b9;
    color: #fff;
    padding: 12px;
    text-align: left;
}

table td {
    padding: 10px;
    border-bottom: 1px solid #ddd;
}

table tr:nth-child(even) {
    background: rgba(255,255,255,0.6);
}

</style>
</head>
<body>

<div class="container">
    <h2>CG-12 Document Monitoring</h2>
    <a href="add_document.php" class="btn">Add New Document</a>
    <table>
        <tr>
            <th>#</th>
            <th>Document No</th>
            <th>Scanned At</th>
            <th>Type</th>
            <th>Subject</th>
            <th>Encoder</th>
            <th>Receiving Office</th>
            <th>Time In</th>
            <th>Time Out</th>
            <th>Remarks</th>
            <th>Forwarded To</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
        <?php if ($result->num_rows > 0): ?>
            <?php while($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo $row['id']; ?></td>
                <td><?php echo $row['document_nr']; ?></td>
                <td><?php echo $row['scanned_at']; ?></td>
                <td><?php echo $row['doc_type']; ?></td>
                <td><?php echo $row['subject']; ?></td>
                <td><?php echo $row['encoder']; ?></td>
                <td><?php echo $row['receiving_office']; ?></td>
                <td><?php echo $row['time_in']; ?></td>
                <td><?php echo $row['time_out']; ?></td>
                <td><?php echo $row['remarks']; ?></td>
                <td><?php echo $row['forwarded_to']; ?></td>
                <td><?php echo ucfirst($row['status']); ?></td>
               <td>  
                <a href="edit_document.php?id=<?php echo $row['id']; ?>" class="btn-edit">Edit</a>
               </td></tr>
            <td>
              <?php 
                if($row['uploaded_file']){
                echo '<a href="uploads/'.$row['uploaded_file'].'" target="_blank">View</a>';
                } else {
              echo 'No file';
                  }
                  ?>
            </td>

            <?php endwhile; ?>
        <?php else: ?>
            <tr><td colspan="12" style="text-align:center;">No documents found.</td></tr>
        <?php endif; ?>
    </table>
</div>

</body>
</html>
