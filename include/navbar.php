<nav class="fixed top-4 left-1/2 -translate-x-1/2 w-[95%] max-w-7xl z-50">
    <div class="glass-card rounded-[2rem] px-8 py-3 flex items-center justify-between border border-white/10 shadow-2xl backdrop-blur-md bg-black/40">
        
        <div class="flex space-x-8 text-[11px] font-extrabold uppercase tracking-[0.15em] items-center">
            <a href="index.php" class="text-green-400 hover:brightness-125 transition-all">Beranda</a>
            <a href="open_ministry.php" class="text-gray-300 hover:text-white transition-all">Open Ministry</a>
            <a href="saat_teduh.php" class="text-gray-300 hover:text-white transition-all">Saat Teduh</a>
            
            <div class="h-4 w-[1px] bg-white/10 mx-2"></div> 
            
        </div>

        <div class="flex items-center space-x-6">
            <div class="hidden lg:flex items-center bg-white/5 px-4 py-2 rounded-full border border-white/5">
                <span class="text-[9px] text-gray-500 font-bold uppercase tracking-widest">Guest Access</span>
            </div>

            <div class="text-right leading-tight border-r border-white/10 pr-6 hidden sm:block">
                <p class="text-[10px] font-black uppercase tracking-tighter text-white">Junior Church</p>
                <p class="text-[8px] text-gray-500 font-bold uppercase tracking-[0.2em]">House of Sacrifice</p>
            </div>

            <div class="flex items-center gap-4 group">
                <div class="flex flex-col items-end hidden md:block">
                    <span class="text-[9px] font-black text-gray-500 uppercase tracking-widest leading-none">Anggota JC?</span>
                    <a href="login.php" class="text-[11px] font-black uppercase text-white hover:text-green-400 transition-colors">Login Masuk</a>
                </div>
                
                <a href="login.php" class="relative block">
                    <div class="w-10 h-10 rounded-full border-2 border-white/10 group-hover:border-green-500 transition-all duration-300 flex items-center justify-center bg-white/5 overflow-hidden">
                        <i class="fas fa-user text-gray-600 text-xs"></i>
                    </div>
                    <div class="absolute bottom-0 right-0 w-3 h-3 bg-gray-700 border-2 border-black rounded-full"></div>
                </a>
            </div>
        </div>
    </div>
</nav>