<?php ?>
<div id="form-modal" class="fixed inset-0 bg-black/40 backdrop-blur-sm z-40 hidden flex justify-center items-center transition-all overflow-y-auto py-10">
    <form method="POST" action="kelolaproduk.php" class="bg-white w-[500px] rounded-[2rem] shadow-2xl relative p-8 flex flex-col animate-pop my-auto">
        <input type="hidden" name="action" id="form-action" value="add">
        <input type="hidden" name="id_barang" id="input-id" value="">
        <input type="hidden" name="status" id="input-status" value="1">
        <input type="hidden" name="foto_b64" id="input-foto-b64" value="">

        <div id="dropzone" class="w-full h-44 bg-[#f0eef1] rounded-2xl flex flex-col items-center justify-center mb-6 cursor-pointer border-2 border-dashed border-gray-300 hover:border-[#572458] hover:bg-gray-100 transition shadow-inner relative overflow-hidden group">
            <input type="file" id="fileInput" accept="image/*" class="hidden">
            <img id="preview-image" src="" class="absolute inset-0 w-full h-full object-cover hidden" alt="Preview">
            <div id="dropzone-text" class="flex flex-col items-center text-gray-500 group-hover:text-[#572458]">
                <svg class="w-10 h-10 mb-2 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                <span class="font-bold text-sm">Klik atau Seret Foto Kesini</span>
            </div>
        </div>

        <div class="mb-5">
            <label class="block text-gray-900 text-sm font-bold mb-2">Nama Produk :</label>
            <input type="text" name="nama_barang" id="input-nama" required class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 text-gray-800 focus:border-[#572458] focus:ring-1 focus:ring-[#572458] focus:outline-none transition font-semibold" placeholder="Nama Produk">
        </div>

        <div class="mb-5">
            <label class="block text-gray-900 text-sm font-bold mb-2">Kategori :</label>
            <select name="jenis" id="input-jenis" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 text-gray-800 focus:border-[#572458] focus:ring-1 focus:ring-[#572458] focus:outline-none transition font-semibold bg-white">
                <?php foreach ($categories as $cat): ?>
                <option value="<?= htmlspecialchars($cat) ?>"><?= htmlspecialchars($cat) ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="mb-5">
            <label class="block text-gray-900 text-sm font-bold mb-2">Harga Produk :</label>
            <input type="text" name="harga_barang" id="input-harga" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 text-gray-800 focus:border-[#572458] focus:ring-1 focus:ring-[#572458] focus:outline-none transition font-semibold" placeholder="Rp 0">
        </div>

        <div class="mb-8">
            <label class="block text-gray-900 text-sm font-bold mb-2">Stok Produk :</label>
            <div class="flex gap-3">
                <input type="number" name="stok" id="input-stok" min="0" class="w-1/3 border-2 border-gray-200 rounded-xl px-4 py-3 text-gray-800 focus:border-[#572458] focus:ring-1 focus:ring-[#572458] focus:outline-none transition font-semibold" placeholder="0">
                
                <div class="flex-1 flex gap-2 font-bold text-sm">
                    <button type="button" id="btn-tidak-tersedia" onclick="setStatus(0)" class="flex-1 text-center py-3 rounded-xl border-2 transition text-gray-500 border-gray-200 hover:bg-red-50">Habis</button>
                    <button type="button" id="btn-tersedia" onclick="setStatus(1)" class="flex-1 text-center py-3 rounded-xl border-2 transition text-gray-500 border-gray-200 hover:bg-green-50">Tersedia</button>
                </div>
            </div>
        </div>

        <div class="flex justify-center gap-4 mt-2">
            <button type="button" onclick="closeFormModal()" class="px-8 py-3 bg-white border-2 border-gray-200 rounded-xl font-bold text-gray-800 hover:bg-gray-50 transition shadow-sm w-1/2">Cancel</button>
            <button type="submit" class="px-8 py-3 bg-[#0cc00c] rounded-xl font-bold text-white hover:bg-green-600 transition shadow-[0_4px_14px_0_rgba(12,192,12,0.39)] w-1/2 tracking-wide">Simpan</button>
        </div>
    </form>
</div>

<div id="cropper-modal" class="fixed inset-0 bg-black/80 backdrop-blur-sm z-50 hidden flex justify-center items-center transition-all">
    <div class="bg-white w-[600px] rounded-[2rem] shadow-2xl p-6 flex flex-col animate-pop">
        <h3 class="text-xl font-bold mb-4 text-center">Sesuaikan Gambar</h3>
        <div class="w-full h-[400px] bg-gray-200 rounded-xl overflow-hidden mb-6 flex justify-center items-center">
            <img id="image-to-crop" src="" class="max-w-full max-h-full">
        </div>
        <div class="flex justify-center gap-4">
            <button type="button" onclick="closeCropper()" class="px-8 py-3 bg-gray-200 rounded-xl font-bold text-gray-800 hover:bg-gray-300 transition shadow-sm w-1/2">Batal</button>
            <button type="button" onclick="applyCrop()" class="px-8 py-3 bg-[#572458] rounded-xl font-bold text-white hover:bg-[#3b173c] transition shadow-md w-1/2 tracking-wide">Potong & Pakai</button>
        </div>
    </div>
</div>

<div id="success-modal" class="fixed inset-0 bg-black/40 backdrop-blur-sm z-50 hidden flex justify-center items-center transition-all">
    <div class="bg-white w-[420px] rounded-[2rem] shadow-2xl relative px-8 py-10 flex flex-col items-center animate-pop">
        <div class="w-16 h-16 mx-auto bg-blue-500 rounded-full flex items-center justify-center mb-5">
            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path></svg>
        </div>
        <h3 class="text-xl font-black text-gray-900 mb-8 text-center tracking-wide">Data Berhasil Diperbarui!!</h3>
        <button onclick="closeSuccessModal()" class="px-12 py-3 bg-[#3125c4] rounded-xl font-bold text-white hover:bg-blue-800 transition shadow-[0_4px_14px_0_rgba(49,37,196,0.39)] tracking-wide">Selesai</button>
    </div>
</div>

<div id="delete-confirm-modal" class="fixed inset-0 bg-black/40 backdrop-blur-sm z-50 hidden flex justify-center items-center transition-all">
    <div class="bg-white w-[350px] rounded-3xl shadow-2xl p-6 py-8 flex flex-col items-center animate-pop">
        <h3 class="text-[17px] font-semibold text-black mb-8 text-center tracking-wide">Apakah ingin Mengapus data??</h3>
        <div class="flex justify-center gap-3 w-full px-2">
            <button onclick="closeDeleteConfirmModal()" class="flex-1 py-2 bg-white border border-gray-200 rounded-xl text-black font-semibold shadow-sm hover:bg-gray-50 transition">Batal</button>
            <a href="#" id="confirm-delete-btn" class="flex-1 py-2 bg-[#8c2222] rounded-xl text-white font-semibold text-center hover:bg-[#6b1919] transition shadow-sm">Hapus</a>
        </div>
    </div>
</div>

<div id="delete-success-modal" class="fixed inset-0 bg-black/40 backdrop-blur-sm z-50 hidden flex justify-center items-center transition-all">
    <div class="bg-white w-[320px] rounded-3xl shadow-2xl p-6 py-8 flex flex-col items-center animate-pop">
        <h3 class="text-[17px] font-semibold text-black mb-8 text-center tracking-wide">Produk Telah di Hapus</h3>
        <button onclick="closeDeleteSuccessModal()" class="w-[120px] py-2 bg-[#8c2222] rounded-xl text-white font-semibold hover:bg-[#6b1919] transition shadow-sm">Selesai</button>
    </div>
</div>
