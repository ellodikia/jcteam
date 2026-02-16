<?php
$query_event = "SELECT * FROM event ORDER BY created_at DESC LIMIT 3";
$result_event = mysqli_query($koneksi, $query_event);
?>

<section class="max-w-7xl mx-auto px-6 py-16">
    <p class="text-purple-500 text-[10px] font-bold uppercase tracking-widest mb-2">Outgoing</p>
    <h2 class="text-4xl italic-heavy mb-10">Event Terkini</h2>
    
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <?php if (mysqli_num_rows($result_event) > 0): ?>
            <?php while($row = mysqli_fetch_assoc($result_event)): ?>
                <div class="group cursor-pointer" onclick="window.open('<?= $row['link'] ?>', '_blank')">
                    <div class="rounded-2xl overflow-hidden aspect-video mb-4 border border-white/10">
                        <?php 
                        $foto_path = "../img_events/" . $row['foto'];
                        if (!empty($row['foto']) && file_exists($foto_path)): 
                        ?>
                            <img src="<?= $foto_path ?>" 
                                 alt="<?= $row['judul'] ?>"
                                 class="w-full h-full object-cover grayscale group-hover:grayscale-0 transition duration-500">
                        <?php else: ?>
                            <div class="w-full h-full bg-neutral-900 flex items-center justify-center text-neutral-700 text-[10px] font-bold">NO POSTER</div>
                        <?php endif; ?>
                    </div>
                    
                    <h3 class="font-bold text-xs uppercase tracking-tight group-hover:text-purple-400 transition">
                        <?= $row['judul'] ?>
                    </h3>
                    
                    <p class="text-[9px] text-gray-500 mt-1 uppercase flex items-center gap-1">
                        See Details 
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-2 h-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                    </p>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="col-span-3 text-center py-10 border border-dashed border-white/5 rounded-2xl">
                <p class="text-gray-600 text-[10px] font-bold uppercase tracking-widest">Stay tuned for upcoming events</p>
            </div>
        <?php endif; ?>
    </div>
</section>