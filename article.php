<div class="container">
    <!-- Search Box -->
    <div class="row mb-2">
        <div class="col-md-6">
         <!-- Button tambah data -->
            <button type="button" class="btn btn-secondary mb-2" data-bs-toggle="modal" data-bs-target="#modalTambah">
                <i class="bi bi-plus-lg"></i> Tambah Article
            </button>
        <!-- End Button tambah data -->
        </div>
        <div class="col-md-6">
            <!-- Search input -->
        	<div class="input-group">
                <input type="text" id="search" class="form-control" placeholder="Ketikan minimal 3 karakter untuk pencarian...">
                <span class="input-group-text">
                    <i class="bi bi-search"></i>
                </span>
            </div>
            <!-- End Search input -->
        </div>
    </div>
    <div class="row">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>No</th>
                        <th>Judul</th>
                        <th>Isi</th>
                        <th style="width: 200px;">Gambar</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody id="result">
                    
                </tbody>
            </table>
        </div>
        <!-- modal tambah data begin -->
        <div class="modal fade" id="modalTambah" tabindex="-1" aria-labelledby="modalTambahLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="modalTambahLabel">Modal title</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
            <form method="post" action="" enctype="multipart/form-data">
                <div class="modal-body">
                   	<div class="mb-3">
						<label for="judul" class="form-label">Judul</label>
                        <input type="text" class="form-control" name="judul" placeholder="Tuliskan Judul Artikel" required>
                    </div>
                    <div class="mb-3">
                        <label for="isi">Isi</label>
                        <textarea class="form-control" placeholder="Tuliskan Isi Artikel" name="isi" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="gambar" class="form-label">Gambar</label>
                        <input type="file" class="form-control" name="gambar">
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
        <!-- modal tambah data end -->
    </div>
</div>

<script>
    function loadData(keyword = '') {
        $.ajax({
            url: "article_search.php",
            type: "POST",
            data: {
                keyword: keyword
            },
            success: function(data) {
                $("#result").html(data);
            }
        });
    }

    // load awal
    loadData();
        // event pencarian
    $("#search").on("keyup", function() {
        let keyword = $(this).val();
        if (keyword.length >= 3 || keyword.length == 0) {
            loadData(keyword);
        }
    });
</script>

<?php
include "upload_foto.php";

//jika tombol simpan diklik
if (isset($_POST['simpan'])) {
    $judul = $_POST['judul'];
    $isi = $_POST['isi'];
    $tanggal = date("Y-m-d H:i:s");
    $username = $_SESSION['username'];
    $gambar = '';
    $nama_gambar = $_FILES['gambar']['name'];

    //jika ada file baru yang dikirim  
    if ($nama_gambar != '') {
        //panggil function upload_foto untuk cek detail file yg diupload user
        //function ini memiliki keluaran sebuah array yang berisi status dan message
        $cek_upload = upload_foto($_FILES["gambar"]);

        //cek status upload file hasilnya true/false
        if ($cek_upload['status']) {
            //jika true maka message berisi nama file gambar
            $gambar = $cek_upload['message'];
        } else {
            //jika true maka message berisi pesan error, tampilkan dalam alert
            echo "<script>
                alert('" . $cek_upload['message'] . "');
                document.location='admin.php?page=article';
            </script>";
            die;
        }
    }

    		//cek apakah ada id yang dikirimkan dari form
    if (isset($_POST['id'])) {
        //jika ada id, lakukan update data dengan id tersebut
        $id = $_POST['id'];

        if ($nama_gambar == '') {
            //jika tidak ganti gambar
            $gambar = $_POST['gambar_lama'];
        } else {
            //jika ganti gambar, hapus gambar lama
            unlink("img/" . $_POST['gambar_lama']);
        }

        $stmt = $conn->prepare("UPDATE article 
                                SET 
                                judul =?,
                                isi =?,
                                gambar = ?,
                                tanggal = ?,
                                username = ?
                                WHERE id = ?");

        $stmt->bind_param("sssssi", $judul, $isi, $gambar, $tanggal, $username, $id);
        $simpan = $stmt->execute();
    } else {
		    //jika tidak ada id, lakukan insert data baru
        $stmt = $conn->prepare("INSERT INTO article (judul,isi,gambar,tanggal,username)
                                VALUES (?,?,?,?,?)");

        $stmt->bind_param("sssss", $judul, $isi, $gambar, $tanggal, $username);
        $simpan = $stmt->execute();
    }
    

    if ($simpan) {
        echo "<script>
            alert('Simpan data sukses');
            document.location='admin.php?page=article';
        </script>";
    } else {
        echo "<script>
            alert('Simpan data gagal');
            document.location='admin.php?page=article';
        </script>";
    }

    $stmt->close();
    $conn->close();
}
 
//jika tombol hapus diklik
if (isset($_POST['hapus'])) {
    $id = $_POST['id'];
    $gambar = $_POST['gambar'];

    if ($gambar != '') {
        //hapus file gambar dari folder /img
        unlink("img/" . $gambar);
    }

    $stmt = $conn->prepare("DELETE FROM article WHERE id =?");

    $stmt->bind_param("i", $id);
    $hapus = $stmt->execute();

    if ($hapus) {
        echo "<script>
            alert('Hapus data sukses');
            document.location='admin.php?page=article';
        </script>";
    } else {
        echo "<script>
            alert('Hapus data gagal');
            document.location='admin.php?page=article';
        </script>";
    }

    $stmt->close();
    $conn->close();
}
?>
