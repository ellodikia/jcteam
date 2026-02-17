<script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

<aside class="w-72 bg-[#0b0d17] text-white flex-shrink-0 hidden md:flex flex-col h-screen sticky top-0 border-r border-white/5 shadow-[20px_0_50px_rgba(0,0,0,0.3)] z-50">
    
    <div class="p-8 mb-2">
        <div class="flex items-center gap-3 group cursor-pointer">
            <div class="relative">
                <div class="absolute inset-0 bg-blue-500 blur-lg opacity-20 group-hover:opacity-40 transition-opacity"></div>
                <div class="relative w-11 h-11 bg-gradient-to-br from-blue-600 to-blue-400 rounded-2xl flex items-center justify-center shadow-lg shadow-blue-500/20 group-hover:rotate-12 transition-transform duration-500">
                    <i class="fas fa-bolt text-white text-xl"></i>
                </div>
            </div>
            <div class="flex flex-col">
                <span class="text-2xl font-black tracking-tighter leading-none">JC <span class="text-blue-500">TEAM</span></span>
                <span class="text-[8px] font-black text-slate-500 uppercase tracking-[0.3em] mt-1">Control Panel v2</span>
            </div>
        </div>
    </div>

    <?php 
        $current_page = basename($_SERVER['PHP_SELF']);
        $main_menu_pages = ['index.php', 'kelola_dokumentasi.php', 'kelola_event.php', 'kelola_dresscode.php', 'prayer_request.php', 'kelola_open_ministry.php'];
        $is_main_open = in_array($current_page, $main_menu_pages);
    ?>
    
    <nav class="flex-1 px-4 space-y-2 overflow-y-auto custom-scrollbar" x-data="{ openMain: <?= $is_main_open ? 'true' : 'false' ?> }">
        
        <div class="space-y-1">
            <button 
                @click="openMain = !openMain" 
                class="w-full flex items-center justify-between p-4 rounded-2xl text-slate-400 hover:bg-white/5 hover:text-white transition-all duration-300 group">
                <div class="flex items-center gap-3">
                    <div class="w-1.5 h-1.5 rounded-full bg-blue-500 shadow-[0_0_10px_rgba(59,130,246,0.5)]"></div>
                    <p class="text-[10px] font-black uppercase tracking-[0.2em]">Navigation</p>
                </div>
                <i class="fas fa-chevron-down text-[10px] transition-transform duration-500" :class="openMain ? 'rotate-180' : ''"></i>
            </button>

            <div 
                x-show="openMain" 
                x-collapse
                class="space-y-1 px-2"
            >
                <?php
                $menus = [
                    ['url' => 'index.php', 'icon' => 'fa-th-large', 'label' => 'Dashboard'],
                    ['url' => 'kelola_dokumentasi.php', 'icon' => 'fa-camera-retro', 'label' => 'Dokumentasi'],
                    ['url' => 'kelola_event.php', 'icon' => 'fa-calendar-check', 'label' => 'Event Aktif'],
                    ['url' => 'kelola_dresscode.php', 'icon' => 'fa-shirt', 'label' => 'Dresscode'],
                    ['url' => 'prayer_request.php', 'icon' => 'fa-hands-praying', 'label' => 'Prayer Requests'],
                    ['url' => 'kelola_open_ministry.php', 'icon' => 'fa-users-rays', 'label' => 'Open Ministry'],
                    ['url' => 'cek_absensi.php', 'icon' => 'fa-users-rays', 'label' => 'Cek Absensi'],
                ];

                foreach ($menus as $menu):
                    $active = ($current_page == $menu['url']);
                ?>
                <a href="<?= $menu['url'] ?>" 
                   class="flex items-center gap-4 group p-3.5 rounded-2xl transition-all duration-300 <?= $active ? 'bg-blue-600/10 text-blue-400' : 'text-slate-500 hover:text-white hover:translate-x-1' ?>">
                    <div class="w-8 h-8 rounded-xl flex items-center justify-center transition-all <?= $active ? 'bg-blue-600 text-white shadow-lg shadow-blue-600/30' : 'bg-white/5 group-hover:bg-white/10' ?>">
                        <i class="fas <?= $menu['icon'] ?> text-xs"></i>
                    </div>
                    <span class="text-xs font-black uppercase tracking-tight"><?= $menu['label'] ?></span>
                    <?php if($active): ?>
                        <div class="ml-auto w-1.5 h-1.5 rounded-full bg-blue-500 animate-pulse"></div>
                    <?php endif; ?>
                </a>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="pt-6">
            <p class="text-[10px] font-black text-slate-600 uppercase tracking-[0.2em] px-4 mb-3">System Access</p>
            <a href="kelola_users.php" 
               class="flex items-center gap-4 group p-4 rounded-2xl transition-all duration-300 <?= $current_page == 'kelola_users.php' ? 'bg-gradient-to-r from-blue-600 to-blue-500 shadow-xl shadow-blue-600/20 text-white' : 'text-slate-400 hover:bg-white/5 hover:text-white' ?>">
                <div class="w-8 h-8 rounded-xl flex items-center justify-center <?= $current_page == 'kelola_users.php' ? 'bg-white/20' : 'bg-white/5' ?>">
                    <i class="fas fa-user-shield text-xs"></i>
                </div>
                <span class="text-xs font-black uppercase tracking-tight">Kelola Users</span>
            </a>
        </div>
    </nav>

    <div class="p-6 mt-auto">
        <div class="p-4 rounded-[2rem] bg-gradient-to-b from-white/5 to-transparent border border-white/5">
            <div class="flex items-center gap-4 mb-4">
                <div class="relative">
                    <div class="w-10 h-10 rounded-2xl bg-slate-800 overflow-hidden border border-white/10">
                        <img src="https://ui-avatars.com/api/?name=Admin&background=0284c7&color=fff" class="w-full h-full object-cover">
                    </div>
                    <div class="absolute -bottom-1 -right-1 w-3.5 h-3.5 bg-[#0b0d17] rounded-full flex items-center justify-center">
                        <div class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></div>
                    </div>
                </div>
                <div class="flex flex-col min-w-0">
                    <span class="text-[11px] font-black text-white truncate uppercase italic tracking-wider">Root Admin</span>
                    <span class="text-[8px] text-blue-400 font-bold uppercase tracking-[0.1em]">Verified Access</span>
                </div>
            </div>
            
            <a href="logout.php" class="flex items-center justify-center gap-3 p-3.5 rounded-xl bg-red-500/10 text-red-500 hover:bg-red-500 hover:text-white transition-all duration-500 group">
                <i class="fas fa-power-off text-[10px] group-hover:rotate-90 transition-transform"></i>
                <span class="font-black uppercase text-[9px] tracking-[0.2em]">Logout System</span>
            </a>
        </div>
    </div>
</aside>

<style>
    .custom-scrollbar::-webkit-scrollbar {
        width: 4px;
    }
    .custom-scrollbar::-webkit-scrollbar-track {
        background: transparent;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: rgba(255, 255, 255, 0.05);
        border-radius: 10px;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background: rgba(59, 130, 246, 0.5);
    }
</style>