<!-- ============ MODAL PEMBAYARAN ============ -->
<div id="payment-modal" class="fixed inset-0 bg-black/40 backdrop-blur-sm z-50 hidden flex justify-center items-center transition-all">
    <div class="bg-white w-[420px] rounded-[2rem] shadow-2xl relative p-8 flex flex-col animate-pop max-h-[90vh] overflow-y-auto custom-scroll">
        <button onclick="closeModal()" class="absolute top-4 right-4 w-8 h-8 flex justify-center items-center rounded-full text-gray-400 border-2 border-gray-400 hover:text-black hover:border-black transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path></svg>
        </button>
        <div id="modal-content" class="w-full">
        </div>
    </div>
</div>

<!-- ============ CUSTOM ALERT ============ -->
<div id="custom-alert" class="fixed inset-0 bg-black/40 backdrop-blur-sm z-[100] hidden flex justify-center items-center transition-all">
    <div class="bg-white w-[350px] rounded-2xl shadow-2xl relative p-6 flex flex-col items-center animate-pop">
        <div class="w-14 h-14 rounded-full bg-red-100 flex justify-center items-center mb-4 text-red-500">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
        </div>
        <h3 class="font-bold text-lg mb-2 text-gray-800">Perhatian</h3>
        <p id="custom-alert-msg" class="text-center text-gray-600 text-sm mb-6">Pesan error di sini</p>
        <button onclick="closeAlert()" class="bg-[#3b173c] text-white font-bold py-2.5 px-8 rounded-xl hover:bg-[#572458] transition w-full">Mengerti</button>
    </div>
</div>

<!-- ============ CUSTOM CONFIRM ============ -->
<div id="custom-confirm" class="fixed inset-0 bg-black/40 backdrop-blur-sm z-[100] hidden flex justify-center items-center transition-all">
    <div class="bg-white w-[350px] rounded-2xl shadow-2xl relative p-6 flex flex-col items-center animate-pop">
        <div class="w-14 h-14 rounded-full bg-yellow-100 flex justify-center items-center mb-4 text-yellow-500">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        </div>
        <h3 class="font-bold text-lg mb-2 text-gray-800">Konfirmasi</h3>
        <p id="custom-confirm-msg" class="text-center text-gray-600 text-sm mb-6">Pesan di sini</p>
        <div class="flex gap-3 w-full">
            <button onclick="closeConfirm(false)" class="flex-1 border-2 border-gray-200 text-gray-700 font-bold py-2.5 rounded-xl hover:bg-gray-50 transition">Batal</button>
            <button onclick="closeConfirm(true)" class="flex-1 bg-[#cc0000] text-white font-bold py-2.5 rounded-xl hover:bg-red-800 transition shadow-[0_4px_14px_0_rgba(204,0,0,0.39)]">Ya, Yakin</button>
        </div>
    </div>
</div>
