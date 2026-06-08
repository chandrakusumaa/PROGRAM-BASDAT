<?php
// Konfigurasi koneksi PostgreSQL
$conn = pg_connect("host=LocalHost port=5432 dbname=perpustakaan user=postgres password=root");

if (!$conn) {
    die("Koneksi PostgreSQL gagal");
}

// Proses form saat disubmit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama_peminjam = htmlspecialchars($_POST["nama_peminjam"]);
    $id_buku = htmlspecialchars($_POST["id_buku"]);
    $tanggal_pinjam = $_POST["tanggal_pinjam"];

    $sql = "INSERT INTO tbl_peminjaman (nama_peminjam, id_buku, tanggal_pinjam)
            VALUES ('$nama_peminjam', '$id_buku', '$tanggal_pinjam')";

    $result = pg_query($conn, $sql);

    if ($result) {
        echo "<p style='color:green;'>Data berhasil disimpan!</p>";
    } else {
        echo "<p style='color:red;'>Error: " . pg_last_error($conn) . "</p>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
 <title>Peminjaman Buku Perpustakaan</title>
</head>
<body>
 <h2>Form Tambah Peminjam</h2>
 <form method="POST" action="">
   <label>Nama:</label><br>
   <input type="text" name="nama_peminjam" required><br><br>
   <label>Id Buku:</label><br>
   <input type="number" name="id_buku" required><br><br>
   <label>Tanggal Pinjam:</label><br>
   <input type="date" name="tanggal_pinjam" required><br><br>
   <input type="submit" value="Simpan">
 </form>
</body>

<?php

// Data Buku
$sql_buku = "SELECT * FROM tbl_buku ORDER BY id_buku DESC";
$result_buku = pg_query($conn, $sql_buku);

// Data Peminjaman
$sql_pinjam = "
SELECT p.id_pinjam,
       p.nama_peminjam,
       b.judul,
       p.tanggal_pinjam
FROM tbl_peminjaman p
JOIN tbl_buku b ON p.id_buku = b.id_buku
ORDER BY p.id_pinjam DESC";
$result_pinjam = pg_query($conn, $sql_pinjam);
?>

<head>
    <title>Perpustakaan</title>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
            margin-bottom: 20px;
        }

        table, td, th {
            border: 1px solid black;
            padding: 8px;
        }

        th {
            background-color: #eee;
        }
    </style>
</head>
<body>

    <h2>Daftar Buku Perpustakaan</h2>

    <table>
        <tr>
            <th>No</th>
            <th>ID Buku</th>
            <th>Judul</th>
            <th>Penulis</th>
            <th>Tahun Terbit</th>
        </tr>

        <?php
        if (pg_num_rows($result_buku) > 0) {

            $no = 1;

            while ($row = pg_fetch_assoc($result_buku)) {

                echo "<tr>
                    <td>".$no++."</td>
                    <td>".$row['id_buku']."</td>
                    <td>".$row['judul']."</td>
                    <td>".$row['penulis']."</td>
                    <td>".$row['tahun_terbit']."</td>
                </tr>";
            }

        } else {

            echo "<tr>
                    <td colspan='6'>Belum ada data buku.</td>
                  </tr>";
        }
        ?>
    </table>

    <h2>Data Peminjaman Buku</h2>

    <table>
        <tr>
            <th>ID Pinjam</th>
            <th>Nama Peminjam</th>
            <th>Judul Buku</th>
            <th>Tanggal Pinjam</th>
        </tr>

        <?php
        if (pg_num_rows($result_pinjam) > 0) {

            while ($row = pg_fetch_assoc($result_pinjam)) {

                echo "<tr>
                    <td>".$row['id_pinjam']."</td>
                    <td>".$row['nama_peminjam']."</td>
                    <td>".$row['judul']."</td>
                    <td>".$row['tanggal_pinjam']."</td>
                </tr>";
            }

        } else {

            echo "<tr>
                    <td colspan='4'>Belum ada data peminjaman.</td>
                  </tr>";
        }
        ?>
    </table>

</body>
</html>

<?php
// Koneksi ke PostgreSQL
$conn = pg_connect("host=LocalHost port=5432 dbname=perpustakaan user=postgres password=root");

if (!$conn) {
    die("Koneksi PostgreSQL gagal");
}

// Kalau form update disubmit
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update'])) {
    $id_pinjam = intval($_POST["id_pinjam"]);
    $nama_peminjam = htmlspecialchars($_POST['nama_peminjam']);
    $id_buku = intval($_POST['id_buku']);
    $tanggal_pinjam = $_POST['tanggal_pinjam'];

    $update = "UPDATE tbl_peminjaman SET
        nama_peminjam = '$nama_peminjam',
        id_buku = '$id_buku',
        tanggal_pinjam = '$tanggal_pinjam'
        WHERE id_pinjam = $id_pinjam";

    $result = pg_query($conn, $update);

    if ($result) {
        echo "<p style='color:green;'>Data berhasil diperbarui!</p>";
        echo "<meta http-equiv='refresh' content='1;url=view.php'>";
    } else {
        echo "<p style='color:red;'>Gagal memperbarui data: " . pg_last_error($conn) . "</p>";
    }
}

// Ambil semua data dari tbl_peminjaman
$query = "SELECT * FROM tbl_peminjaman ORDER BY id_pinjam ASC";
$result = pg_query($conn, $query);
?>

<!DOCTYPE html>
<html>
<head>
 <title>Edit Peminjaman</title>
</head>
<body>
 <h2 style="text-align:center;">Daftar Peminjaman</h2>
 <table border="1" cellpadding="8" align="center">
     <tr>
         <th>No</th>
         <th>id_pinjam</th>
         <th>nama_peminjam</th>
         <th>id_buku</th>
         <th>tanggal_pinjam</th>
     </tr>
     <?php
     $no = 1;
     while ($row = pg_fetch_assoc($result)) {
         echo "<tr>";
         echo "<td>".$no++."</td>";
         echo "<td>".$row['id_pinjam']."</td>";
         echo "<td>".$row['nama_peminjam']."</td>";
         echo "<td>".$row['id_buku']."</td>";
         echo "<td>".$row['tanggal_pinjam']."</td>";
         echo "</tr>";
     }
     ?>
 </table>

 <h2>Edit Data Peminjaman</h2>
 <form method="POST" action="">
   <label>ID Pinjam:</label><br>
   <input type="number" name="id_pinjam" required><br><br>
   <label>Nama:</label><br>
   <input type="text" name="nama_peminjam" required><br><br>
   <label>Id Buku:</label><br>
   <input type="number" name="id_buku" required><br><br>
   <label>Tanggal Pinjam:</label><br>
   <input type="date" name="tanggal_pinjam" required><br><br>
   <input type="submit" name="update" value="Update">
   <a href="view.php">Batal</a>
 </form>
</body>
</html>

<?php
// Konfigurasi koneksi database
$conn = pg_connect("host=LocalHost port=5432 dbname=perpustakaan user=postgres password=root");

if (!$conn) {
    die("Koneksi PostgreSQL gagal");
}

// Proses hapus data saat form disubmit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_pinjam = intval($_POST["id_pinjam"]);
    $sql = "DELETE FROM tbl_peminjaman WHERE id_pinjam = $id_pinjam";
    $result = pg_query($conn, $sql);

    if ($result) {
        echo "<p style='color:green;'>Data dengan ID $id_pinjam berhasil dihapus!</p>";
        echo "<meta http-equiv='refresh' content='2;url=view.php'>";
    } else {
        echo "<p style='color:red;'>Gagal menghapus data: " . pg_last_error($conn) . "</p>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
 <title>Hapus Peminjaman</title>
</head>
<body>
 <h2>Hapus Data Peminjaman</h2>
 <form method="POST" action="">
   <label>Masukkan ID Pinjam yang ingin dihapus:</label><br>
   <input type="number" name="id_pinjam" required><br><br>
   <input type="submit" value="Hapus">
 </form>
</body>
</html>

