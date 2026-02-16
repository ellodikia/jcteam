<?php
include '../assets/koneksi.php';
$target_dir = "../img_events/";
if (!is_dir($target_dir)) { mkdir($target_dir, 0777, true); }

if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $cek = mysqli_query($koneksi, "SELECT foto FROM event WHERE id_event=$id");
    $data = mysqli_fetch_assoc($cek);
    if(!empty($data['foto']) && file_exists($target_dir . $data['foto'])) { unlink($target_dir . $data['foto']); }
    mysqli_query($koneksi, "DELETE FROM event WHERE id_event=$id");
    header("Location: kelola_event.php");
}

if (isset($_POST['save'])) {
    $id_event   = $_POST['id_event'];
    $judul      = mysqli_real_escape_string($koneksi, $_POST['judul']);
    $link       = mysqli_real_escape_string($koneksi, $_POST['link']);
    $nama_foto  = $_POST['foto_lama'] ?? ""; 

    if (!empty($_FILES['foto']['name'])) {
        $new_file_name = "event_" . time() . "_" . $_FILES['foto']['name'];
        if (move_uploaded_file($_FILES['foto']['tmp_name'], $target_dir . $new_file_name)) {
            $nama_foto = $new_file_name;
            if (!empty($_POST['foto_lama']) && file_exists($target_dir . $_POST['foto_lama'])) { unlink($target_dir . $_POST['foto_lama']); }
        }
    }

    if ($id_event == "") {
        mysqli_query($koneksi, "INSERT INTO event (judul, link, foto) VALUES ('$judul', '$link', '$nama_foto')");
    } else {
        mysqli_query($koneksi, "UPDATE event SET judul='$judul', link='$link', foto='$nama_foto' WHERE id_event=$id_event");
    }
    header("Location: kelola_event.php");
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <title>Admin - Events</title>
</head>
<body class="bg-[#0b0d17] text-white flex min-h-screen">

    <?php include 'include/nav_admin.php'; ?>

    <main class="flex-1 p-8">
        <div class="flex justify-between items-center mb-10">
            <div>
                <h1 class="text-3xl font-black uppercase tracking-tighter">Database <span class="text-blue-500">Event</span></h1>
                <p class="text-slate-500 text-xs uppercase tracking-widest font-bold mt-1">Special Events Management</p>
            </div>
            <button onclick="toggleModal('modalEvent')" class="bg-blue-600 hover:bg-blue-700 px-6 py-3 rounded-2xl text-xs font-bold uppercase transition-all shadow-lg shadow-blue-600/20">
                <i class="fas fa-plus mr-2"></i> Tambah Event
            </button>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php 
            $query = mysqli_query($koneksi, "SELECT * FROM event ORDER BY id_event DESC");
            while($row = mysqli_fetch_assoc($query)): 
            ?>
            <div class="bg-white/5 border border-white/10 rounded-[2.5rem] overflow-hidden group hover:border-blue-500/50 transition-all">
                <div class="h-56 overflow-hidden relative">
                    <img src="../img_events/<?= $row['foto'] ?>" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                </div>
                <div class="p-6">
                    <h3 class="font-black uppercase italic text-sm tracking-tight mb-6"><?= $row['judul'] ?></h3>
                    <div class="flex gap-3">
                        <a href="<?= $row['link'] ?>" target="_blank" class="flex-1 bg-blue-600 hover:bg-blue-700 text-center py-3 rounded-xl text-[10px] font-black uppercase transition-all shadow-lg shadow-blue-600/10">Buka Link</a>
                        <button onclick='openEditModal(<?= json_encode($row) ?>)' class="w-10 h-10 flex items-center justify-center bg-orange-500/10 text-orange-500 rounded-xl hover:bg-orange-500 hover:text-white transition-all">
                            <i class="fas fa-edit text-xs"></i>
                        </button>
                        <a href="?delete=<?= $row['id_event'] ?>" onclick="return confirm('Hapus?')" class="w-10 h-10 flex items-center justify-center bg-red-500/10 text-red-500 rounded-xl hover:bg-red-500 hover:text-white transition-all">
                            <i class="fas fa-trash text-xs"></i>
                        </a>
                    </div>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
    </main>

    <div id="modalEvent" class="fixed inset-0 bg-black/80 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
        <div class="bg-[#161925] border border-white/10 w-full max-w-lg rounded-[3rem] p-8">
            <h2 class="text-xl font-black uppercase italic tracking-tighter mb-6">Kelola Event</h2>
            <form action="" method="POST" enctype="multipart/form-data" class="space-y-4">
                <input type="hidden" name="id_event" id="ev_id" value="">
                <input type="hidden" name="foto_lama" id="ev_foto_lama" value="">
                <input type="text" name="judul" id="ev_judul" placeholder="Judul Event" class="w-full bg-black/40 border border-white/5 rounded-xl px-4 py-3 text-sm outline-none focus:border-blue-500" required>
                <input type="url" name="link" id="ev_link" placeholder="URL Informasi / Pendaftaran" class="w-full bg-black/40 border border-white/5 rounded-xl px-4 py-3 text-sm outline-none focus:border-blue-500" required>
                <input type="file" name="foto" class="w-full bg-black/40 border border-white/5 rounded-xl px-4 py-3 text-xs text-slate-500">
                <div class="flex gap-4 pt-4">
                    <button type="button" onclick="toggleModal('modalEvent')" class="flex-1 py-4 font-black uppercase text-[10px] tracking-widest text-slate-500">Batal</button>
                    <button type="submit" name="save" class="flex-1 bg-blue-600 py-4 rounded-2xl font-black uppercase text-[10px] tracking-widest shadow-lg shadow-blue-600/20">Simpan Event</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function toggleModal(id) { document.getElementById(id).classList.toggle('hidden'); }
        function openEditModal(data) {
            document.getElementById('ev_id').value = data.id_event;
            document.getElementById('ev_judul').value = data.judul;
            document.getElementById('ev_link').value = data.link;
            document.getElementById('ev_foto_lama').value = data.foto;
            toggleModal('modalEvent');
        }
    </script>
</body>
</html>