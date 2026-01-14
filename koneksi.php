<?php
date_default_timezone_set('Asia/Jakarta');

$servername = "localhost";
$username = "root";
$password = "";
$db = "webdailyjournal"; //nama database

//create connection
$conn = new mysqli($servername,$username,$password,$db);

//check apakah ada error connection
if($conn->connect_error){
	//jika ada, hentikan script dan tampilkan pesan error
	header("Content-Type: text/html; charset=utf-8");
	echo "<h2>Database Error</h2>";
	echo "<p>Koneksi database gagal: " . htmlspecialchars($conn->connect_error) . "</p>";
	echo "<p>Pastikan database 'webdailyjournal' sudah dibuat dan MySQL/XAMPP sudah berjalan.</p>";
	exit;
}

?>