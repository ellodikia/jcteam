<?php
session_start();
$host = "localhost";
$user = "root";
$pass = "";
$db   = "jcteam";
$koneksi = mysqli_connect($host, $user, $pass, $db);

$error = "";

if (isset($_POST['login'])) {
    $username = mysqli_real_escape_string($koneksi, $_POST['username']);
    $password = $_POST['password'];

    $query = "SELECT * FROM users WHERE username = '$username'";
    $result = mysqli_query($koneksi, $query);

    if (mysqli_num_rows($result) === 1) {
        $row = mysqli_fetch_assoc($result);
        
        if (password_verify($password, $row['password'])) {
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['id_user'] = $row['id_user']; 
            $_SESSION['username'] = $row['username'];
            $_SESSION['level'] = $row['level']; 
            
            if ($row['level'] === 'pembina') {
                header("Location: admin/index.php");
            } elseif ($row['level'] === 'frontliner') {
                header("Location: frontliner/index.php");
            }
            exit;
        } else {
            $error = "Password salah!";
        }
    } else {
        $error = "Username tidak ditemukan!";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin | JC TEAM</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;700;800&display=swap');
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #000; }
        .glass { background: rgba(255, 255, 255, 0.03); backdrop-filter: blur(10px); border: 1px solid rgba(255, 255, 255, 0.05); }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-6 bg-[radial-gradient(circle_at_top_right,_var(--tw-gradient-stops))] from-blue-900/20 via-black to-black">

    <div class="w-full max-w-md">
        <div class="text-center mb-10">
            <div class="inline-flex w-16 h-16 bg-blue-600 rounded-2xl items-center justify-center shadow-2xl shadow-blue-500/40 mb-6">
                <i class="fas fa-bolt text-white text-3xl"></i>
            </div>
            <h1 class="text-4xl font-black italic tracking-tighter text-white uppercase">JC <span class="text-blue-500">TEAM</span></h1>
            <p class="text-[10px] font-bold text-slate-500 uppercase tracking-[0.4em] mt-2">Control Panel Access</p>
        </div>

        <div class="glass p-10 rounded-[2.5rem] shadow-2xl">
            <?php if($error): ?>
                <div class="bg-red-500/10 border border-red-500/50 text-red-500 text-[10px] font-black uppercase tracking-widest p-4 rounded-xl mb-6 text-center italic">
                    <i class="fas fa-exclamation-triangle mr-2"></i> <?= $error ?>
                </div>
            <?php endif; ?>

            <form action="" method="POST" class="space-y-6">
                <div>
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-2 px-1">Username</label>
                    <div class="relative">
                        <i class="fas fa-user absolute left-5 top-1/2 -translate-y-1/2 text-slate-600 text-xs"></i>
                        <input type="text" name="username" required 
                            class="w-full bg-white/5 border border-white/10 rounded-2xl py-4 pl-12 pr-6 text-white text-sm focus:outline-none focus:border-blue-500 transition-all placeholder:text-slate-700"
                            placeholder="Masukan username">
                    </div>
                </div>

                <div>
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-2 px-1">Password</label>
                    <div class="relative">
                        <i class="fas fa-lock absolute left-5 top-1/2 -translate-y-1/2 text-slate-600 text-xs"></i>
                        <input type="password" name="password" required 
                            class="w-full bg-white/5 border border-white/10 rounded-2xl py-4 pl-12 pr-6 text-white text-sm focus:outline-none focus:border-blue-500 transition-all placeholder:text-slate-700"
                            placeholder="••••••••">
                    </div>
                </div>

                <div class="pt-4">
                    <button type="submit" name="login" 
                        class="w-full bg-white text-black py-4 rounded-2xl font-black uppercase tracking-[0.2em] text-[11px] hover:bg-blue-500 hover:text-white transition-all shadow-xl shadow-white/5 active:scale-95">
                        Akses Dashboard
                    </button>
                </div>
            </form>
        </div>

        <p class="text-center text-slate-600 text-[10px] font-bold uppercase tracking-widest mt-10">
            &copy; 2026 Junior Church House of Sacrifice
        </p>
    </div>

</body>
</html>