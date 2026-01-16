<?php
// query untuk mengambil data article
$sql1 = "SELECT * FROM article ORDER BY tanggal DESC";
$hasil1 = $conn->query($sql1);

// menghitung jumlah baris data article
$jumlah_article = $hasil1->num_rows;

// ambil data user dari session untuk ditampilkan di dashboard
$username_session = isset($_SESSION['username']) ? $_SESSION['username'] : '';
$foto_user = '';
if ($username_session) {
    $stmt_user = $conn->prepare("SELECT username, foto FROM user WHERE username = ? LIMIT 1");
    $stmt_user->bind_param("s", $username_session);
    $stmt_user->execute();
    $res_user = $stmt_user->get_result();
    $row_user = $res_user->fetch_assoc();
    if ($row_user) {
        $username_session = $row_user['username'];
        $foto_user = $row_user['foto'];
    }
    $stmt_user->close();
}

// tentukan path foto (fallback ke img/profil.jpg jika tidak ada)
$foto_path = 'img/profil.jpg';
if (!empty($foto_user) && file_exists(__DIR__ . '/img/' . $foto_user)) {
    $foto_path = 'img/' . $foto_user;
}

// hitung jumlah gallery dari database
$res_gallery = $conn->query("SELECT COUNT(*) AS cnt FROM gallery");
$jumlah_gallery = 0;
if ($res_gallery) {
    $r = $res_gallery->fetch_assoc();
    $jumlah_gallery = isset($r['cnt']) ? (int)$r['cnt'] : 0;
}
?>

<div class="text-center pt-4">
    <h1 class="mb-2">Hallo !!</h1>
    <p class="lead">Selamat Datang,</p>
    <h3 class="text-danger fw-bold"><?php echo htmlspecialchars($username_session); ?></h3>
    <div class="my-4">
        <img src="<?php echo htmlspecialchars($foto_path); ?>" alt="Profil" style="width:200px;height:200px;border-radius:50%;object-fit:cover;border:4px solid #fff;box-shadow:0 4px 12px rgba(0,0,0,0.15);">
    </div>
</div>

<div class="row row-cols-1 row-cols-md-4 g-4 justify-content-center pt-4">
    <div class="col">
        <div class="card border border-danger mb-3 shadow" style="max-width: 18rem;">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div class="p-3">
                        <h5 class="card-title"><i class="bi bi-newspaper"></i> Article</h5> 
                    </div>
                    <div class="p-3">
                        <span class="badge rounded-pill text-bg-danger fs-2"><?php echo $jumlah_article; ?></span>
                    </div> 
                </div>
            </div>
        </div>
    </div> 
    <div class="col">
        <div class="card border border-danger mb-3 shadow" style="max-width: 18rem;">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div class="p-3">
                        <h5 class="card-title"><i class="bi bi-camera"></i> Gallery</h5> 
                    </div>
                    <div class="p-3">
                        <span class="badge rounded-pill text-bg-danger fs-2"><?php echo $jumlah_gallery; ?></span>
                    </div> 
                </div>
            </div>
        </div>
    </div> 
</div>
