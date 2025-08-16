<?php
require 'db.php';
if (!isset($_SESSION['user_id'])) { header('Location: login.php'); exit; }

$term = trim($_GET['q'] ?? '');
$status = $_GET['status'] ?? '';
$page = max(1, intval($_GET['page'] ?? 1));
$perpage = 15;
$offset = ($page-1)*$perpage;

$where = "WHERE 1=1";
$params = [];
$types = '';
if ($term !== '') { $where .= " AND (document_nr LIKE ? OR subject LIKE ? OR receiving_office LIKE ?)"; $params[] = "%$term%"; $params[] = "%$term%"; $params[] = "%$term%"; $types .= 'sss'; }
if ($status !== '') { $where .= " AND status = ?"; $params[] = $status; $types .= 's'; }

// count
$sqlCount = "SELECT COUNT(*) AS cnt FROM documents $where";
$stmt = $mysqli->prepare($sqlCount);
if ($params) $stmt->bind_param($types, ...$params);
$stmt->execute(); $cnt = $stmt->get_result()->fetch_assoc()['cnt'] ?? 0;
$pages = max(1, ceil($cnt/$perpage));

// fetch page
$sql = "SELECT d.*, u.fullname AS created_by_name FROM documents d LEFT JOIN users u ON d.created_by = u.id $where ORDER BY d.created_at DESC LIMIT ? OFFSET ?";
$stmt = $mysqli->prepare($sql);
$bindTypes = $types . 'ii';
$bindParams = array_merge($params, [$perpage, $offset]);
$stmt->bind_param($bindTypes, ...$bindParams);
$stmt->execute(); $res = $stmt->get_result();
?>
<!doctype html>
<html>
<head><meta charset="utf-8"><title>Documents</title><link rel="stylesheet" href="styles.css"></head>
<body>
<?php include 'nav.php'; ?>
<main class="container">
  <h1>Documents Monitoring</h1>

  <form class="search-form" method="get">
    <input type="search" name="q" placeholder="Search doc no, subject, office..." value="<?=htmlspecialchars($term)?>">
    <select name="status">
      <option value="">All status</option>
      <option value="pending" <?= $status==='pending'?'selected':''?>>Pending</option>
      <option value="in-progress" <?= $status==='in-progress'?'selected':''?>>In-Progress</option>
      <option value="completed" <?= $status==='completed'?'selected':''?>>Completed</option>
    </select>
    <button class="btn" type="submit">Search</button>
  </form>

  <table class="striped">
    <thead><tr><th>#</th><th>Doc No</th><th>Scanned</th><th>Type</th><th>Subject</th><th>Receiving</th><th>Status</th><th>Actions</th></tr></thead>
    <tbody>
      <?php while($row = $res->fetch_assoc()): ?>
        <tr>
          <td><?=$row['id']?></td>
          <td><?=htmlspecialchars($row['document_nr'])?></td>
          <td><?=date('Y-m-d H:i', strtotime($row['scanned_at']))?></td>
          <td><?=htmlspecialchars($row['doc_type'])?></td>
          <td><?=htmlspecialchars(substr($row['subject'],0,60))?></td>
          <td><?=htmlspecialchars($row['receiving_office'])?></td>
          <td><?=htmlspecialchars(ucfirst($row['status']))?></td>
          <td>
            <a class="btn-small" href="view_document.php?id=<?=$row['id']?>">View</a>
            <?php if(in_array($_SESSION['role'], ['admin','duty_watch'])): ?>
              <a class="btn-small ghost" href="edit_document.php?id=<?=$row['id']?>">Edit</a>
              <a class="btn-small danger" href="delete_document.php?id=<?=$row['id']?>" onclick="return confirm('Delete this?')">Delete</a>
            <?php endif; ?>
          </td>
        </tr>
      <?php endwhile; ?>
    </tbody>
  </table>

  <div class="pagination">
    <?php for($p=1;$p<=$pages;$p++): ?>
      <a class="<?= $p==$page ? 'active' : ''?>" href="?<?= http_build_query(array_merge($_GET, ['page'=>$p])) ?>"><?= $p ?></a>
    <?php endfor; ?>
  </div>
</main>
</body>
</html>
