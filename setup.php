<?php
// setup.php - create DB + tables + seed accounts
// Edit $user/$pass above if necessary (this script connects without selecting DB first)

$host = '127.0.0.1';
$user = 'root';
$pass = ''; // set DB root password

$mysqli = new mysqli($host, $user, $pass);
if ($mysqli->connect_errno) die("Connect failed: " . $mysqli->connect_error);

// create database
$db = 'cg12_monitoring';
$mysqli->query("CREATE DATABASE IF NOT EXISTS `$db` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
$mysqli->select_db($db);

// users table
$mysqli->query("CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) UNIQUE NOT NULL,
    fullname VARCHAR(200) NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin','duty_watch','secretary') NOT NULL DEFAULT 'duty_watch',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;");

// documents table — canonical column names used by the app
$mysqli->query("CREATE TABLE IF NOT EXISTS documents (
    id INT AUTO_INCREMENT PRIMARY KEY,
    document_nr VARCHAR(120) NOT NULL,
    scanned_at DATETIME NOT NULL,
    doc_type VARCHAR(120) NOT NULL,
    subject TEXT NOT NULL,
    encoder VARCHAR(150) NOT NULL,
    receiving_office VARCHAR(150) NOT NULL,
    time_in TIME DEFAULT NULL,
    time_out TIME DEFAULT NULL,
    remarks TEXT,
    forwarded_to VARCHAR(150),
    status ENUM('pending','in-progress','completed') DEFAULT 'pending',
    uploaded_file VARCHAR(255) DEFAULT NULL,
    created_by INT DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT NULL,
    FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB;");

// seed users (only if not exist)
$pw_admin = password_hash('admin123', PASSWORD_DEFAULT);
$pw_watch = password_hash('watch123', PASSWORD_DEFAULT);
$pw_sec   = password_hash('secretary123', PASSWORD_DEFAULT);

$mysqli->query("INSERT IGNORE INTO users (username, fullname, password, role) VALUES
    ('admin', 'CG-12 Admin', '{$pw_admin}', 'admin'),
    ('watch', 'Duty Watch Officer', '{$pw_watch}', 'duty_watch'),
    ('secretary', 'Office Secretary', '{$pw_sec}', 'secretary')");

echo "Setup completed successfully.<br>";
echo "Default accounts:<br>";
echo "Admin: username <b>admin</b> password <b>admin123</b><br>";
echo "Duty Watch: username <b>watch</b> password <b>watch123</b><br>";
echo "Secretary: username <b>secretary</b> password <b>secretary123</b><br>";
echo "<br><b>Important:</b> Delete or move setup.php after running it for security.<br>";
?>
