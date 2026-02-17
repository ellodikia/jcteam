<?php
include "assets/koneksi.php"; // Pastikan path koneksi benar

echo "<h2>🚀 Memulai Generate Data Dummy...</h2>";

// 1. DATA OPEN MINISTRY (Sesuai Permintaan)
$ministries = [
    ['Worship Leader', 'Melayani sebagai pemimpin pujian dalam ibadah raya.'],
    ['Music', 'Melayani sebagai pemusik (Keyboard, Guitar, Bass, Drum, dll).'],
    ['Dancer', 'Melayani Tuhan melalui tarian kontemporer dan prophetik.'],
    ['Multimedia', 'Mengoperasikan slide lirik, obs, dan visual selama ibadah.'],
    ['Soundman', 'Bertanggung jawab atas kualitas audio di dalam ruangan.'],
    ['Usher', 'Menyambut jemaat dengan kasih dan mengatur ketertiban.'],
    ['Prayer', 'Tim pendoa syafaat yang berdiri bagi jiwa-jiwa.'],
    ['Documentation', 'Mengambil momen ibadah melalui foto dan video.']
];

mysqli_query($koneksi, "TRUNCATE TABLE open_ministry"); // Bersihkan data lama
foreach ($ministries as $m) {
    $bidang = $m[0];
    $desc = $m[1];
    mysqli_query($koneksi, "INSERT INTO open_ministry (bidang, deskripsi, status) VALUES ('$bidang', '$desc', 'buka')");
}
echo "✅ 8 Bidang Open Ministry berhasil dibuat.<br>";

// 2. DATA USER (Password: password123)
$password = password_hash('password123', PASSWORD_DEFAULT);
mysqli_query($koneksi, "DELETE FROM users WHERE username IN ('pembina_dummy', 'front_dummy')");
mysqli_query($koneksi, "INSERT INTO users (username, password, level) VALUES 
    ('pembina_dummy', '$password', 'pembina'),
    ('front_dummy', '$password', 'frontliner')");
    
$id_pembina = mysqli_insert_id($koneksi) - 1;
$id_front = mysqli_insert_id($koneksi);
echo "✅ User Dummy berhasil dibuat (User: front_dummy, Pass: password123).<br>";

// 3. DATA PROFIL
mysqli_query($koneksi, "INSERT INTO admin_pembina (id_user, nama_lengkap, bidang) VALUES ($id_pembina, 'Budi Pembina', 'All Departments')");
mysqli_query($koneksi, "INSERT INTO frontliners (id_user, nama_lengkap, bidang) VALUES ($id_front, 'Andi Frontliner', 'Multimedia')");
echo "✅ Profil Admin & Frontliner berhasil dibuat.<br>";

// 4. DATA EVENT & DOKUMENTASI
mysqli_query($koneksi, "INSERT INTO event (judul, link, foto) VALUES ('Gathering Frontliner 2026', '#', 'event.jpg')");
mysqli_query($koneksi, "INSERT INTO dokumentasi (judul, tahun, bulan, tanggal, minggu_ke, link, foto) VALUES 
    ('Ibadah Minggu 1', 2026, 'Februari', '2026-02-01', 1, '#', 'm1.jpg')");
echo "✅ Event & Dokumentasi berhasil dibuat.<br>";

// 5. DATA ABSENSI (Dummy Laporan)
mysqli_query($koneksi, "INSERT INTO absensi (id_user, status_kehadiran, sesi_ibadah, status_ketepatan, makna_rhema, ayat_referensi, sesi_pelayanan) VALUES 
    ($id_front, 'pelayanan', 'SESI 1 (08.00)', 'tepat waktu', 'Tuhan sangat baik dalam hidupku.', 'Mazmur 23:1', 1)");
echo "✅ Riwayat Absensi Dummy berhasil dibuat.<br>";

echo "<br><b>🎉 Semua data berhasil di-generate!</b>";
?>