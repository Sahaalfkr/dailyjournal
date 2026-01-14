<?php
// User hanya bisa akses profile miliknya sendiri
$username = $_SESSION['username'];
$stmt = $conn->prepare("SELECT id, username, foto FROM user WHERE username=?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    echo '<div class="alert alert-danger">Data user tidak ditemukan</div>';
    exit;
}

// Make upload helper available before it's potentially called
include "upload_foto.php";

// Proses update profile
if (isset($_POST['update'])) {
    $username_baru = $_POST['username'];
    $password = $_POST['password'];
    $password_confirm = $_POST['password_confirm'];
    $nama_foto = $_FILES['foto']['name'];
    $foto_lama = $_POST['foto_lama'];
    $foto = $foto_lama; // Default gunakan foto lama

    // Validasi username tidak kosong
    if (empty($username_baru)) {
        echo "<script>
            alert('Username tidak boleh kosong');
            document.location='admin.php?page=profile';
        </script>";
        die;
    }

    // Validasi jika password diisi
    if (!empty($password)) {
        if ($password !== $password_confirm) {
            echo "<script>
                alert('Konfirmasi password tidak cocok');
                document.location='admin.php?page=profile';
            </script>";
            die;
        }
        if (strlen($password) < 6) {
            echo "<script>
                alert('Password minimal 6 karakter');
                document.location='admin.php?page=profile';
            </script>";
            die;
        }
    }

    // Jika ada upload foto baru
    if ($nama_foto != '') {
        // Panggil function upload_foto untuk cek file
        $cek_upload = upload_foto($_FILES["foto"]);

        if ($cek_upload['status']) {
            $foto = $cek_upload['message'];
            // Hapus foto lama jika ada
            if ($foto_lama != '' && file_exists("img/" . $foto_lama)) {
                unlink("img/" . $foto_lama);
            }
        } else {
            echo "<script>
                alert('" . $cek_upload['message'] . "');
                document.location='admin.php?page=profile';
            </script>";
            die;
        }
    }

    // Update profile dengan username baru
    if (!empty($password)) {
        // Update username, password, dan foto
        $password_hash = md5($password);
        $stmt = $conn->prepare("UPDATE user SET username=?, password=?, foto=? WHERE id=?");
        $stmt->bind_param("sssi", $username_baru, $password_hash, $foto, $user['id']);
    } else {
        // Update username dan foto saja (password tidak diubah)
        $stmt = $conn->prepare("UPDATE user SET username=?, foto=? WHERE id=?");
        $stmt->bind_param("ssi", $username_baru, $foto, $user['id']);
    }

    $update = $stmt->execute();

    if ($update) {
        // Update session dengan username baru
        $_SESSION['username'] = $username_baru;
        echo "<script>
            alert('Update profil sukses');
            document.location='admin.php?page=profile';
        </script>";
    } else {
        echo "<script>
            alert('Update profil gagal');
            document.location='admin.php?page=profile';
        </script>";
    }

    $stmt->close();
}
?>

<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <h3 class="mb-4">profile</h3>
            
            <form method="post" enctype="multipart/form-data" id="profileForm">
                <!-- Username (Editable) -->
                <div class="mb-4">
                    <label for="username" class="form-label fw-bold">Username</label>
                    <input type="text" class="form-control" id="username" name="username" value="<?php echo htmlspecialchars($user['username']) ?>" required>
                </div>

                <!-- Password -->
                <div class="mb-4">
                    <label for="password" class="form-label fw-bold">Ganti Password</label>
                    <input type="password" class="form-control" id="password" name="password" placeholder="Tuliskan Password Baru Jika Ingin Mengganti Password Saya">
                    <small class="text-muted">Minimal 6 karakter. Kosongkan jika tidak ingin mengubah.</small>
                </div>

                <!-- Confirm Password -->
                <div class="mb-4">
                    <label for="password_confirm" class="form-label fw-bold">Konfirmasi Password</label>
                    <input type="password" class="form-control" id="password_confirm" name="password_confirm" placeholder="Ketik ulang password baru">
                    <small class="text-danger" id="passwordError" style="display:none;">Password tidak cocok!</small>
                </div>

                <!-- Foto Profil Upload -->
                <div class="mb-4">
                    <label for="foto" class="form-label fw-bold">Ganti Foto Profil</label>
                    <input type="file" class="form-control" id="foto" name="foto" accept="image/*">
                    <small class="text-muted">Format: JPG, PNG, GIF (Max: 5MB). Kosongkan jika tidak ingin mengubah.</small>
                </div>

                <!-- Current Photo Display -->
                <div class="mb-4">
                    <label class="form-label fw-bold">Foto Profil Saat Ini</label><br>
                    <?php
                    $foto_val = isset($user['foto']) ? $user['foto'] : '';
                    $foto_path = $foto_val ? 'img/' . $foto_val : '';
                    if ($foto_val && file_exists($foto_path)):
                    ?>
                        <img src="<?php echo $foto_path ?>" alt="Profil" style="width: 200px; height: 200px; border-radius: 8px; object-fit: cover; border: 2px solid #ddd;">
                        <div class="mt-2"><small class="text-muted">Nama file: <?php echo htmlspecialchars($foto_val) ?></small></div>
                    <?php else: ?>
                        <img src="https://via.placeholder.com/200" alt="Profil" style="width: 200px; height: 200px; border-radius: 8px; object-fit: cover; border: 2px solid #ddd;">
                        <div class="mt-2">
                            <?php if ($foto_val): ?>
                                <small class="text-warning">File "<?php echo htmlspecialchars($foto_val) ?>" tidak ditemukan di folder <code>img/</code>.</small>
                            <?php else: ?>
                                <small class="text-muted">Belum ada foto profil.</small>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Hidden input for old photo -->
                <input type="hidden" name="foto_lama" value="<?php echo htmlspecialchars($user['foto']) ?>">

                <!-- Button -->
                <div class="mb-4">
                    <button type="submit" name="update" class="btn btn-primary" id="submitBtn">simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Validasi password saat diisi
    document.getElementById('password').addEventListener('input', function() {
        validatePassword();
    });
    
    document.getElementById('password_confirm').addEventListener('input', function() {
        validatePassword();
    });

    function validatePassword() {
        const password = document.getElementById('password').value;
        const passwordConfirm = document.getElementById('password_confirm').value;
        const errorMsg = document.getElementById('passwordError');

        // Jika password diisi
        if (password !== '') {
            // Cek kesesuaian password
            if (passwordConfirm !== password) {
                errorMsg.style.display = 'block';
                errorMsg.textContent = 'Password tidak cocok!';
            } else {
                errorMsg.style.display = 'none';
            }
        } else {
            // Password kosong
            errorMsg.style.display = 'none';
        }
    }

    // Form submit validation
    document.getElementById('profileForm').addEventListener('submit', function(e) {
        const password = document.getElementById('password').value;
        const passwordConfirm = document.getElementById('password_confirm').value;

        // Jika password diisi, harus cocok
        if (password !== '' && password !== passwordConfirm) {
            e.preventDefault();
            alert('Konfirmasi password tidak cocok!');
            return false;
        }

        // Jika password diisi, harus minimal 6 karakter
        if (password !== '' && password.length < 6) {
            e.preventDefault();
            alert('Password minimal 6 karakter!');
            return false;
        }
    });
</script>

<?php
$stmt->close();
$conn->close();
?>
