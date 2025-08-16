<?php
require 'db.php';
if (!isset($_SESSION['user_id'])) { header('Location: login.php'); exit; }
if (!in_array($_SESSION['role'], ['admin','duty_watch'])) { echo "Unauthorized"; exit; }

$id = intval($_GET['id'] ?? 0);
$stmt = $mysqli->prepare("SELECT uploaded_file FROM documents WHERE id = ?");
$stmt->bind_param('i',$id); $stmt->execute(); $r = $stmt->get_result()->fetch_assoc();
if ($r && $r['uploaded_file'] && file_exists('uploads/'.$r['uploaded_file'])) unlink('uploads/'.$r['uploaded_file']);

$del = $mysqli->prepare("DELETE FROM documents WHERE id=?");
$del->bind_param('i',$id); $del->execute();
header('Location: documents.php');
exit;
