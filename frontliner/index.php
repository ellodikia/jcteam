<?php
include('../assets/koneksi.php');

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Junior Church GBI HOS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;700;900&display=swap');
        body { background-color: #0b0d17; color: white; font-family: 'Inter', sans-serif; scroll-behavior: smooth; }
        .glass-card { background: rgba(255, 255, 255, 0.03); backdrop-filter: blur(12px); border: 1px solid rgba(255, 255, 255, 0.08); }
        .italic-heavy { font-weight: 900; font-style: italic; text-transform: uppercase; letter-spacing: -0.02em; }
    </style>
</head>
<body class="overflow-x-hidden">

        <?php include "include/navbar.php" ?>


    <header class="relative h-[70vh] flex items-center justify-center pt-20 px-6">
        <div class="absolute inset-x-4 top-24 bottom-0 z-0 overflow-hidden rounded-[3rem]">
            <img src="https://images.unsplash.com/photo-1511671782779-c97d3d27a1d4?auto=format&fit=crop&q=80" class="w-full h-full object-cover opacity-30 grayscale">
            <div class="absolute inset-0 bg-gradient-to-b from-transparent to-[#0b0d17]"></div>
        </div>
        <div class="relative z-10 text-center">
            <h1 class="text-6xl md:text-9xl italic-heavy">JUNIOR CHURCH</h1>
            <div class="h-1.5 w-32 bg-blue-500 mx-auto rounded-full my-6"></div>
            <p class="text-lg text-gray-400">Forging the next generation of <span class="text-white font-bold">Kingdom Leaders.</span></p>
        </div>
    </header>

        <?php include "main_menu.php" ?>

        <?php include "dokumentasi.php" ?>

        <?php include "frontliner.php" ?>

        <?php include "latest_update.php" ?>

        <?php include "event_terkini.php" ?>

        <?php include "pembina.php" ?>

        <?php include "open_ministry.php" ?>

        <?php include "include/footer.php" ?>
   

</body>
</html>