<?php
session_start();
include '../assets/koneksi.php';

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['level'] !== 'pembina') {
    header("location: ../../login.php?pesan=restricted");
    exit;
}

if (isset($_GET['export']) && $_GET['export'] == 'excel') {
    header("Content-type: application/vnd-ms-excel");
    header("Content-Disposition: attachment; filename=Rekap_Absensi_JC_".date('Y-m-d').".xls");
    
    $q_ex = "SELECT a.*, f.nama_lengkap, f.bidang 
             FROM absensi a 
             JOIN frontliners f ON a.id_user = f.id_user 
             ORDER BY a.waktu_absen DESC";
    $res_ex = mysqli_query($koneksi, $q_ex);
    
    echo "<table border='1'>
            <tr style='background-color: #00df9a; color: white;'>
                <th>Waktu Absen</th>
                <th>Nama Lengkap</th>
                <th>Bidang</th>
                <th>Status Kehadiran</th>
                <th>Sesi Ibadah</th>
                <th>Sesi Pelayanan</th>
                <th>Ketepatan</th>
                <th>Rhema / Firman</th>
                <th>Ayat Referensi</th>
                <th>Keterangan Izin</th>
            </tr>";
            
    while($row = mysqli_fetch_assoc($res_ex)) {
        echo "<tr>
                <td>{$row['waktu_absen']}</td>
                <td>" . strtoupper($row['nama_lengkap']) . "</td>
                <td>{$row['bidang']}</td>
                <td>" . strtoupper($row['status_kehadiran']) . "</td>
                <td>{$row['sesi_ibadah']}</td>
                <td>Sesi {$row['sesi_pelayanan']}</td>
                <td>{$row['status_ketepatan']}</td>
                <td>" . htmlspecialchars($row['makna_rhema']) . "</td>
                <td>{$row['ayat_referensi']}</td>
                <td>{$row['keterangan_izin']}</td>
              </tr>";
    }
    echo "</table>";
    exit;
}

// 3. QUERY DATA UTAMA UNTUK TAMPILAN WEB
$query = "SELECT a.*, f.nama_lengkap, f.bidang, f.foto 
          FROM absensi a 
          JOIN frontliners f ON a.id_user = f.id_user 
          ORDER BY a.waktu_absen DESC";
$result = mysqli_query($koneksi, $query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <title>Admin - Monitoring Absensi</title>
</head>
<body class="bg-[#0b0d17] text-white flex min-h-screen font-sans">

    <?php include 'include/nav_admin.php'; ?>

    <main class="flex-1 p-8">
        <div class="flex justify-between items-center mb-10">
            <div>
                <h1 class="text-3xl font-black uppercase tracking-tighter">Attendance <span class="text-cyan-500">Center</span></h1>
                <p class="text-slate-500 text-xs uppercase tracking-widest font-bold mt-1">Full Report & Data Analysis</p>
            </div>
            <div class="flex gap-4">
                <a href="?export=excel" class="w-12 h-12 bg-green-500/10 text-green-500 rounded-2xl flex items-center justify-center text-xl hover:bg-green-500 hover:text-white transition-all shadow-lg shadow-green-500/10" title="Export to Excel">
                    <i class="fas fa-file-excel"></i>
                </a>
                <div class="w-12 h-12 bg-cyan-600/10 text-cyan-500 rounded-2xl flex items-center justify-center text-xl">
                    <i class="fas fa-clipboard-user"></i>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-6">
            <?php while($row = mysqli_fetch_assoc($result)): 
                $is_late = ($row['status_ketepatan'] == 'terlambat');
                $is_absent = ($row['status_kehadiran'] == 'tidak pelayanan');
            ?>
            <div class="bg-white/5 border border-white/10 rounded-[2.5rem] p-8 relative overflow-hidden group transition-all <?= $is_absent ? 'opacity-40 grayscale' : 'hover:border-cyan-500/50' ?>">
                
                <div class="flex justify-between items-start mb-6">
                    <div class="flex items-center gap-4">
                        <img src="../../img_users/<?= $row['foto'] ?>" class="w-10 h-10 rounded-full object-cover border border-white/10" onerror="this.src='https://ui-avatars.com/api/?name=<?= urlencode($row['nama_lengkap']) ?>&background=random'">
                        <div>
                            <h3 class="font-black uppercase italic text-sm tracking-tight text-cyan-400"><?= $row['nama_lengkap'] ?></h3>
                            <p class="text-[9px] font-bold text-slate-500 uppercase mt-1 tracking-widest"><?= $row['bidang'] ?> | Sesi Pelayanan <?= $row['sesi_pelayanan'] ?></p>
                        </div>
                    </div>
                    <div class="text-right">
                        <span class="block text-[10px] font-black <?= $is_late ? 'text-red-500' : 'text-green-500' ?> uppercase italic">
                            <?= $row['status_ketepatan'] ?>
                        </span>
                        <p class="text-[8px] font-bold text-slate-600 uppercase mt-1 tracking-tighter"><?= date('d M Y | H:i', strtotime($row['waktu_absen'])) ?></p>
                    </div>
                </div>
                
                <div class="bg-black/20 rounded-2xl p-4 mb-6 min-h-[100px] flex flex-col justify-center border border-white/5">
                    <?php if(!$is_absent): ?>
                        <p class="text-[10px] text-cyan-500 font-black uppercase mb-2 tracking-widest">Rhema Hari Ini:</p>
                        <p class="text-sm text-slate-300 leading-relaxed italic">"<?= htmlspecialchars($row['makna_rhema']) ?>"</p>
                        <p class="text-orange-500 text-[10px] font-black mt-3 uppercase tracking-widest">
                            <i class="fas fa-book-open mr-1"></i> <?= $row['ayat_referensi'] ?>
                        </p>
                    <?php else: ?>
                        <p class="text-[10px] text-red-500 font-black uppercase mb-2 tracking-widest">Laporan Ketidakhadiran:</p>
                        <p class="text-sm text-red-400 font-bold italic uppercase tracking-tighter">
                            <i class="fas fa-circle-exclamation mr-1"></i> <?= $row['keterangan_izin'] ?>
                        </p>
                    <?php endif; ?>
                </div>
                
                <div class="flex gap-4 border-t border-white/5 pt-6">
                    <div class="flex-1 bg-white/5 border border-white/5 text-center py-3 rounded-xl text-[9px] font-black uppercase tracking-widest text-slate-400">
                        Sesi Ibadah: <?= $row['sesi_ibadah'] ?>
                    </div>
                    <a href="hapus_absensi.php?id=<?= $row['id_absensi'] ?>" onclick="return confirm('Hapus data absensi ini secara permanen?')" class="w-11 h-11 flex items-center justify-center bg-red-500/10 text-red-500 rounded-xl hover:bg-red-500 hover:text-white transition-all">
                        <i class="fas fa-trash text-xs"></i>
                    </a>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
    </main>
</body>
</html>