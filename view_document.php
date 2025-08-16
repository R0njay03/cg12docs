<?php
require 'db.php';
if (!isset($_SESSION['user_id'])) { header('Location: login.php'); exit; }
$id = intval($_GET['id'] ?? 0);
$stmt = $mysqli->prepare("SELECT d.*, u.fullname AS created_by_name FROM documents d LEFT JOIN users u ON d.created_by = u.id WHERE d.id=?");
$stmt->bind_param('i', $id);
$stmt->execute(); $res = $stmt->get_result();
if ($res->num_rows !== 1) { echo "Document not found"; exit; }
$row = $res->fetch_assoc();
?>
<!doctype html>
<html><head><meta charset="utf-8"><title>View Document</title><link rel="stylesheet" href="styles.css"></head>
<body>
<?php include 'nav.php'; ?>
<main class="container">
  <h1>Document #<?=htmlspecialchars($row['document_nr'])?></h1>
  <div class="card">
    <dl class="doc-detail">
      <dt>Scanned at</dt><dd><?=date('Y-m-d H:i', strtotime($row['scanned_at']))?></dd>
      <dt>Type</dt><dd><?=htmlspecialchars($row['doc_type'])?></dd>
      <dt>Subject</dt><dd><?=nl2br(htmlspecialchars($row['subject']))?></dd>
      <dt>Receiving Office</dt><dd><?=htmlspecialchars($row['receiving_office'])?></dd>
      <dt>Encoder</dt><dd><?=htmlspecialchars($row['encoder'])?></dd>
      <dt>Time In</dt><dd><?=htmlspecialchars($row['time_in'])?></dd>
      <dt>Time Out</dt><dd><?=htmlspecialchars($row['time_out'])?></dd>
      <dt>Forwarded To</dt><dd><?=htmlspecialchars($row['forwarded_to'])?></dd>
      <dt>Remarks</dt><dd><?=nl2br(htmlspecialchars($row['remarks']))?></dd>
      <dt>Status</dt><dd><?=ucfirst($row['status'])?></dd>
      <dt>Uploaded file</dt>
      <dd>
        <?php if($row['uploaded_file']): ?>
          <a href="uploads/<?=urlencode($row['uploaded_file'])?>" target="_blank">Download / View</a>
        <?php else: ?>No file attached<?php endif; ?>
      </dd>
      <dt>Created by</dt><dd><?=htmlspecialchars($row['created_by_name'])?> at <?=htmlspecialchars($row['created_at'])?></dd>
    </dl>
    <div class="actions">
      <a class="btn" href="documents.php">Back</a>
      <?php if(in_array($_SESSION['role'], ['admin','duty_watch'])): ?>
        <a class="btn ghost" href="edit_document.php?id=<?=$row['id']?>">Edit</a>
        <a class="btn danger" href="delete_document.php?id=<?=$row['id']?>" onclick="return confirm('Delete?')">Delete</a>
      <?php endif; ?>
    </div>
  </div>
</main>
</body></html>
