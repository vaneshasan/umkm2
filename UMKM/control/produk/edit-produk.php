<?php
session_start();

// Include file koneksi, untuk menghubungkan ke database
include '../../config/koneksi.php';

// Fungsi untuk mencegah inputan karakter yang tidak sesuai
function input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Ambil data produk untuk diedit
if (isset($_GET["produk"])) {
    $id_produk = input($_GET["produk"]);
    
    $query = mysqli_prepare($koneksi, "SELECT * FROM produk WHERE id_produk = ?");
    mysqli_stmt_bind_param($query, 'i', $id_produk);
    mysqli_stmt_execute($query);
    $result = mysqli_stmt_get_result($query);
    $data = mysqli_fetch_assoc($result);
}

// Proses update produk
if (isset($_POST['update_produk'])) {
    // Cek apakah ada kiriman form dari method post
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $id_produk = input($_POST["id_produk"]);
        $nama_produk = input($_POST["nama_produk"]);
        $harga = input($_POST["harga"]);
        $id_kategori = input($_POST["id_kategori"]);
        $gambar_saat_ini = input($_POST['gambar_saat_ini']);
        $gambar_baru = $_FILES['gambar_baru']['name'];
        $ekstensi_diperbolehkan = array('png', 'jpg', 'jpeg', 'gif');
        $x = explode('.', $gambar_baru);
        $ekstensi = strtolower(end($x));
        $file_tmp = $_FILES['gambar_baru']['tmp_name'];
        $id_user = $_SESSION["id_user"];

        // SQL update produk
        if (!empty($gambar_baru)) {
            if (in_array($ekstensi, $ekstensi_diperbolehkan) === true) {
                $upload_dir = '../../aset/images/';
                $upload_file = $upload_dir . basename($gambar_baru);

                if (move_uploaded_file($file_tmp, $upload_file)) {
                    // Menghapus gambar lama, jika bukan gambar default
                    if ($gambar_saat_ini != 'gambar_default.png') {
                        @unlink($upload_dir . $gambar_saat_ini);
                    }
                    
                    // Update dengan gambar baru
                    $sql = "UPDATE produk SET
                            nama_produk = ?,
                            harga = ?,
                            gambar = ?,
                            id_kategori = ?,
                            id_user = ?
                            WHERE id_produk = ?";
                    $stmt = mysqli_prepare($koneksi, $sql);
                    mysqli_stmt_bind_param($stmt, 'ssiii', $nama_produk, $harga, $gambar_baru, $id_kategori, $id_user, $id_produk);
                } else {
                    echo "Gagal mengunggah gambar baru.";
                    exit;
                }
            } else {
                echo "Ekstensi file tidak diperbolehkan. Harap unggah file dengan ekstensi png, jpg, jpeg, atau gif.";
                exit;
            }
        } else {
            // Update tanpa mengganti gambar
            $sql = "UPDATE produk SET
                    nama_produk = ?,
                    harga = ?,
                    id_kategori = ?,
                    id_user = ?
                    WHERE id_produk = ?";
            $stmt = mysqli_prepare($koneksi, $sql);
            mysqli_stmt_bind_param($stmt, 'ssiii', $nama_produk, $harga, $id_kategori, $id_user, $id_produk);
        }

        // Mengeksekusi query
        if (mysqli_stmt_execute($stmt)) {
            header("Location: ../../view/penjual/index.php?update=berhasil");
        } else {
            echo 'Query Error: ' . mysqli_error($koneksi);
            header("Location: ../../view/penjual/index.php?update=gagal");
        }
        
        mysqli_stmt_close($stmt);
    }
}

// Ambil daftar kategori untuk dropdown
$kategori_query = mysqli_query($koneksi, "SELECT * FROM kategori ORDER BY id_kategori ASC");
$kategori_options = "";
while ($kt = mysqli_fetch_assoc($kategori_query)) {
    $kategori_options .= "<option value='" . htmlspecialchars($kt['id_kategori']) . "'";
    if ($kt['id_kategori'] == $data['id_kategori']) {
        $kategori_options .= " selected";
    }
    $kategori_options .= ">" . htmlspecialchars($kt['nama_kategori']) . "</option>";
}

// Tutup koneksi
mysqli_close($koneksi);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <title>Edit Produk</title>
</head>
<body>
    <div class="container mt-5">
        <h2 class="mb-4">Edit Produk</h2>
        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) . '?produk=' . urlencode($id_produk); ?>" method="post" enctype="multipart/form-data">
            <input type="hidden" name="id_produk" value="<?= htmlspecialchars($data['id_produk']); ?>">
            <input type="hidden" name="gambar_saat_ini" value="<?= htmlspecialchars($data['gambar']); ?>">
            <div class="form-group">
                <label for="nama_produk">Nama Produk:</label>
                <input type="text" class="form-control" id="nama_produk" name="nama_produk" value="<?= htmlspecialchars($data['nama_produk']); ?>" required>
            </div>
            <div class="form-group">
                <label for="harga">Harga :</label>
                <input type="text" class="form-control" id="harga" name="harga" value="<?= htmlspecialchars($data['harga']); ?>" required>
            </div>
            <div class="form-group">
                <label for="id_kategori">Kategori:</label>
                <select name="id_kategori" class="form-control" required>
                    <?= $kategori_options; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="gambar_baru">Pilih Gambar Baru (opsional):</label>
                <input type="file" class="form-control-file" id="gambar_baru" name="gambar_baru" accept=".png, .jpg, .jpeg, .gif">
                <img src="../../aset/images/<?= htmlspecialchars($data['gambar']); ?>" alt="Gambar Produk" width="100" class="mt-2">
            </div>
            <button type="submit" name="update_produk" class="btn btn-secondary btn-sm">Update Produk</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
