<?php
// nav.php (include in pages after requiring db.php)
if (!isset($_SESSION['user_id'])) return;
?>
<header class="topbar">
  <div class="brand">CG-12 Monitoring</div>
  <nav>
    <a href="index.php">Dashboard</a>
    <a href="documents.php">Documents</a>
    <a href="add_document.php">Add Document</a>
    <a href="logout.php" class="danger">Logout (<?php echo htmlspecialchars($_SESSION['username']); ?>)</a>
  </nav>
</header>
