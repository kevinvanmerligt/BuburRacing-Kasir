<!-- ============ KONTEN UTAMA RIWAYAT ============ -->
<main class="flex-1 flex flex-col overflow-hidden relative">
    
    <div class="flex justify-between items-end mb-4 px-2 shrink-0">
        <div class="relative inline-block mt-4">
            <h1 class="text-4xl font-black tracking-widest text-black uppercase">RIWAYAT</h1>
            <div class="h-[6px] bg-blue-500 w-[110%] mt-1 absolute -bottom-2 -left-1"></div>
        </div>
        
        <div class="flex items-center gap-3">
            <form id="form-date-filter" action="" method="GET" class="flex items-center gap-2 m-0 relative">
                <?php if (isset($_GET['sort'])): ?>
                    <input type="hidden" name="sort" value="<?= htmlspecialchars($_GET['sort']) ?>">
                <?php endif; ?>
                
                <div class="relative flex items-center">
                    <input type="date" name="date" id="date-filter" 
                        value="<?= htmlspecialchars($filter_date) ?>" 
                        onchange="document.getElementById('form-date-filter').submit()"
                        class="bg-white px-4 py-2.5 rounded-xl text-sm font-bold text-gray-700 shadow-sm hover:bg-gray-50 transition border border-gray-200 outline-none cursor-pointer focus:ring-2 focus:ring-blue-500">
                </div>

                <?php if (!empty($filter_date)): ?>
                    <a href="?sort=<?= htmlspecialchars($sort) ?>" class="bg-red-500 hover:bg-red-600 text-white px-3 py-2.5 rounded-xl shadow-sm transition flex items-center justify-center" title="Hapus Filter Tanggal">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </a>
                <?php endif; ?>
            </form>

            <div class="relative">
                <button onclick="toggleSort()" class="bg-white px-6 py-2.5 rounded-xl text-sm font-bold text-gray-700 shadow-sm hover:bg-gray-50 transition border border-gray-200 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                    SORTIR
                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </button>
                
                <div id="sort-dropdown" class="hidden absolute right-0 mt-2 w-56 bg-white rounded-xl shadow-lg border border-gray-100 z-20 py-2 overflow-hidden">
                    <a href="?sort=waktu_desc<?= $url_date ?>" class="block px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-100 font-semibold transition">Waktu (Terbaru ke Lama)</a>
                    <a href="?sort=waktu_asc<?= $url_date ?>" class="block px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-100 font-semibold transition border-b border-gray-100">Waktu (Lama ke Terbaru)</a>
                    
                    <a href="?sort=total_desc<?= $url_date ?>" class="block px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-100 font-semibold transition">Total (Tertinggi ke Terendah)</a>
                    <a href="?sort=total_asc<?= $url_date ?>" class="block px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-100 font-semibold transition border-b border-gray-100">Total (Terendah ke Tertinggi)</a>
                    
                    <a href="?sort=id_desc<?= $url_date ?>" class="block px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-100 font-semibold transition">ID Transaksi (Z - A)</a>
                    <a href="?sort=id_asc<?= $url_date ?>" class="block px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-100 font-semibold transition">ID Transaksi (A - Z)</a>
                </div>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-[2rem] shadow-sm flex-1 overflow-hidden flex flex-col border border-gray-400 mt-2">
        <div class="overflow-y-auto custom-scroll p-8 flex-1">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="text-[#65433a] font-bold text-[13px] border-b border-gray-200">
                        <th class="pb-4 uppercase w-[15%] px-2">ORDER ID</th>
                        <th class="pb-4 uppercase w-[15%]">WAKTU</th>
                        <th class="pb-4 uppercase w-[25%]">BARANG</th>
                        <th class="pb-4 uppercase w-[10%]">METODE</th>
                        <th class="pb-4 uppercase w-[15%]">TOTAL</th>
                        <th class="pb-4 uppercase w-[10%]">STATUS</th>
                        <th class="pb-4 uppercase w-[10%] text-center">AKSI</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($riwayat_transaksi) == 0): ?>
                        <tr><td colspan="7" class="text-center py-10 text-gray-400 font-medium">Belum ada riwayat transaksi <?= !empty($filter_date) ? 'pada tanggal ini' : '' ?>.</td></tr>
                    <?php else: ?>
                        <?php foreach($riwayat_transaksi as $row): ?>
                        <tr class="border-b border-gray-100 hover:bg-gray-50 transition group">
                            <td class="py-5 font-semibold text-gray-700 px-2"><?= $row['order_id'] ?></td>
                            <td class="py-5">
                                <div class="font-bold text-gray-800 text-sm"><?= $row['waktu_jam'] ?></div>
                                <div class="text-[13px] text-gray-500 font-medium"><?= $row['waktu_hari'] ?></div>
                            </td>
                            <td class="py-5 pr-4">
                                <div class="font-bold text-gray-700 text-[15px]"><?= implode(", ", $row['barang_utama']) ?></div>
                                <div class="text-[13px] text-gray-400 font-medium mt-0.5"><?= implode(", ", $row['barang_toping']) ?></div>
                            </td>
                            <td class="py-5">
                                <span class="font-bold text-gray-600 text-sm border border-gray-300 px-2 py-1 rounded-lg bg-white shadow-sm"><?= $row['metode'] ?></span>
                            </td>
                            <td class="py-5 font-bold text-gray-700">Rp <?= number_format($row['total'], 0, ',', '.') ?></td>
                            <td class="py-5">
                                <span class="bg-[#0d6efd] text-white text-[10px] font-bold px-3.5 py-1 rounded-full uppercase tracking-wider shadow-sm">
                                    <?= $row['status'] ?>
                                </span>
                            </td>
                            <td class="py-5 text-center">
                                <button onclick="openDetailModal(<?= htmlspecialchars(json_encode($row), ENT_QUOTES, 'UTF-8') ?>)" class="text-[#65433a] hover:text-black transition p-2 rounded-lg hover:bg-gray-200">
                                    <svg class="w-6 h-6 mx-auto" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                </button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</main>
