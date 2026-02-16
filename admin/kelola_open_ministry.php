<?php
include '../assets/koneksi.php';
$target_dir = "../img_open_ministry/";
if (!is_dir($target_dir)) { mkdir($target_dir, 0777, true); }

if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $cek = mysqli_query($koneksi, "SELECT foto FROM open_ministry WHERE id=$id");
    $data = mysqli_fetch_assoc($cek);
    if(!empty($data['foto']) && $data['foto'] != 'default_ministry.jpg' && file_exists($target_dir . $data['foto'])) { unlink($target_dir . $data['foto']); }
    mysqli_query($koneksi, "DELETE FROM open_ministry WHERE id=$id");
    header("Location: kelola_open_ministry.php");
}

if (isset($_POST['save'])) {
    $id         = $_POST['id'];
    $bidang     = mysqli_real_escape_string($koneksi, $_POST['bidang']);
    $deskripsi  = mysqli_real_escape_string($koneksi, $_POST['deskripsi']);
    $status     = $_POST['status'];
    $nama_foto  = $_POST['foto_lama'] ?? "default_ministry.jpg"; 

    if (!empty($_FILES['foto']['name'])) {
        $new_name = "ministry_" . time() . "_" . $_FILES['foto']['name'];
        if (move_uploaded_file($_FILES['foto']['tmp_name'], $target_dir . $new_name)) {
            $nama_foto = $new_name;
            if (!empty($_POST['foto_lama']) && $_POST['foto_lama'] != 'default_ministry.jpg' && file_exists($target_dir . $_POST['foto_lama'])) { unlink($target_dir . $_POST['foto_lama']); }
        }
    }

    if ($id == "") {
        mysqli_query($koneksi, "INSERT INTO open_ministry (bidang, deskripsi, status, foto) VALUES ('$bidang', '$deskripsi', '$status', '$nama_foto')");
    } else {
        mysqli_query($koneksi, "UPDATE open_ministry SET bidang='$bidang', deskripsi='$deskripsi', status='$status', foto='$nama_foto' WHERE id=$id");
    }
    header("Location: kelola_open_ministry.php");
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <title>Admin - Open Ministry</title>
</head>
<body class="bg-[#0b0d17] text-white flex min-h-screen">

    <?php include 'include/nav_admin.php'; ?>

    <main class="flex-1 p-8">
        <div class="flex justify-between items-center mb-10">
            <div>
                <h1 class="text-3xl font-black uppercase tracking-tighter">Database <span class="text-blue-500">Ministry</span></h1>
                <p class="text-slate-500 text-xs uppercase tracking-widest font-bold mt-1">Recruitment Management</p>
            </div>
            <button onclick="toggleModal('modalMin')" class="bg-blue-600 hover:bg-blue-700 px-6 py-3 rounded-2xl text-xs font-bold uppercase transition-all shadow-lg shadow-blue-600/20">
                <i class="fas fa-plus mr-2"></i> Tambah Bidang
            </button>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php 
            $query = mysqli_query($koneksi, "SELECT * FROM open_ministry ORDER BY id DESC");
            while($row = mysqli_fetch_assoc($query)): 
            ?>
            <div class="bg-white/5 border border-white/10 rounded-[2.5rem] overflow-hidden group hover:border-blue-500/50 transition-all">
                <div class="p-8">
                    <div class="flex justify-between items-start mb-6">
                        <div class="w-16 h-16 rounded-[1.5rem] overflow-hidden border border-white/10">
                            <img src="../img_open_ministry/<?= $row['foto'] ?>" class="w-full h-full object-cover">
                        </div>
                        <span class="px-3 py-1 rounded-full text-[9px] font-black uppercase italic <?= $row['status'] == 'buka' ? 'bg-blue-500/10 text-blue-500' : 'bg-red-500/10 text-red-500' ?>">
                            <?= $row['status'] ?>
                        </span>
                    </div>
                    <h3 class="font-black uppercase italic text-lg tracking-tight mb-2"><?= $row['bidang'] ?></h3>
                    <p class="text-xs text-slate-500 line-clamp-3 mb-8 leading-relaxed"><?= $row['deskripsi'] ?></p>
                    
                    <div class="flex gap-3">
                        <button onclick='openEditModal(<?= json_encode($row) ?>)' class="flex-1 bg-white/5 hover:bg-white/10 py-3 rounded-xl text-[10px] font-black uppercase transition-all">Edit Bidang</button>
                        <a href="?delete=<?= $row['id'] ?>" onclick="return confirm('Hapus?')" class="w-11 h-11 flex items-center justify-center bg-red-500/10 text-red-500 rounded-xl hover:bg-red-500 hover:text-white transition-all">
                            <i class="fas fa-trash text-xs"></i>
                        </a>
                    </div>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
    </main>

    <div id="modalMin" class="fixed inset-0 bg-black/80 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
        <div class="bg-[#161925] border border-white/10 w-full max-w-lg rounded-[3rem] p-8">
            <h2 class="text-xl font-black uppercase italic tracking-tighter mb-6">Data Ministry</h2>
            <form action="" method="POST" enctype="multipart/form-data" class="space-y-4">
                <input type="hidden" name="id" id="min_id">
                <input type="hidden" name="foto_lama" id="min_foto_lama">
                <input type="text" name="bidang" id="min_bidang" placeholder="Nama Bidang" class="w-full bg-black/40 border border-white/5 rounded-xl px-4 py-3 text-sm outline-none focus:border-blue-500" required>
                <textarea name="deskripsi" id="min_deskripsi" rows="4" placeholder="Deskripsi Pelayanan" class="w-full bg-black/40 border border-white/5 rounded-xl px-4 py-3 text-sm outline-none focus:border-blue-500" required></textarea>
                <select name="status" id="min_status" class="w-full bg-black/40 border border-white/5 rounded-xl px-4 py-3 text-sm outline-none focus:border-blue-500">
                    <option value="buka">BUKA</option>
                    <option value="tutup">TUTUP</option>
                </select>
                <input type="file" name="foto" class="w-full bg-black/40 border border-white/5 rounded-xl px-4 py-3 text-xs text-slate-500">
                <div class="flex gap-4 pt-4">
                    <button type="button" onclick="toggleModal('modalMin')" class="flex-1 py-4 font-black uppercase text-[10px] tracking-widest text-slate-500">Batal</button>
                    <button type="submit" name="save" class="flex-1 bg-blue-600 py-4 rounded-2xl font-black uppercase text-[10px] tracking-widest shadow-lg shadow-blue-600/20">Update Data</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function toggleModal(id) { document.getElementById(id).classList.toggle('hidden'); }
        function openEditModal(data) {
            document.getElementById('min_id').value = data.id;
            document.getElementById('min_bidang').value = data.bidang;
            document.getElementById('min_deskripsi').value = data.deskripsi;
            document.getElementById('min_status').value = data.status;
            document.getElementById('min_foto_lama').value = data.foto;
            toggleModal('modalMin');
        }
    </script>
</body>
</html>