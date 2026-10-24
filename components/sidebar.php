<?php
$active_page = $active_page ?? 'produk';
?>
<aside class="w-20 bg-gradient-to-b from-[#e5e1e6] to-[#3b173c] rounded-3xl flex flex-col items-center py-6 justify-between shadow-md shrink-0">
    <div class="flex flex-col gap-6 w-full items-center">
        <a href="produk.php" class="w-12 h-12 <?= $active_page === 'produk' ? 'bg-[#572458] text-white shadow-inner' : 'text-[#3b173c] hover:bg-white/40' ?> rounded-full flex justify-center items-center transition <?= $active_page === 'produk' ? 'hover:scale-105' : '' ?>" title="Kasir / Main Menu">
            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M19 8h-1V6a2 2 0 00-2-2H8a2 2 0 00-2 2v2H5a2 2 0 00-2 2v8a2 2 0 002 2h14a2 2 0 002-2v-8a2 2 0 00-2-2zM8 6h8v2H8V6zm11 12H5v-2h14v2zm0-4H5v-4h14v4zm-7-2h2v2h-2v-2z"></path></svg>
        </a>
        <a href="kelolaproduk.php" class="w-12 h-12 <?= $active_page === 'kelolaproduk' ? 'bg-[#572458] text-white shadow-inner' : 'text-[#3b173c] hover:bg-white/40' ?> rounded-full flex justify-center items-center transition <?= $active_page === 'kelolaproduk' ? 'hover:scale-105' : '' ?>" title="Kelola Produk">
            <?php if ($active_page === 'kelolaproduk'): ?>
                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16zM12 22.27L5 18.27v-8.54l7 4l7-4v8.54l-7 4.02zM12 11.73L5 7.73l7-4l7-4l-7 4z"></path></svg>
            <?php else: ?>
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
            <?php endif; ?>
        </a>
        <a href="riwayat.php" class="w-12 h-12 <?= $active_page === 'riwayat' ? 'bg-[#572458] text-white shadow-inner' : 'text-[#3b173c] hover:bg-white/40' ?> rounded-full flex justify-center items-center transition <?= $active_page === 'riwayat' ? 'hover:scale-105' : '' ?>" title="Riwayat">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        </a>
    </div>
    <a href="login.php" class="w-12 h-12 text-[#3b173c] hover:bg-red-500 hover:text-white rounded-full flex justify-center items-center transition shadow-sm" title="Keluar">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
    </a>
</aside>
