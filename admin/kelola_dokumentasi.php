<?php
include '../assets/koneksi.php';

$target_dir = "../img_dokumentasi/";
if (!is_dir($target_dir)) { mkdir($target_dir, 0777, true); }

if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $cek = mysqli_query($koneksi, "SELECT foto FROM dokumentasi WHERE id=$id");
    $data = mysqli_fetch_assoc($cek);
    if(!empty($data['foto']) && file_exists($target_dir . $data['foto'])) {
        unlink($target_dir . $data['foto']);
    }
    mysqli_query($koneksi, "DELETE FROM dokumentasi WHERE id=$id");
    header("Location: kelola_dokumentasi.php");
}

if (isset($_POST['save'])) {
    $id         = $_POST['id'];
    $judul      = mysqli_real_escape_string($koneksi, $_POST['judul']);
    $tahun      = $_POST['tahun'];
    $bulan      = $_POST['bulan'];
    $tanggal    = $_POST['tanggal'];
    $minggu_ke  = $_POST['minggu_ke'];
    $link       = mysqli_real_escape_string($koneksi, $_POST['link']);
    $nama_foto  = $_POST['foto_lama'] ?? ""; 

    if (!empty($_FILES['foto']['name'])) {
        $file_name = time() . "_" . $_FILES['foto']['name'];
        if (move_uploaded_file($_FILES['foto']['tmp_name'], $target_dir . $file_name)) {
            $nama_foto = $file_name;
            if (!empty($_POST['foto_lama']) && file_exists($target_dir . $_POST['foto_lama'])) {
                unlink($target_dir . $_POST['foto_lama']);
            }
        }
    }

    if ($id == "") {
        mysqli_query($koneksi, "INSERT INTO dokumentasi (judul, tahun, bulan, tanggal, minggu_ke, link, foto) VALUES ('$judul', '$tahun', '$bulan', '$tanggal', '$minggu_ke', '$link', '$nama_foto')");
    } else {
        mysqli_query($koneksi, "UPDATE dokumentasi SET judul='$judul', tahun='$tahun', bulan='$bulan', tanggal='$tanggal', minggu_ke='$minggu_ke', link='$link', foto='$nama_foto' WHERE id=$id");
    }
    header("Location: kelola_dokumentasi.php");
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <title>Admin - Dokumentasi</title>
</head>
<body class="bg-[#0b0d17] text-white flex min-h-screen">

    <?php include 'include/nav_admin.php'; ?>

    <main class="flex-1 p-8">
        <div class="flex justify-between items-center mb-10">
            <div>
                <h1 class="text-3xl font-black uppercase tracking-tighter">Database <span class="text-blue-500">Dokumentasi</span></h1>
                <p class="text-slate-500 text-xs uppercase tracking-widest font-bold mt-1">Archive Gallery System</p>
            </div>
            <button onclick="toggleModal('modalTambah')" class="bg-blue-600 hover:bg-blue-700 px-6 py-3 rounded-2xl text-xs font-bold uppercase transition-all shadow-lg shadow-blue-600/20">
                <i class="fas fa-plus mr-2"></i> Tambah Data
            </button>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php 
            $query = mysqli_query($koneksi, "SELECT * FROM dokumentasi ORDER BY id DESC");
            while($row = mysqli_fetch_assoc($query)): 
            ?>
            <div class="bg-white/5 border border-white/10 rounded-[2.5rem] overflow-hidden group hover:border-blue-500/50 transition-all">
                <div class="h-48 overflow-hidden relative">
                    <img src="../img_dokumentasi/<?= $row['foto'] ?>" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                    <div class="absolute top-4 left-4 bg-blue-600 px-3 py-1 rounded-full text-[9px] font-black uppercase italic">Mng <?= $row['minggu_ke'] ?></div>
                </div>
                <div class="p-6">
                    <h3 class="font-black uppercase italic text-sm tracking-tight mb-1 truncate"><?= $row['judul'] ?></h3>
                    <p class="text-[10px] text-slate-500 font-bold uppercase mb-6"><?= $row['tanggal'] ?> <?= $row['bulan'] ?> <?= $row['tahun'] ?></p>
                    
                    <div class="flex gap-3">
                        <a href="<?= $row['link'] ?>" target="_blank" class="flex-1 bg-white/5 hover:bg-white/10 text-center py-3 rounded-xl text-[10px] font-black uppercase transition-all">Link Folder</a>
                        <button onclick='openEditModal(<?= json_encode($row) ?>)' class="w-10 h-10 flex items-center justify-center bg-orange-500/10 text-orange-500 rounded-xl hover:bg-orange-500 hover:text-white transition-all">
                            <i class="fas fa-edit text-xs"></i>
                        </button>
                        <a href="?delete=<?= $row['id'] ?>" onclick="return confirm('Hapus?')" class="w-10 h-10 flex items-center justify-center bg-red-500/10 text-red-500 rounded-xl hover:bg-red-500 hover:text-white transition-all">
                            <i class="fas fa-trash text-xs"></i>
                        </a>
                    </div>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
    </main>

    <div id="modalTambah" class="fixed inset-0 bg-black/80 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
        <div class="bg-[#161925] border border-white/10 w-full max-w-lg rounded-[3rem] p-8">
            <h2 class="text-xl font-black uppercase italic tracking-tighter mb-6">Tambah Dokumentasi</h2>
            <form action="" method="POST" enctype="multipart/form-data" class="space-y-4">
                <input type="hidden" name="id" value="">
                <input type="text" name="judul" placeholder="Judul Dokumentasi" class="w-full bg-black/40 border border-white/5 rounded-xl px-4 py-3 text-sm outline-none focus:border-blue-500" required>
                <div class="grid grid-cols-2 gap-4">
                    <input type="number" name="tahun" placeholder="Tahun" class="bg-black/40 border border-white/5 rounded-xl px-4 py-3 text-sm outline-none focus:border-blue-500" required>
                    <select name="bulan" class="bg-black/40 border border-white/5 rounded-xl px-4 py-3 text-sm outline-none focus:border-blue-500" required>
                        <option value="">Pilih Bulan</option>
                        <option value="Januari">Januari</option><option value="Februari">Februari</option><option value="Maret">Maret</option>
                        <option value="April">April</option><option value="Mei">Mei</option><option value="Juni">Juni</option>
                        <option value="Juli">Juli</option><option value="Agustus">Agustus</option><option value="September">September</option>
                        <option value="Oktober">Oktober</option><option value="November">November</option><option value="Desember">Desember</option>
                    </select>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <input type="number" name="tanggal" placeholder="Tanggal" class="bg-black/40 border border-white/5 rounded-xl px-4 py-3 text-sm outline-none focus:border-blue-500" required>
                    <input type="number" name="minggu_ke" placeholder="Minggu Ke" class="bg-black/40 border border-white/5 rounded-xl px-4 py-3 text-sm outline-none focus:border-blue-500" required>
                </div>
                <input type="url" name="link" placeholder="Link Google Drive" class="w-full bg-black/40 border border-white/5 rounded-xl px-4 py-3 text-sm outline-none focus:border-blue-500" required>
                <input type="file" name="foto" class="w-full bg-black/40 border border-white/5 rounded-xl px-4 py-3 text-xs text-slate-500">
                <div class="flex gap-4 pt-4">
                    <button type="button" onclick="toggleModal('modalTambah')" class="flex-1 py-4 font-black uppercase text-[10px] tracking-widest text-slate-500">Batal</button>
                    <button type="submit" name="save" class="flex-1 bg-blue-600 py-4 rounded-2xl font-black uppercase text-[10px] tracking-widest shadow-lg shadow-blue-600/20">Simpan Data</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function toggleModal(id) { document.getElementById(id).classList.toggle('hidden'); }
        function openEditModal(data) {
            const modal = document.getElementById('modalTambah');
            modal.querySelector('h2').innerText = 'Edit Dokumentasi';
            modal.querySelector('[name="id"]').value = data.id;
            modal.querySelector('[name="judul"]').value = data.judul;
            modal.querySelector('[name="tahun"]').value = data.tahun;
            modal.querySelector('[name="bulan"]').value = data.bulan;
            modal.querySelector('[name="tanggal"]').value = data.tanggal;
            modal.querySelector('[name="minggu_ke"]').value = data.minggu_ke;
            modal.querySelector('[name="link"]').value = data.link;
            modal.innerHTML += `<input type="hidden" name="foto_lama" value="${data.foto}">`;
            toggleModal('modalTambah');
        }
    </script>
</body>
</html>