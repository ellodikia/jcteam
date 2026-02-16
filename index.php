<?php include('assets/koneksi.php'); ?>
<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Junior Church | GBI HOS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;800&family=Space+Grotesk:wght@500;700&display=swap" rel="stylesheet">
    <style>
        body { background-color: #050505; color: #fff; font-family: 'Plus Jakarta Sans', sans-serif; }
        .font-heading { font-family: 'Space Grotesk', sans-serif; }
        .glass { background: rgba(255, 255, 255, 0.02); backdrop-filter: blur(10px); border: 1px solid rgba(255, 255, 255, 0.05); }
        .text-gradient { background: linear-gradient(to right, #60a5fa, #a855f7); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
        .card-hover { transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1); }
        .card-hover:hover { transform: translateY(-5px); background: rgba(255, 255, 255, 0.05); border-color: rgba(255, 255, 255, 0.1); }
    </style>
</head>
<body class="antialiased">
    <?php include "include/navbar.php" ?>

    <header class="relative min-h-screen flex items-center justify-center pt-20 overflow-hidden">
        <div class="absolute inset-0 z-0">
            <div class="absolute inset-0 bg-gradient-to-b from-blue-600/10 via-transparent to-[#050505] z-10"></div>
            <img src="https://images.unsplash.com/photo-1511671782779-c97d3d27a1d4?auto=format&fit=crop&q=80" class="w-full h-full object-cover opacity-20 scale-110 animate-[pulse_8s_infinite]">
        </div>
        
        <div class="relative z-20 text-center px-6">
            <span class="inline-block px-4 py-1.5 mb-6 text-[10px] font-bold tracking-[0.3em] uppercase border border-blue-500/30 rounded-full bg-blue-500/5 text-blue-400">Welcome to the future</span>
            <h1 class="text-6xl md:text-9xl font-heading font-bold tracking-tighter leading-none mb-8">JUNIOR<br><span class="text-gradient">CHURCH</span></h1>
            <p class="max-w-2xl mx-auto text-gray-400 text-lg md:text-xl font-light leading-relaxed">
                Forging the next generation of <span class="text-white font-semibold underline decoration-blue-500 decoration-2 underline-offset-4">Kingdom Leaders</span> to impact the world.
            </p>
            <div class="mt-12 flex flex-col md:flex-row items-center justify-center gap-4">
                <a href="#event" class="px-8 py-4 bg-white text-black font-bold rounded-full hover:bg-blue-500 hover:text-white transition-all duration-300 uppercase text-xs tracking-widest">Explore More</a>
                <a href="#ministry" class="px-8 py-4 glass font-bold rounded-full hover:border-white/20 transition-all duration-300 uppercase text-xs tracking-widest">Join Us</a>
            </div>
        </div>
    </header>

    <main class="space-y-1 pb-1">
        <?php include "main_menu.php" ?>
        <?php include "latest_update.php" ?>
        <?php include "event_terkini.php" ?>
        <?php include "dokumentasi.php" ?>
        <?php include "pembina.php" ?>
        <?php include "open_ministry.php" ?>
    </main>

    <?php include "include/footer.php" ?>
</body>
</html>