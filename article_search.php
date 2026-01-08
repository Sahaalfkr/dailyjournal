<?php
include "koneksi.php";

$keyword = $_POST['keyword'];

$sql = "SELECT * FROM article 
        WHERE judul LIKE ? OR isi LIKE ? OR tanggal LIKE ? OR username LIKE ?
        ORDER BY tanggal DESC";

$stmt = $conn->prepare($sql);
$search = "%" . $keyword . "%";
$stmt->bind_param("ssss", $search, $search, $search, $search);
$stmt->execute();

$hasil = $stmt->get_result();

$no = 1;
if ($hasil && $hasil->num_rows > 0) {
    while ($row = $hasil->fetch_assoc()) { ?>
<tr>
    <td><?= $no++ ?></td>
    <td>
        <strong><?= htmlspecialchars($row['judul']) ?></strong>
        <br><small class="text-muted">pada : <?= htmlspecialchars(
            $row['tanggal'],
        ) ?></small>
        <br><small class="text-muted">oleh : <?= htmlspecialchars(
            $row['username'],
        ) ?></small>
    </td>
    <td><?= htmlspecialchars(substr($row['isi'], 0, 100)) ?>...</td>
    <td>
        <?php if (
            !empty($row['gambar']) &&
            file_exists('img/' . $row['gambar'])
        ) {
            echo '<img src="img/' .
                $row['gambar'] .
                '" class="img-fluid" alt="Gambar Artikel" style="max-width: 180px; height: auto; border-radius: 5px;">';
        } else {
            echo '<span class="text-muted">-</span>';
        } ?>
    </td>
    <td>
        <a href="#" title="edit" class="badge rounded-pill text-bg-success" data-bs-toggle="modal" data-bs-target="#modalEdit<?= $row["id"] ?>"><i class="bi bi-pencil"></i></a>
        <a href="#" title="delete" class="badge rounded-pill text-bg-danger" data-bs-toggle="modal" data-bs-target="#modalHapus<?= $row["id"] ?>"><i class="bi bi-x-circle"></i></a>
        <!-- Awal Modal Edit -->
        <div class="modal fade" id="modalEdit<?= $row["id"] ?>" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="staticBackdropLabel">Edit Article</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form method="post" action="" enctype="multipart/form-data">
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="judul" class="form-label">Judul</label>
                                <input type="hidden" name="id" value="<?= $row["id"] ?>">
                                <input type="text" class="form-control" name="judul" placeholder="Tuliskan Judul Artikel" value="<?= $row["judul"] ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="isi">Isi</label>
                                <textarea class="form-control" placeholder="Tuliskan Isi Artikel" name="isi" required><?= $row["isi"] ?></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="gambar" class="form-label">Ganti Gambar</label>
                                <input type="file" class="form-control" name="gambar">
                            </div>
                            <div class="mb-3">
                                <label for="formGroupExampleInput3" class="form-label">Gambar Lama</label>
                                <?php
                                if ($row["gambar"] != '') {
                                    if (file_exists('img/' . $row["gambar"] . '')) { 
                                        echo '<br><img src="img/' . $row["gambar"] . '" class="img-fluid" alt="Gambar Artikel">';
                                    }
                                }
                                ?>
                                <input type="hidden" name="gambar_lama" value="<?= $row["gambar"] ?>">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <input type="submit" value="simpan" name="simpan" class="btn btn-primary">
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- Akhir Modal Edit -->

        <!-- Awal Modal Hapus -->
        <div class="modal fade" id="modalHapus<?= $row["id"] ?>" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="staticBackdropLabel">Konfirmasi Hapus Article</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form method="post" action="" enctype="multipart/form-data">
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="formGroupExampleInput" class="form-label">Yakin akan menghapus artikel "<strong><?= $row["judul"] ?></strong>"?</label>
                                <input type="hidden" name="id" value="<?= $row["id"] ?>">
                                <input type="hidden" name="gambar" value="<?= $row["gambar"] ?>">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">batal</button>
                            <input type="submit" value="hapus" name="hapus" class="btn btn-primary">
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- Akhir Modal Hapus -->
    </td>
</tr>
<?php }
} else {
    echo '<tr><td colspan="5" class="text-center">Belum ada data article.</td></tr>';
}
?>