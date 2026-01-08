
<?php 
// mulai session dan sertakan koneksi database
session_start();
include "koneksi.php";

// jika user sudah login arahkan ke admin
if (isset($_SESSION['username'])) {
    header("Location: admin.php");
    exit;
}

$error = '';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // ambil input user
    $userInput = isset($_POST['username']) ? trim($_POST['username']) : '';
    $passInput = isset($_POST['password']) ? $_POST['password'] : '';

    if ($userInput === '' || $passInput === '') {
        $error = 'Username dan Password wajib diisi.';
    } else {
        // siapkan parameter untuk pencarian di database
        $username = $userInput;
        $password = md5($passInput); // gunakan md5 supaya cocok dengan format password di DB

        // prepared statement
        $stmt = $conn->prepare("SELECT * FROM user WHERE username=? AND password=?");
        $stmt->bind_param("ss", $username, $password);
        $stmt->execute();
        $hasil = $stmt->get_result();
        $row = $hasil->fetch_array(MYSQLI_ASSOC);

        // cek apakah ada baris hasil
        if (!empty($row)) {
            // simpan username ke session dan alihkan ke halaman admin
            $_SESSION['username'] = $row['username'];
            header("Location: admin.php");
            exit;
        } else {
            $error = 'Username dan Password Salah';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        :root{ font-family: Arial, Helvetica, sans-serif; }
        body{ background:#f2f6fb; margin:0; display:flex; align-items:center; justify-content:center; height:100vh; }
        .card{ background:#fff; padding:28px; border-radius:8px; box-shadow:0 6px 18px rgba(20,40,80,0.08); width:320px; }
        .card h2{ margin:0 0 12px 0; font-size:20px; color:#203040; }
        .field{ margin-bottom:12px; }
        label{ display:block; font-size:13px; color:#405060; margin-bottom:6px; }
        input[type="text"], input[type="password"]{ width:100%; padding:10px 12px; border:1px solid #d7e0ef; border-radius:6px; box-sizing:border-box; }
        .btn{ width:100%; padding:10px 12px; background:#2b7cff; color:#fff; border:0; border-radius:6px; cursor:pointer; font-weight:600; }
        .error{ background:#ffe6e6; color:#b20000; padding:8px 10px; border-radius:6px; margin-bottom:12px; font-size:14px; }
        .small{ font-size:13px; color:#6b7280; text-align:center; margin-top:12px; }
        a.link{ color:#2b7cff; text-decoration:none }
    </style>
</head>
<body>
    <div class="card">
        <h2>Masuk ke Akun</h2>

        <?php if ($error !== ''): ?>
            <div class="error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <form method="post" autocomplete="off">
            <div class="field">
                <label for="username">Username</label>
                <input id="username" type="text" name="username" value="<?php echo isset($userInput) ? htmlspecialchars($userInput) : ''; ?>" required>
            </div>

            <div class="field">
                <label for="password">Password</label>
                <input id="password" type="password" name="password" required>
            </div>

            <div class="field">
                <button class="btn" type="submit">Login</button>
            </div>
        </form>

        
    </div>
</body>
</html>
