<?php
session_start();
include('../../config/koneksi.php'); // Koneksi ke database
if (!isset($_SESSION['username'])) {
    header("Location: ../../login.php");
    exit();
}

// Pastikan pengguna sudah login dan id_user tersimpan dalam sesi
if (isset($_SESSION['id_user'])) {
    $id_user = $_SESSION['id_user'];

    // Periksa apakah ada parameter pencarian dan filter
    $search = isset($_GET['search']) ? '%' . $_GET['search'] . '%' : '%';
    $filter_kategori = isset($_GET['filter_kategori']) ? $_GET['filter_kategori'] : '';

    // Siapkan pernyataan SQL
    $sql = "SELECT * FROM produk WHERE id_user = ? AND nama_produk LIKE ?";
    if ($filter_kategori) {
        $sql .= " AND id_kategori = ?";
    }

    $stmt = mysqli_prepare($koneksi, $sql);

    if ($stmt) {
        // Bind parameter
        if ($filter_kategori) {
            mysqli_stmt_bind_param($stmt, "isi", $id_user, $search, $filter_kategori);
        } else {
            mysqli_stmt_bind_param($stmt, "is", $id_user, $search);
        }

        // Jalankan pernyataan
        mysqli_stmt_execute($stmt);

        // Ambil hasil
        $result = mysqli_stmt_get_result($stmt);

        // Mulai HTML
        ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Product Catalog</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css">
    <style>
    .container {
        margin-top: 50px;
    }

    .header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }

    .input-group {
        width: 100%;
    }

    .custom-input {
        width: 80px;
    }

    @media (min-width: 992px) {
        .custom-input {
            width: auto;
        }
    }

    .product-grid {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
    }

    .product-card {
        flex: 1 1 calc(25% - 20px);
        box-sizing: border-box;
        max-width: calc(25% - 20px);
    }

    .product-card img {
        width: 100%;
        height: auto;
    }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>Admin Dashboard - Product Catalog</h1>
            <div>
                <a href="../../control/produk/tambah-produk.php"><button class="btn btn-primary">Add Product</button></a>
                <a href="../../control/auth/logout.php" class="btn btn-danger">Log Out</a>
            </div>
        </div>
        <form action="index.php" method="GET">
            <div class="input-group mb-4">
                <input type="search" name="search" class="form-control rounded custom-input" placeholder="Search"
                    aria-label="Search" aria-describedby="search-addon" />
                <div class="input-group-append">
                    <button type="submit" class="btn btn-outline-primary">Search</button>
                </div>
            </div>
            <div class="form-group">
                <label for="filter_kategori">Filter Kategori:</label>
                <select name="filter_kategori" class="form-control" id="filter_kategori">
                    <option value="">Semua Kategori</option>
                    <option value="1" <?php if ($filter_kategori === '1') echo 'selected'; ?>>HP</option>
                    <option value="2" <?php if ($filter_kategori === '2') echo 'selected'; ?>>Jam</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Terapkan Filter</button>
        </form>
        <div class="product-grid">
            <?php
            // Loop hasil dan tampilkan dalam format HTML
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    echo '
                    <div class="product-card card">
                        <img src="../../aset/images/' . htmlspecialchars($row['gambar']) . '" class="card-img-top" alt="Product Image">
                        <div class="card-body">
                            <h5 class="card-title">' . htmlspecialchars($row['nama_produk']) . '</h5>
                            <p class="card-text">$' . htmlspecialchars($row['harga']) . '</p>
                            <a href="../../control/produk/edit-produk.php?produk=' . urlencode($row['id_produk']) . '" class="btn btn-warning">Edit</a>
                            <a href="../../control/produk/hapus-produk.php?produk=' . urlencode($row['id_produk']) . '" class="btn btn-danger" onclick="return confirm(\'Anda yakin ingin menghapus produk ini?\')">Delete</a>
                        </div>
                    </div>';
                }
            } else {
                echo '<div class="alert alert-warning" role="alert">Tidak ada produk yang ditemukan.</div>';
            }
            ?>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>

</html>

<?php
        // Tutup pernyataan
        mysqli_stmt_close($stmt);
    } else {
        echo "Gagal menyiapkan pernyataan.";
    }
} else {
    echo "Pengguna belum login.";
}

// Tutup koneksi
mysqli_close($koneksi);
?>
