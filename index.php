<?php
//menyertakan code dari file koneksi
include "koneksi.php";
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>My Daily Jurnal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" />
    <style>
      body {
        min-height: 100vh;
        display: flex;
        flex-direction: column;
      }
      footer {
        margin-top: auto;
      }
    </style>
  </head>
  <body>
    <!-- navbar-->
    <nav class="navbar navbar-expand-lg bg-danger-subtle">
      <div class="container-fluid">
        <a class="navbar-brand" href="#">My Daily Jurnal</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
          <div class="navbar-nav ms-auto">
            <a class="nav-link active" aria-current="page" href="index.php">Home</a>
            <a class="nav-link" href="admin.php?page=article">Article</a>
            <a class="nav-link" href="admin.php">Admin</a>
          </div>
        </div>
      </div>
    </nav>
    <!-- navbar end -->

    <!-- article begin -->
    <section id="article" class="text-center-p-5">
      <div class="container">
        <h1 class="fw-bold display-4 pb-3 text-center">article</h1>

        <div class="row row-cols-1 row-cols-md-5 g-4 justify-content-center">
          <?php
          $sql = "SELECT * FROM article ORDER BY tanggal DESC";
          $hasil = $conn->query($sql);
          
          if ($hasil && $hasil->num_rows > 0) {
            while ($row = $hasil->fetch_assoc()) {
              $gambar = !empty($row['gambar']) && file_exists('img/' . $row['gambar']) ? 'img/' . $row['gambar'] : 'img/default.jpg';
              $judul = htmlspecialchars($row['judul']);
              $isi = htmlspecialchars(substr($row['isi'], 0, 100));
              $tanggal = htmlspecialchars($row['tanggal']);
          ?>
          <!-- col begin -->
          <div class="col">
            <div class="card h-100">
              <img src="<?= $gambar ?>" class="card-img-top" alt="<?= $judul ?>" />

              <div class="card-body">
                <h5 class="card-title"><?= $judul ?></h5>

                <p class="card-text"><?= $isi ?>...</p>
              </div>

              <div class="card-footer">
                <small class="text-body-secondary"><?= $tanggal ?></small>
              </div>
            </div>
          </div>
          <!-- col end -->
          <?php
            }
          } else {
            echo '<p class="text-center">Belum ada artikel.</p>';
          }
          ?>
        </div>
      </div>
    </section>

    <!-- article end -->

    <!-- footer begin -->
    <footer class="text-center p-3 bg-danger-subtle">
        <div>
            <a href="https://www.instagram.com/udinusofficial"><i class="bi bi-instagram h2 p-2 text-dark"></i></a>
            <a href="https://twitter.com/udinusofficial"><i class="bi bi-twitter h2 p-2 text-dark"></i></a>
            <a href="https://wa.me/+62812685577"><i class="bi bi-whatsapp h2 p-2 text-dark"></i></a>
        </div>
        <div>Sahal Fikri Maftukhin &copy; 2024</div>
    </footer>
    <!-- footer end -->

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
  </body>
</html>
