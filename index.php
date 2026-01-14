<?php
session_start();
include "koneksi.php";

/* Default identitas */
$nama = "Sahal Fikri Maftukhin";
$foto_profil = "img/pp.jpg";

/* Jika login */
if (isset($_SESSION['username'])) {
  $username = $_SESSION['username'];
  $stmt = $conn->prepare("SELECT username, foto FROM user WHERE username=?");
  $stmt->bind_param("s", $username);
  $stmt->execute();
  $result = $stmt->get_result();
  if ($row = $result->fetch_assoc()) {
    $nama = $row['username'];
    if (!empty($row['foto']) && file_exists("img/" . $row['foto'])) {
      $foto_profil = "img/" . $row['foto'];
    }
  }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>My Daily Jurnal - Sahal Fikri Maftukhin</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet" />
  <style>
    /* Default Dark Mode */
    * {
      transition: background-color 0.3s, color 0.3s, border-color 0.3s;
    }

    body {
      background-color: #1a1a1a;
      color: #e8e8e8;
    }

    .bg-soft {
      background-color: #2d2d2d;
    }

    .navbar {
      background-color: #1a1a1a !important;
      border-bottom: 1px solid #404040 !important;
    }

    .navbar-brand,
    .nav-link {
      color: #e8e8e8 !important;
    }

    .nav-link:hover,
    .nav-link:focus {
      color: #64b5f6 !important;
    }

    .card {
      background-color: #2d2d2d;
      color: #e8e8e8;
      border: none;
      border-radius: 16px;
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3);
    }

    .text-muted {
      color: #a8a8a8 !important;
    }

    h1, h2, h3, h4, h5, h6 {
      color: #e8e8e8;
    }

    #theme-toggle {
      background-color: #2d2d2d !important;
      border-color: #404040 !important;
      color: #e8e8e8 !important;
    }

    #theme-toggle:hover {
      background-color: #404040 !important;
    }

    /* Light Mode */
    body.light-theme {
      background-color: #ffffff !important;
      color: #212529 !important;
    }

    body.light-theme .bg-soft {
      background-color: #f8f9fa !important;
    }

    body.light-theme .navbar {
      background-color: #ffffff !important;
      border-bottom-color: #dee2e6 !important;
    }

    body.light-theme .navbar-brand,
    body.light-theme .nav-link {
      color: #212529 !important;
    }

    body.light-theme .nav-link:hover,
    body.light-theme .nav-link:focus {
      color: #0d6efd !important;
    }

    body.light-theme .card {
      background-color: #ffffff !important;
      color: #212529 !important;
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1) !important;
    }

    body.light-theme .text-muted {
      color: #6c757d !important;
    }

    body.light-theme h1,
    body.light-theme h2,
    body.light-theme h3,
    body.light-theme h4,
    body.light-theme h5,
    body.light-theme h6 {
      color: #212529 !important;
    }

    /* User Greeting Text */
    .user-greeting {
      color: #e8e8e8;
    }

    body.light-theme .user-greeting {
      color: #212529 !important;
    }
    }

    body.light-theme #theme-toggle {
      background-color: #ffffff !important;
      border-color: #dee2e6 !important;
      color: #212529 !important;
    }

    body.light-theme #theme-toggle:hover {
      background-color: #f8f9fa !important;
    }

    /* HERO */
    .hero-section {
      min-height: 90vh;
      padding: 100px 0;
      display: flex;
      align-items: center;
    }

    .hero-image {
      width: 100%;
      max-width: 480px;
      border-radius: 20px;
      box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
    }

    /* GALLERY */
    .gallery-img {
      width: 100%;
      height: 300px;
      object-fit: cover;
      border-radius: 12px;
      cursor: pointer;
      transition: transform 0.3s;
    }

    .gallery-img:hover {
      transform: scale(1.05);
    }

    /* SCHEDULE */
    .schedule-header-mon {background: #0d6efd;}
    .schedule-header-tue {background: #198754;}
    .schedule-header-wed {background: #6f42c1;}
    .schedule-header-thu {background: #ffc107; color: #000;}
    .schedule-header-fri {background: #20c997;}
    .schedule-header-sat {background: #6c757d;}
    .schedule-header-sun {background: #dc3545;}

    /* PROFILE */
    .profile-img {
      width: 280px;
      height: 280px;
      border-radius: 50%;
      object-fit: cover;
    }

    /* FOOTER */
    .footer-icon {
      font-size: 1.5rem;
      transition: 0.3s;
    }

    .footer-icon:hover {
      transform: scale(1.2);
    }
  </style>
</head>

<body>

<nav class="navbar navbar-expand-lg sticky-top border-bottom">
  <div class="container">
    <a class="navbar-brand fw-bold" href="#">My Daily Jurnal</a>
    <button class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#nav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="nav">
      <ul class="navbar-nav ms-auto align-items-center gap-2">
        <li class="nav-item"><a class="nav-link" href="#">Home</a></li>
        <li class="nav-item"><a class="nav-link" href="#article">Article</a></li>
        <li class="nav-item"><a class="nav-link" href="#gallery">Gallery</a></li>
        <li class="nav-item"><a class="nav-link" href="#aboutme">About Me</a></li>
        <li class="nav-item">
          <button class="btn btn-outline-secondary btn-sm" id="theme-toggle" type="button">
            <i class="bi bi-moon-stars-fill" id="theme-icon"></i>
          </button>
        </li>
        <li class="nav-item">
          <?php if (isset($_SESSION['username'])): ?>
            <div class="d-flex align-items-center gap-2">
              <span class="user-greeting">Halo, <strong><?php echo htmlspecialchars($nama); ?></strong></span>
              <a href="login.php" class="btn btn-sm btn-primary">Admin Login</a>
              <a href="logout.php" class="btn btn-sm btn-danger">Logout</a>
            </div>
          <?php else: ?>
            <a href="login.php" class="btn btn-sm btn-success">Login</a>
          <?php endif; ?>
        </li>
      </ul>
    </div>
  </div>
</nav>

<!-- HERO -->
<section class="hero-section">
  <div class="container">
    <div class="row align-items-center">
      <div class="col-md-6 text-md-start text-center">
        <h1 class="display-4 fw-bold mb-3">
          My Jurnal: Di Sini Saya Menemukan Bug Hidup
        </h1>
        <p class="lead mb-4">
          Repository pribadi berisi catatan belajar, project, teknologi, dan refleksi dunia digital.
        </p>
        <span id="tanggal"></span> | <span id="jam"></span>
      </div>
      <div class="col-md-6 text-center mt-4 mt-md-0">
        <img src="img/Mobil.jpg" class="hero-image" alt="Ilustrasi Jurnal" onerror="this.style.display='none'">
      </div>
    </div>
  </div>
</section>

<!-- ARTICLE -->
<section id="article" class="p-5 text-center">
  <div class="container">
    <h1 class="fw-bold display-4 mb-4">Article</h1>
    <div class="row row-cols-1 row-cols-md-3 g-4">
      <div class="col">
        <div class="card h-100">
          <img src="img/Belajar.jpg" 
               onerror="this.src='img/default.jpg'"
               style="height:200px;object-fit:cover"
               class="card-img-top">
          <div class="card-body">
            <h5>Belajar Web Development</h5>
            <p class="text-muted">Panduan lengkap belajar HTML, CSS, dan JavaScript dari nol hingga mahir...</p>
          </div>
          <div class="card-footer bg-transparent border-0">
            <small>14 Jan 2026</small>
          </div>
        </div>
      </div>
      <div class="col">
        <div class="card h-100">
          <img src="img/Kopi.jpg" 
               onerror="this.src='img/default.jpg'"
               style="height:200px;object-fit:cover"
               class="card-img-top">
          <div class="card-body">
            <h5>Tips Produktivitas</h5>
            <p class="text-muted">Cara meningkatkan produktivitas dalam belajar coding dan mengembangkan project...</p>
          </div>
          <div class="card-footer bg-transparent border-0">
            <small>13 Jan 2026</small>
          </div>
        </div>
      </div>
      <div class="col">
        <div class="card h-100">
          <img src="img/Pantai.jpg" 
               onerror="this.src='img/default.jpg'"
               style="height:200px;object-fit:cover"
               class="card-img-top">
          <div class="card-body">
            <h5>Database MySQL</h5>
            <p class="text-muted">Pengenalan basis data relasional dan cara menggunakannya dengan PHP...</p>
          </div>
          <div class="card-footer bg-transparent border-0">
            <small>12 Jan 2026</small>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- GALLERY -->
<section id="gallery" class="p-5 bg-soft text-center">
  <div class="container">
    <h1 class="fw-bold display-4 mb-4">Gallery</h1>
    <div class="row row-cols-1 row-cols-md-3 g-4">
      <?php
      $q = $conn->query("SELECT * FROM gallery ORDER BY tanggal DESC");
      if ($q->num_rows > 0) {
        while ($r = $q->fetch_assoc()):
          ?>
          <div class="col">
            <div class="position-relative">
              <img src="img/<?php echo $r['gambar'] ?>" 
                   alt="Gallery" 
                   class="gallery-img shadow" 
                   data-bs-toggle="modal" 
                   data-bs-target="#galleryModal"
                   onerror="this.src='img/default.jpg'"
                   onclick="document.getElementById('modalImage').src='img/<?php echo $r['gambar'] ?>'; document.getElementById('modalTitle').textContent='<?php echo htmlspecialchars($r['judul']) ?>'">
              <div class="position-absolute bottom-0 start-0 end-0 bg-dark bg-opacity-50 p-2">
                <p class="text-white mb-0 small fw-bold"><?php echo htmlspecialchars($r['judul']) ?></p>
              </div>
            </div>
          </div>
        <?php
        endwhile;
      } else {
        echo '<p class="text-muted">Belum ada data gallery</p>';
      }
      ?>
    </div>
  </div>
</section>

<!-- Modal untuk Gallery -->
<div class="modal fade" id="galleryModal" tabindex="-1">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header border-0">
        <h5 class="modal-title" id="modalTitle">Gallery</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body text-center">
        <img id="modalImage" src="" alt="Gallery" style="width:100%; border-radius:12px;">
      </div>
    </div>
  </div>
</div>

<!-- ABOUT ME -->
<section id="aboutme" class="p-5 bg-soft">
  <div class="container">
    <h1 class="fw-bold display-4 mb-5 text-center">About Me</h1>
    <div class="row align-items-center">
      <div class="col-md-4 text-center mb-4 mb-md-0">
        <img src="img/profil.jpg" alt="Profil" class="profile-img shadow" onerror="this.src='https://via.placeholder.com/280'">
      </div>
      <div class="col-md-8">
        <div class="card">
          <div class="card-body">
            <h3 class="card-title fw-bold">Sahal Fikri Maftukhin</h3>
            <p class="card-text text-muted">
              <strong>NIM:</strong> A11.2024.15863
            </p>
            <p class="card-text">
              <strong><i class="bi bi-geo-alt"></i> Lokasi:</strong> Bogor Kec. Cibinong, Jawa Barat
            </p>
            <p class="card-text">
              <strong><i class="bi bi-envelope"></i> Email:</strong> sahaalfkr@gmail.com
            </p>
            <hr>
            <h5 class="fw-bold mt-4">Tentang Saya</h5>
            <p class="card-text">
              Saya adalah seorang mahasiswa dengan passion untuk belajar teknologi dan pengembangan web. 
              Repository ini merupakan kumpulan catatan, project, dan refleksi saya dalam perjalanan belajar coding.
            </p>
            <h5 class="fw-bold mt-4">Skill</h5>
            <p class="card-text">
              <span class="badge bg-primary">HTML</span>
              <span class="badge bg-primary">CSS</span>
              <span class="badge bg-primary">JavaScript</span>
              <span class="badge bg-primary">PHP</span>
              <span class="badge bg-primary">MySQL</span>
              <span class="badge bg-success">Bootstrap</span>
            </p>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<footer class="bg-dark text-white text-center py-4">
  <div class="d-flex justify-content-center gap-4 mb-3">
    <a href="#" class="text-white"><i class="bi bi-instagram footer-icon"></i></a>
    <a href="#" class="text-white"><i class="bi bi-whatsapp footer-icon"></i></a>
    <a href="#" class="text-white"><i class="bi bi-tiktok footer-icon"></i></a>
  </div>
  <p class="mb-0">&copy; Sahal Fikri Maftukhin</p>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
<script>
  // Dark Mode Toggle
  document.addEventListener('DOMContentLoaded', function() {
    const themeToggleBtn = document.getElementById("theme-toggle");
    const themeIcon = document.getElementById("theme-icon");
    const body = document.body;

    // Load saved theme preference
    const savedTheme = localStorage.getItem("theme") || "dark";
    if (savedTheme === "light") {
      body.classList.add("light-theme");
      themeIcon.className = "bi bi-sun-fill";
    }

    // Toggle theme on button click
    themeToggleBtn.addEventListener("click", function () {
      body.classList.toggle("light-theme");
      
      if (body.classList.contains("light-theme")) {
        localStorage.setItem("theme", "light");
        themeIcon.className = "bi bi-sun-fill";
      } else {
        localStorage.setItem("theme", "dark");
        themeIcon.className = "bi bi-moon-stars-fill";
      }
    });
  });

  // Time display
  function waktu() {
    const d = new Date();
    const tanggalEl = document.getElementById("tanggal");
    const jamEl = document.getElementById("jam");
    if (tanggalEl) tanggalEl.innerHTML = d.toLocaleDateString('id-ID');
    if (jamEl) jamEl.innerHTML = d.toLocaleTimeString('id-ID');
  }
  
  setInterval(waktu, 1000);
  waktu();
</script>
