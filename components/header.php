<!-- ============ HEADER BAR ============ -->
<?php
// Default values jika tidak di-set dari halaman pemanggil
$page_title = $page_title ?? 'MAIN MENU';
$search_placeholder = $search_placeholder ?? 'Cari nama produk...';
?>
<header class="w-full bg-gradient-to-r from-[#e5e1e6] via-[#856a88] to-[#3b173c] h-24 flex items-center justify-between shadow-sm z-10 px-8 shrink-0 rounded-b-3xl">
    <div class="flex items-center gap-4 w-1/3">
        <div class="font-logo tracking-widest text-4xl text-gray-800 drop-shadow-sm">
            BUBUR <span class="text-gray-500">RACING</span>
        </div>
    </div>
    <div class="w-1/3 flex justify-center">
        <div class="relative w-full max-w-lg">
            <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none">
                <svg class="w-5 h-5 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </div>
            <input type="text" id="search-input" oninput="if (typeof searchProduct === 'function') searchProduct()" placeholder="<?= htmlspecialchars($search_placeholder) ?>" class="w-full bg-white/20 backdrop-blur-md border border-white/40 text-gray-800 placeholder-gray-700 rounded-full pl-14 pr-4 py-3 text-lg focus:outline-none focus:ring-2 focus:ring-white/50 shadow-[0_8px_32px_0_rgba(31,38,135,0.15)] transition-all">
        </div>
    </div>
    <div class="w-1/3 flex justify-end">
        <div class="text-white font-logo tracking-widest text-3xl px-10 py-3 drop-shadow-md">
            <?= htmlspecialchars($page_title) ?>
        </div>
    </div>
</header>