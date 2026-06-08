<?php
// Konfigurasi koneksi PostgreSQL
$conn = pg_connect("host=LocalHost port=5432 dbname=perpustakaan user=postgres password=root");

if (!$conn) {
    die("Koneksi gagal: " . pg_last_error($conn));
}

// Proses form saat disubmit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama_peminjam = htmlspecialchars($_POST["nama_peminjam"]);
    $id_buku = htmlspecialchars($_POST["id_buku"]);
    $tanggal_peminjam = $_POST["tanggal_peminjam"];

    $sql = "INSERT INTO tbl_peminjam (nama_peminjam, id_buku, tanggal_peminjam)
            VALUES ('$nama_peminjam', '$id_buku', '$tanggal_peminjam')";

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
   <input type="date" name="tanggal_peminjam" required><br><br>
   <input type="submit" value="Simpan">
 </form>
</body>
</html>
