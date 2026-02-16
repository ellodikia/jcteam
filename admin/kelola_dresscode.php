<?php
include '../assets/koneksi.php'; // Sesuaikan dengan file koneksi Anda

// 1. FUNGSI HAPUS
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    $get_foto = mysqli_query($koneksi, "SELECT foto FROM dresscode WHERE id='$id'");
    $data_foto = mysqli_fetch_assoc($get_foto);
    
    // Hapus file fisik jika bukan default
    if($data_foto['foto'] != '') {
        unlink("../img_dresscode/" . $data_foto['foto']);
    }

    mysqli_query($koneksi, "DELETE FROM dresscode WHERE id='$id'");
    header("location:kelola_dresscode.php?pesan=dihapus");
}

// 2. FUNGSI TAMBAH
if (isset($_POST['tambah'])) {
    $tahun = $_POST['tahun'];
    $bulan = $_POST['bulan'];
    $keterangan = $_POST['keterangan'];
    
    $foto = $_FILES['foto']['name'];
    $tmp = $_FILES['foto']['tmp_name'];
    $foto_baru = time() . "_" . $foto;
    $path = "../img_dresscode/" . $foto_baru;

    if (move_uploaded_file($tmp, $path)) {
        mysqli_query($koneksi, "INSERT INTO dresscode (tahun, bulan, foto, keterangan) VALUES ('$tahun', '$bulan', '$foto_baru', '$keterangan')");
        header("location:kelola_dresscode.php?pesan=berhasil");
    }
}

// 3. FUNGSI EDIT
if (isset($_POST['edit'])) {
    $id = $_POST['id'];
    $tahun = $_POST['tahun'];
    $bulan = $_POST['bulan'];
    $keterangan = $_POST['keterangan'];
    
    if ($_FILES['foto']['name'] != "") {
        // Jika ganti foto
        $foto = $_FILES['foto']['name'];
        $tmp = $_FILES['foto']['tmp_name'];
        $foto_baru = time() . "_" . $foto;
        
        // Hapus foto lama
        $old = mysqli_query($koneksi, "SELECT foto FROM dresscode WHERE id='$id'");
        $old_data = mysqli_fetch_assoc($old);
        unlink("../img_dresscode/" . $old_data['foto']);
        
        move_uploaded_file($tmp, "../img_dresscode/" . $foto_baru);
        mysqli_query($koneksi, "UPDATE dresscode SET tahun='$tahun', bulan='$bulan', keterangan='$keterangan', foto='$foto_baru' WHERE id='$id'");
    } else {
        // Jika tidak ganti foto
        mysqli_query($koneksi, "UPDATE dresscode SET tahun='$tahun', bulan='$bulan', keterangan='$keterangan' WHERE id='$id'");
    }
    header("location:kelola_dresscode.php?pesan=diupdate");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <title>Admin - Kelola Dresscode</title>
</head>
<body class="bg-[#0b0d17] text-white flex min-h-screen">

    <?php include 'include/nav_admin.php'; ?>

    <main class="flex-1 p-8">
        <div class="flex justify-between items-center mb-10">
            <div>
                <h1 class="text-3xl font-black uppercase tracking-tighter">Database <span class="text-blue-500">Dresscode</span></h1>
                <p class="text-slate-500 text-xs uppercase tracking-widest font-bold mt-1">Management System JC Team</p>
            </div>
            <button onclick="toggleModal('modalTambah')" class="bg-blue-600 hover:bg-blue-700 px-6 py-3 rounded-2xl text-xs font-bold uppercase transition-all shadow-lg shadow-blue-600/20">
                <i class="fas fa-plus mr-2"></i> Tambah Periode
            </button>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php
            $query = mysqli_query($koneksi, "SELECT * FROM dresscode ORDER BY id DESC");
            while($row = mysqli_fetch_assoc($query)):
            ?>
            <div class="bg-white/5 border border-white/10 rounded-[2.5rem] overflow-hidden group hover:border-blue-500/50 transition-all duration-500 shadow-2xl">
                <div class="relative h-64 overflow-hidden">
                    <img src="../img_dresscode/<?= $row['foto'] ?>" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                    <div class="absolute inset-0 bg-gradient-to-t from-[#0b0d17] via-transparent to-transparent opacity-80"></div>
                    
                    <div class="absolute bottom-4 left-6">
                        <span class="text-blue-400 text-[10px] font-black uppercase tracking-widest">Periode</span>
                        <h3 class="text-xl font-bold italic uppercase leading-none"><?= $row['bulan'] ?> <?= $row['tahun'] ?></h3>
                    </div>
                </div>

                <div class="p-6">
                    <p class="text-slate-400 text-xs leading-relaxed italic mb-6">
                        "<?= $row['keterangan'] ?: 'Tidak ada keterangan tambahan.' ?>"
                    </p>
                    
                    <div class="flex gap-2">
                        <button onclick='openEditModal(<?= json_encode($row) ?>)' class="flex-1 bg-white/5 hover:bg-white/10 border border-white/10 py-3 rounded-xl text-[10px] font-bold uppercase transition-all">
                            <i class="fas fa-edit mr-2 text-blue-500"></i> Edit
                        </button>
                        <a href="?hapus=<?= $row['id'] ?>" onclick="return confirm('Hapus data ini?')" class="w-12 bg-red-500/10 hover:bg-red-500 text-red-500 hover:text-white border border-red-500/20 py-3 rounded-xl flex items-center justify-center transition-all">
                            <i class="fas fa-trash-alt text-xs"></i>
                        </a>
                    </div>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
    </main>

    <div id="modalTambah" class="fixed inset-0 bg-black/90 backdrop-blur-sm hidden z-[100] flex items-center justify-center p-4">
        <div class="bg-[#161a2a] border border-white/10 p-8 rounded-[2.5rem] w-full max-w-md shadow-2xl">
            <h2 class="text-xl font-black uppercase mb-6 italic">Tambah <span class="text-blue-500">Dresscode</span></h2>
            <form action="" method="POST" enctype="multipart/form-data" class="space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <input type="number" name="tahun" value="<?= date('Y') ?>" class="w-full bg-black/40 border border-white/5 rounded-xl px-4 py-3 text-sm outline-none focus:border-blue-500">
                    <select name="bulan" class="w-full bg-black/40 border border-white/5 rounded-xl px-4 py-3 text-sm outline-none focus:border-blue-500">
                        <?php 
                        $bulanArr = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
                        foreach($bulanArr as $b) echo "<option value='$b'>$b</option>";
                        ?>
                    </select>
                </div>
                <textarea name="keterangan" rows="3" class="w-full bg-black/40 border border-white/5 rounded-xl px-4 py-3 text-sm outline-none focus:border-blue-500" placeholder="Keterangan dresscode..."></textarea>
                <div class="bg-black/20 border border-dashed border-white/10 p-4 rounded-xl text-center">
                    <input type="file" name="foto" required class="text-xs text-slate-500 cursor-pointer">
                </div>
                <div class="flex gap-3 pt-4">
                    <button type="button" onclick="toggleModal('modalTambah')" class="flex-1 bg-white/5 py-4 rounded-2xl font-bold uppercase text-[10px]">Batal</button>
                    <button type="submit" name="tambah" class="flex-1 bg-blue-600 py-4 rounded-2xl font-bold uppercase text-[10px] shadow-lg shadow-blue-600/20">Simpan Data</button>
                </div>
            </form>
        </div>
    </div>

    <div id="modalEdit" class="fixed inset-0 bg-black/90 backdrop-blur-sm hidden z-[100] flex items-center justify-center p-4">
        <div class="bg-[#161a2a] border border-white/10 p-8 rounded-[2.5rem] w-full max-w-md shadow-2xl">
            <h2 class="text-xl font-black uppercase mb-6 italic text-blue-500">Edit Dresscode</h2>
            <form action="" method="POST" enctype="multipart/form-data" class="space-y-4">
                <input type="hidden" name="id" id="edit_id">
                <div class="grid grid-cols-2 gap-4">
                    <input type="number" name="tahun" id="edit_tahun" class="w-full bg-black/40 border border-white/5 rounded-xl px-4 py-3 text-sm outline-none focus:border-blue-500">
                    <select name="bulan" id="edit_bulan" class="w-full bg-black/40 border border-white/5 rounded-xl px-4 py-3 text-sm outline-none focus:border-blue-500">
                        <?php foreach($bulanArr as $b) echo "<option value='$b'>$b</option>"; ?>
                    </select>
                </div>
                <textarea name="keterangan" id="edit_keterangan" rows="3" class="w-full bg-black/40 border border-white/5 rounded-xl px-4 py-3 text-sm outline-none focus:border-blue-500"></textarea>
                <p class="text-[9px] text-slate-500 uppercase font-bold italic">*Kosongkan jika tidak ingin ganti foto</p>
                <input type="file" name="foto" class="text-xs text-slate-500 bg-black/20 p-4 w-full rounded-xl">
                <div class="flex gap-3 pt-4">
                    <button type="button" onclick="toggleModal('modalEdit')" class="flex-1 bg-white/5 py-4 rounded-2xl font-bold uppercase text-[10px]">Batal</button>
                    <button type="submit" name="edit" class="flex-1 bg-green-600 py-4 rounded-2xl font-bold uppercase text-[10px] shadow-lg shadow-green-600/20">Update Data</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function toggleModal(id) {
            document.getElementById(id).classList.toggle('hidden');
        }

        function openEditModal(data) {
            document.getElementById('edit_id').value = data.id;
            document.getElementById('edit_tahun').value = data.tahun;
            document.getElementById('edit_bulan').value = data.bulan;
            document.getElementById('edit_keterangan').value = data.keterangan;
            toggleModal('modalEdit');
        }
    </script>
</body>
</html>