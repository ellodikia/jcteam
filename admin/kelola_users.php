<?php
include '../assets/koneksi.php';

$target_dir = "../img_users/";

if (!is_dir($target_dir)) {
    mkdir($target_dir, 0777, true);
}

if (isset($_GET['delete'])) {
    $id_user = $_GET['delete'];
    
    $cek_p = mysqli_query($koneksi, "SELECT foto FROM admin_pembina WHERE id_user=$id_user");
    $cek_f = mysqli_query($koneksi, "SELECT foto FROM frontliners WHERE id_user=$id_user");
    $data_p = mysqli_fetch_assoc($cek_p);
    $data_f = mysqli_fetch_assoc($cek_f);
    
    $foto_hapus = $data_p['foto'] ?? $data_f['foto'] ?? '';
    if(!empty($foto_hapus) && !str_contains($foto_hapus, 'default') && file_exists($target_dir . $foto_hapus)) {
        unlink($target_dir . $foto_hapus);
    }

    mysqli_query($koneksi, "DELETE FROM users WHERE id_user=$id_user");
    header("Location: kelola_users.php");
    exit;
}

if (isset($_POST['save'])) {
    $username     = mysqli_real_escape_string($koneksi, $_POST['username']);
    $password     = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $level        = $_POST['level'];
    $nama_lengkap = mysqli_real_escape_string($koneksi, $_POST['nama_lengkap']);
    $bidang       = mysqli_real_escape_string($koneksi, $_POST['bidang']);

    $nama_foto = ($level == 'pembina') ? 'default_admin.jpg' : 'default_frontliner.jpg';
    if (!empty($_FILES['foto']['name'])) {
        $ext = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
        $nama_foto = "user_" . time() . "_" . rand(100, 999) . "." . $ext;
        move_uploaded_file($_FILES['foto']['tmp_name'], $target_dir . $nama_foto);
    }

    $query_user = "INSERT INTO users (username, password, level) VALUES ('$username', '$password', '$level')";
    if (mysqli_query($koneksi, $query_user)) {
        $id_baru = mysqli_insert_id($koneksi);
        
        if ($level == 'pembina') {
            mysqli_query($koneksi, "INSERT INTO admin_pembina (id_user, nama_lengkap, bidang, foto) VALUES ('$id_baru', '$nama_lengkap', '$bidang', '$nama_foto')");
        } else {
            mysqli_query($koneksi, "INSERT INTO frontliners (id_user, nama_lengkap, bidang, foto) VALUES ('$id_baru', '$nama_lengkap', '$bidang', '$nama_foto')");
        }
    }
    header("Location: kelola_users.php");
    exit;
}

$query_tampil = "SELECT users.id_user, users.username, users.level, 
                COALESCE(admin_pembina.nama_lengkap, frontliners.nama_lengkap) as nama,
                COALESCE(admin_pembina.bidang, frontliners.bidang) as bidang,
                COALESCE(admin_pembina.foto, frontliners.foto) as foto
                FROM users 
                LEFT JOIN admin_pembina ON users.id_user = admin_pembina.id_user
                LEFT JOIN frontliners ON users.id_user = frontliners.id_user
                ORDER BY users.id_user DESC";
$result = mysqli_query($koneksi, $query_tampil);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kelola Users - JC Team</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-[#0b0d17] text-white flex min-h-screen">

    <?php include('include/nav_admin.php'); ?>

    <main class="flex-1 p-8">
        <div class="flex justify-between items-center mb-10">
            <div>
                <h1 class="text-3xl font-black uppercase tracking-tighter">Manage <span class="text-blue-500">Users</span></h1>
                <p class="text-slate-500 text-xs uppercase tracking-widest font-bold mt-1">Access Control & Team Directory</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
            <div class="lg:col-span-1">
                <div class="bg-white/5 border border-white/10 p-8 rounded-[2.5rem] sticky top-8">
                    <h2 class="text-sm font-black uppercase italic tracking-widest mb-6 text-blue-500">Add New Member</h2>
                    <form action="" method="POST" enctype="multipart/form-data" class="space-y-4">
                        <div class="space-y-1">
                            <label class="text-[9px] font-black uppercase tracking-widest text-slate-500 ml-2">Access Level</label>
                            <select name="level" required class="w-full bg-black/40 border border-white/5 rounded-2xl px-4 py-3 text-sm outline-none focus:border-blue-500 transition-all appearance-none">
                                <option value="pembina" class="bg-[#161925]">PEMBINA / ADMIN</option>
                                <option value="frontliner" class="bg-[#161925]">FRONTLINER</option>
                            </select>
                        </div>
                        
                        <input type="text" name="nama_lengkap" required placeholder="Full Name" class="w-full bg-black/40 border border-white/5 rounded-2xl px-4 py-3 text-sm outline-none focus:border-blue-500 transition-all">
                        <input type="text" name="bidang" placeholder="Department / Role" class="w-full bg-black/40 border border-white/5 rounded-2xl px-4 py-3 text-sm outline-none focus:border-blue-500 transition-all">
                        
                        <div class="p-4 bg-blue-500/5 border border-white/5 rounded-2xl">
                            <label class="text-[9px] font-black text-blue-400 block mb-2 uppercase tracking-widest">Profile Picture</label>
                            <input type="file" name="foto" class="text-[10px] text-slate-500 file:mr-4 file:py-1 file:px-3 file:rounded-full file:border-0 file:text-[10px] file:font-black file:uppercase file:bg-blue-600 file:text-white hover:file:bg-blue-700 cursor-pointer">
                        </div>

                        <div class="pt-4 space-y-4">
                            <div class="border-t border-white/5 pt-4">
                                <input type="text" name="username" required placeholder="Username" class="w-full bg-black/40 border border-white/5 rounded-2xl px-4 py-3 text-sm outline-none focus:border-blue-500 transition-all mb-4">
                                <input type="password" name="password" required placeholder="Password" class="w-full bg-black/40 border border-white/5 rounded-2xl px-4 py-3 text-sm outline-none focus:border-blue-500 transition-all">
                            </div>
                        </div>
                        
                        <button type="submit" name="save" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-black py-4 rounded-2xl transition shadow-lg shadow-blue-600/20 uppercase text-[10px] tracking-[0.2em] mt-6">
                            Register Member
                        </button>
                    </form>
                </div>
            </div>

            <div class="lg:col-span-2 space-y-4">
                <h2 class="text-[10px] font-black uppercase tracking-[0.3em] text-slate-500 mb-6 ml-4">Current Team Members</h2>
                
                <div class="grid grid-cols-1 gap-4">
                    <?php while($row = mysqli_fetch_assoc($result)): ?>
                    <div class="bg-white/5 border border-white/10 p-5 rounded-[2rem] flex items-center gap-6 hover:border-blue-500/50 transition-all group">
                        <div class="w-16 h-16 rounded-2xl overflow-hidden bg-slate-800 shrink-0 border border-white/10 group-hover:scale-105 transition-transform">
                            <?php 
                            $path_foto = "../img_users/" . $row['foto'];
                            if(!empty($row['foto']) && file_exists($path_foto)): ?>
                                <img src="<?= $path_foto ?>" class="w-full h-full object-cover">
                            <?php else: ?>
                                <div class="w-full h-full flex items-center justify-center text-xl font-black text-slate-700 uppercase italic">
                                    <?= substr($row['nama'], 0, 1) ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="flex-1">
                            <div class="flex items-center gap-3 mb-1">
                                <h3 class="font-black uppercase italic text-sm tracking-tight"><?= $row['nama'] ?></h3>
                                <span class="text-[8px] font-black px-2 py-0.5 rounded-full uppercase italic tracking-widest <?= $row['level'] == 'pembina' ? 'bg-orange-500/10 text-orange-500' : 'bg-blue-500/10 text-blue-500' ?>">
                                    <?= $row['level'] ?>
                                </span>
                            </div>
                            <div class="flex items-center gap-2 text-slate-500">
                                <span class="text-[10px] font-bold uppercase tracking-widest">@<?= $row['username'] ?></span>
                                <span class="w-1 h-1 bg-slate-700 rounded-full"></span>
                                <span class="text-[10px] font-medium italic"><?= $row['bidang'] ?: 'Unassigned' ?></span>
                            </div>
                        </div>

                        <a href="?delete=<?= $row['id_user'] ?>" onclick="return confirm('Remove this user?')" class="w-12 h-12 flex items-center justify-center text-slate-500 bg-white/5 rounded-2xl hover:bg-red-500 hover:text-white transition-all">
                            <i class="fas fa-trash-alt text-xs"></i>
                        </a>
                    </div>
                    <?php endwhile; ?>
                </div>

                <?php if(mysqli_num_rows($result) == 0): ?>
                    <div class="bg-white/5 border-2 border-dashed border-white/5 py-20 rounded-[3rem] text-center">
                        <i class="fas fa-user-slash text-4xl text-slate-800 mb-4"></i>
                        <p class="text-[10px] font-black text-slate-600 uppercase tracking-widest">No users registered yet</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </main>

</body>
</html>