<?php
session_start();
//menambahkan koneksi ke database
include "koneksi.php";
// jika belum login arahkan ke halaman login
if (!isset($_SESSION['username'])) {
    header("location:login.php");
    exit;
}

// sertakan koneksi ke database
include "koneksi.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - My Daily Journal</title>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" />
    <style>
        body {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        #content {
            flex: 1;
        }
    </style>
</head>
<body>

    <!-- nav begin -->
    <nav class="navbar navbar-expand-sm bg-body-tertiary sticky-top bg-danger-subtle">
        <div class="container">
            <a class="navbar-brand" target="_blank" href=".">My Daily Journal</a>
            <button
                class="navbar-toggler"
                type="button"
                data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent"
                aria-expanded="false"
                aria-label="Toggle navigation"
            >
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0 text-dark">
                    <li class="nav-item">
    					<a class="nav-link" href="admin.php?page=dashboard">Dashboard</a>
						</li>
						<li class="nav-item">
    					<a class="nav-link" href="admin.php?page=article">Article</a>
					</li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle text-danger fw-bold" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <?= htmlspecialchars($_SESSION['username'])?>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="logout.php">Logout</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <!-- nav end -->

   		<!-- content begin -->
    <section id="content" class="p-5">
        <div class="container"> 
			<?php
            // Ambil parameter page dari URL
            $page = isset($_GET['page']) ? $_GET['page'] : "dashboard";
            
            // Sanitasi input
            $page = basename($page);
            
            // Whitelist halaman yang diizinkan
            $allowed_pages = ['dashboard', 'article'];
            
            // Tampilkan judul page dengan huruf kapital di awal setiap kata
            $title = ucwords(str_replace(['-','_'], ' ', $page));
            echo '<h4 class="lead display-6 pb-2 border-bottom border-danger-subtle">' . htmlspecialchars($title) . '</h4>';
            
            // Debug info (hapus di production)
            // echo '<!-- DEBUG: page=' . $page . ', file=' . $page . '.php, exists=' . (file_exists($page . ".php") ? 'yes' : 'no') . ' -->';
            
            // Cek apakah halaman ada dalam whitelist dan file ada
            if (in_array($page, $allowed_pages)) {
                $filename = $page . ".php";
                if (file_exists($filename)) {
                    include $filename;
                } else {
                    echo '<div class="alert alert-danger"><strong>Error!</strong> File ' . htmlspecialchars($filename) . ' tidak ditemukan di folder.</div>';
                }
            } else {
                echo '<div class="alert alert-warning"><strong>Akses ditolak!</strong> Halaman "' . htmlspecialchars($page) . '" tidak ada dalam whitelist.</div>';
            }
            ?>
        </div> 
    </section>
    <!-- content end -->

    <!-- footer begin -->
    <footer class="text-center p-3 bg-danger-subtle">
        <div>
            <a href="https://www.instagram.com/udinusofficial"><i class="bi bi-instagram h2 p-2 text-dark"></i></a>
            <a href="https://twitter.com/udinusofficial"><i class="bi bi-twitter h2 p-2 text-dark"></i></a>
            <a href="https://wa.me/+62812685577"><i class="bi bi-whatsapp h2 p-2 text-dark"></i></a>
        </div>
        <div>Sahal Fikri Maftukhin &copy; 2025
		</div>
    </footer>
    <!-- footer end -->

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</body>
</html>