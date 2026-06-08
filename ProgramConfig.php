<?php
// Konfigurasi koneksi PostgreSQL
$conn = pg_connect("host=LocalHost port=8000 dbname=db_perpustakaan user=postgres password=w1lmaL06!?");

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


<?php
// Konfigurasi koneksi database
$conn = pg_connect(
    "host=localhost
    port=8000
    dbname=db_perpustakaan
    user=postgres
    password=w1lmaL06!?"
);

// Cek koneksi
if (!$conn) {
    die("Koneksi PostgreSQL gagal");
}
// Ambil data dari tabel
$sql = "SELECT * FROM tbl_buku ORDER BY id_buku DESC";
$result = pg_query($conn, $sql);
?>


<!DOCTYPE html>
<html>
<head>
    <title>Data Buku Perpustakaan</title>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
        }
        table, td, th {
            border: 1px solid #000;
            padding: 8px;
        }
        th {
            background-color: #eee;
        }
    </style>
</head>
<body>
    <h2>Daftar Buku Perpustakaan</h2>
    <a href="add.php">+ Tambah Buku Baru</a>
    <br><br>

    <table>
    <tr>
        <th>No</th>
        <th>id_buku</th>
        <th>judul</th>
        <th>Penulis</th>
        <th>Tahun Terbit</th>
        <th>Aksi</th>
    </tr>
 <?php
if (pg_num_rows($result) > 0) {

    $no = 1;

    while ($row = pg_fetch_assoc($result)) {

        echo "<tr>
            <td>".$no++."</td>
            <td>".$row['id_buku']."</td>
            <td>".$row['judul']."</td>
            <td>".$row['penulis']."</td>
            <td>".$row['tahun_terbit']."</td>
            <td>
                <a href='edit.php?id=".$row['id_buku']."'>Edit</a> |
                <a href='delete.php?id=".$row['id_buku']."'
                onclick=\"return confirm('Yakin ingin menghapus?')\">Hapus</a>
            </td>
        </tr>";
    }

} else {

    echo "<tr>
            <td colspan='6'>Belum ada data buku.</td>
          </tr>";
}
?>
    </table>
</body>
</html>

