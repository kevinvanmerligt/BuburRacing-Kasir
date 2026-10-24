let cart = []; 
let selectedPaymentMethod = null;
let globalSubtotal = 0;
let globalTax = 0;
let globalTotal = 0;
let globalTotalQty = 0;

function showAlert(message) {
    document.getElementById('custom-alert-msg').innerText = message;
    document.getElementById('custom-alert').classList.remove('hidden');
}

function closeAlert() {
    document.getElementById('custom-alert').classList.add('hidden');
}

let confirmAction = null;
function showConfirm(message, action) {
    document.getElementById('custom-confirm-msg').innerText = message;
    confirmAction = action;
    document.getElementById('custom-confirm').classList.remove('hidden');
}

function closeConfirm(isConfirmed) {
    document.getElementById('custom-confirm').classList.add('hidden');
    if (isConfirmed && confirmAction) {
        confirmAction();
    }
    confirmAction = null;
}

function searchProduct() {
    let input = document.getElementById('search-input').value.toLowerCase();
    let cards = document.querySelectorAll('.product-card');

    cards.forEach(card => {
        let name = card.getAttribute('data-name').toLowerCase();
        if (name.includes(input)) {
            card.style.display = 'flex';
        } else {
            card.style.display = 'none';
        }
    });
}

function scrollToCategory(id, btnEl) {
    const el = document.getElementById(id);
    if (el) {
        el.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }

    if (btnEl) {
        const allBtns = document.querySelectorAll('.cat-btn');
        allBtns.forEach(btn => {
            btn.className = "cat-btn px-8 py-2 bg-[#f0eef1] hover:bg-[#e0dce1] text-gray-500 font-medium rounded-lg transition border border-gray-300";
        });
        btnEl.className = "cat-btn px-8 py-2 bg-[#e0dce1] text-gray-800 font-semibold rounded-lg shadow-sm transition border border-transparent";
    }
}

function formatRupiah(angka) {
    return 'Rp ' + angka.toLocaleString('id-ID');
}

function formatInputRupiah(el) {
    let value = el.value.replace(/[^,\d]/g, '').toString();
    let split = value.split(',');
    let sisa = split[0].length % 3;
    let rupiah = split[0].substr(0, sisa);
    let ribuan = split[0].substr(sisa).match(/\d{3}/gi);

    if (ribuan) {
        let separator = sisa ? '.' : '';
        rupiah += separator + ribuan.join('.');
    }

    rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
    el.value = rupiah;
    hitungKembalian();
}

function setNominal(amount) {
    const inputNominal = document.getElementById('input-nominal');
    if (inputNominal) {
        inputNominal.value = amount.toLocaleString('id-ID');
        hitungKembalian();
    }
}

function addToCart(id, name, price, maxStock, type) {
    let currentCartQty = 0;
    cart.forEach(b => {
        if (b.main.id === id) currentCartQty += b.main.qty;
        b.addons.forEach(a => {
            if (a.id === id) currentCartQty += a.qty;
        });
    });

    if (currentCartQty >= maxStock) {
        showAlert('Stok ' + name + ' tidak mencukupi!');
        return;
    }

    if (type === 'bubur') {
        cart.push({
            main: { id: id, name: name, price: price, qty: 1, maxStock: maxStock, type: type },
            addons: []
        });
    } else if (type === 'minuman') {
        let existingMinuman = cart.find(b => b.main.id === id && (b.main.type === 'minuman' || !b.main.type));
        if (existingMinuman) {
            existingMinuman.main.qty++;
        } else {
            cart.push({
                main: { id: id, name: name, price: price, qty: 1, maxStock: maxStock, type: type },
                addons: []
            });
        }
    } else if (type === 'toping') {
        let lastBuburBundle = null;
        for (let i = cart.length - 1; i >= 0; i--) {
            if (cart[i].main.type === 'bubur' || !cart[i].main.type) {
                lastBuburBundle = cart[i];
                break;
            }
        }

        if (!lastBuburBundle) {
            showAlert("Silakan pilih makanan (Bubur) terlebih dahulu sebelum menambahkan Toping!");
            return;
        }
        
        let existingAddon = lastBuburBundle.addons.find(a => a.id === id);
        
        if (existingAddon) {
            existingAddon.qty++;
        } else {
            lastBuburBundle.addons.push({ id: id, name: name, price: price, qty: 1, maxStock: maxStock });
        }
    }
    updateCartUI();
}

function changeMainQty(bundleIndex, delta) {
    let bundle = cart[bundleIndex];
    
    if (delta > 0) {
        let currentCartQty = 0;
        cart.forEach(b => {
            if (b.main.id === bundle.main.id) currentCartQty += b.main.qty;
        });
        if (currentCartQty >= bundle.main.maxStock) {
            showAlert('Stok maksimal tercapai!');
            return;
        }
    }

    let newQty = bundle.main.qty + delta;
    
    if (newQty <= 0) {
        cart.splice(bundleIndex, 1);
    } else {
        bundle.main.qty = newQty;
    }
    updateCartUI();
}

function changeAddonQty(bundleIndex, addonIndex, delta) {
    let bundle = cart[bundleIndex];
    let addon = bundle.addons[addonIndex];
    
    if (delta > 0) {
        let currentCartQty = 0;
        cart.forEach(b => {
            b.addons.forEach(a => {
                if (a.id === addon.id) currentCartQty += a.qty;
            });
        });
        if (currentCartQty >= addon.maxStock) {
            showAlert('Stok maksimal tercapai!');
            return;
        }
    }

    let newQty = addon.qty + delta;

    if (newQty <= 0) {
        bundle.addons.splice(addonIndex, 1);
    } else {
        addon.qty = newQty;
    }
    updateCartUI();
}

function updateCartUI() {
    document.querySelectorAll('[id^="stock-"]').forEach(el => {
        el.innerText = 'Stok: ' + el.getAttribute('data-stock');
    });

    const cartContainer = document.getElementById('cart-items');
    cartContainer.innerHTML = '';
    
    globalSubtotal = 0;
    globalTotalQty = 0;

    if (cart.length === 0) {
        cartContainer.innerHTML = '<div id="empty-cart-msg" class="text-center text-gray-400 mt-10 font-medium">Belum ada pesanan</div>';
    } else {
        for (let i = 0; i < cart.length; i++) {
            let bundle = cart[i];
            let mainTotal = bundle.main.price * bundle.main.qty;
            globalSubtotal += mainTotal;
            globalTotalQty += bundle.main.qty;

            let stockElMain = document.getElementById('stock-' + bundle.main.id);
            if (stockElMain) {
                let currentStock = parseInt(stockElMain.innerText.replace('Stok: ', ''));
                stockElMain.innerText = 'Stok: ' + (currentStock - bundle.main.qty);
            }

            let bundleHTML = `
            <div class="border-b border-gray-200 pb-3 mb-1">
                <div class="flex justify-between items-start mb-2">
                    <div class="w-1/2 pr-2">
                        <p class="font-bold text-gray-800 text-base leading-tight">${bundle.main.name}</p>
                    </div>
                    <div class="flex items-center gap-3 w-1/4 justify-center">
                        <button onclick="changeMainQty(${i}, -1)" class="w-6 h-6 rounded-full bg-black text-white flex items-center justify-center text-sm hover:bg-gray-800 transition shadow-sm">-</button>
                        <span class="text-base font-bold text-gray-800">${bundle.main.qty}</span>
                        <button onclick="changeMainQty(${i}, 1)" class="w-6 h-6 rounded-full bg-black text-white flex items-center justify-center text-sm hover:bg-gray-800 transition shadow-sm">+</button>
                    </div>
                    <div class="text-right w-1/4">
                        <p class="font-bold text-base text-gray-800">${bundle.main.price === 0 ? 'Gratis' : formatRupiah(mainTotal)}</p>
                    </div>
                </div>
            `;

            for (let j = 0; j < bundle.addons.length; j++) {
                let addon = bundle.addons[j];
                let addonTotal = addon.price * addon.qty;
                globalSubtotal += addonTotal;
                globalTotalQty += addon.qty;

                let stockElAddon = document.getElementById('stock-' + addon.id);
                if (stockElAddon) {
                    let currentStock = parseInt(stockElAddon.innerText.replace('Stok: ', ''));
                    stockElAddon.innerText = 'Stok: ' + (currentStock - addon.qty);
                }

                bundleHTML += `
                <div class="flex justify-between items-start mt-2 pl-3">
                    <div class="w-1/2 pr-2 flex items-start gap-1 text-gray-500">
                        <span class="text-sm mt-[2px]">+</span>
                        <p class="font-medium text-sm leading-tight">${addon.name}</p>
                    </div>
                    <div class="flex items-center gap-2 w-1/4 justify-center">
                        <button onclick="changeAddonQty(${i}, ${j}, -1)" class="w-5 h-5 rounded-full bg-gray-300 text-gray-700 flex items-center justify-center text-xs hover:bg-gray-400 transition">-</button>
                        <span class="text-sm font-semibold text-gray-700">${addon.qty}</span>
                        <button onclick="changeAddonQty(${i}, ${j}, 1)" class="w-5 h-5 rounded-full bg-gray-300 text-gray-700 flex items-center justify-center text-xs hover:bg-gray-400 transition">+</button>
                    </div>
                    <div class="text-right w-1/4">
                        <p class="font-medium text-sm text-gray-600">${addon.price === 0 ? 'Gratis' : formatRupiah(addonTotal)}</p>
                        </div>
                </div>
                `;
            }
            bundleHTML += `</div>`;
            cartContainer.insertAdjacentHTML('beforeend', bundleHTML);
        }
    }

    globalTax = Math.round(globalSubtotal * 0.11);
    globalTotal = globalSubtotal + globalTax;

    document.getElementById('subtotal-price').innerText = formatRupiah(globalSubtotal);
    document.getElementById('tax-price').innerText = formatRupiah(globalTax);
    document.getElementById('total-price').innerText = formatRupiah(globalTotal);
}

function selectPayment(buttonEl, method) {
    const buttons = document.querySelectorAll('.payment-btn');
    buttons.forEach(btn => {
        btn.classList.remove('border-black');
        btn.classList.add('border-gray-200');
        const child = btn.children[0];
        if (child) {
            child.classList.remove('text-black');
            child.classList.add('text-gray-600');
        }
    });

    buttonEl.classList.remove('border-gray-200');
    buttonEl.classList.add('border-black');

    const child = buttonEl.children[0];
    if (child) {
        child.classList.remove('text-gray-600');
        child.classList.add('text-black');
    }
    selectedPaymentMethod = method;
}

function clearCart() {
    if (cart.length === 0) return;
    showConfirm("Yakin ingin membatalkan pesanan ini?", doClearCart);
}

function doClearCart() {
    cart = []; 
    document.getElementById('customer-name').value = '';
    document.getElementById('meja-selection').value = '';
    
    const buttons = document.querySelectorAll('.payment-btn');
    buttons.forEach(btn => {
        btn.classList.remove('border-black');
        btn.classList.add('border-gray-200');
        const child = btn.children[0];
        if (child) {
            child.classList.remove('text-black');
            child.classList.add('text-gray-600');
        }
    });
    selectedPaymentMethod = null;
    updateCartUI();
}

function checkout() {
    if (cart.length === 0) {
        showAlert("Pesanan masih kosong!");
        return;
    }
    if (!selectedPaymentMethod) {
        showAlert("Silakan pilih metode pembayaran terlebih dahulu!");
        return;
    }

    if (selectedPaymentMethod === 'QRIS' || selectedPaymentMethod === 'Debit') {
        showAlert("Masih proses pengembangan");
        return;
    }
    
    const namaVal = document.getElementById('customer-name').value.trim();
    if (!namaVal) {
        showAlert("Silakan isi Nama pelanggan terlebih dahulu!");
        return;
    }

    const mejaVal = document.getElementById('meja-selection').value;
    if (!mejaVal) {
        showAlert("Silakan pilih Meja terlebih dahulu!");
        return;
    }

    renderModalContent('checkout');
    document.getElementById('payment-modal').classList.remove('hidden');
}

function closeModal() {
    document.getElementById('payment-modal').classList.add('hidden');
    if (document.getElementById('success-icon')) {
        window.location.reload(); 
    }
}

function hitungKembalian() {
    const inputNominalRaw = document.getElementById('input-nominal').value.replace(/\./g, '');
    const inputNominal = parseInt(inputNominalRaw) || 0;
    const kembaliEl = document.getElementById('teks-kembali');

    if (inputNominal >= globalTotal) {
        const kembalian = inputNominal - globalTotal;
        kembaliEl.innerText = formatRupiah(kembalian);
    } else {
        kembaliEl.innerText = "Rp 0";
    }
}

async function processCheckoutToDB(uang, kembalian, nama, meja, metode) {
    let flatCart = {};
    for (let bundle of cart) {
        if (!flatCart[bundle.main.id]) {
            flatCart[bundle.main.id] = { price: bundle.main.price, qty: 0 };
        }
        flatCart[bundle.main.id].qty += bundle.main.qty;

        for (let addon of bundle.addons) {
            if (!flatCart[addon.id]) {
                flatCart[addon.id] = { price: addon.price, qty: 0 };
            }
            flatCart[addon.id].qty += addon.qty;
        }
    }

    try {
        const response = await fetch('produk.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                action: 'checkout',
                uang: uang,
                kembalian: kembalian,
                pajak: globalTax,
                nama: nama,
                meja: meja,
                metode: metode,
                cart: flatCart
            })
        });
        
        const result = await response.json();
        
        if (result.status === 'success') {
            renderModalContent('success');
        } else {
            showAlert("Gagal memproses transaksi:\n" + result.message);
            const btn = document.getElementById('btn-verify');
            if (btn) btn.innerText = 'Coba Lagi';
        }
    } catch (error) {
        console.error("Error:", error);
        showAlert("Terjadi masalah jaringan.");
        const btn = document.getElementById('btn-verify');
        if (btn) btn.innerText = 'Coba Lagi';
    }
}

function verifyPayment() {
    let uang = 0;
    let kembalian = 0;
    const namaVal = document.getElementById('customer-name').value.trim();
    const mejaVal = document.getElementById('meja-selection').value;

    if (selectedPaymentMethod === 'Cash') {
        const inputNominalRaw = document.getElementById('input-nominal').value.replace(/\./g, '');
        const inputNominal = parseInt(inputNominalRaw) || 0;
        
        if (!inputNominal || inputNominal < globalTotal) {
            showAlert("Nominal pembayaran kurang!");
            return;
        }
        uang = inputNominal;
        kembalian = uang - globalTotal;
    } else {
        uang = globalTotal;
        kembalian = 0;
    }

    const btn = document.getElementById('btn-verify');
    if (btn) btn.innerText = 'Memproses...';

    processCheckoutToDB(uang, kembalian, namaVal, mejaVal, selectedPaymentMethod);
}

function renderModalContent(state) {
    const container = document.getElementById('modal-content');
    let itemsHtml = '';
    
    for (let i = 0; i < cart.length; i++) {
        let bundle = cart[i];
        itemsHtml += `
        <div class="flex justify-between items-center text-sm mb-1">
            <div class="flex-1 text-gray-800 font-bold">${bundle.main.name}</div>
            <div class="w-8 text-center text-gray-800 font-bold">${bundle.main.qty}</div>
            <div class="w-24 text-right text-gray-800 font-bold">${bundle.main.price === 0 ? 'Gratis' : formatRupiah(bundle.main.price * bundle.main.qty)}</div>
        </div>`;
        
        for (let j = 0; j < bundle.addons.length; j++) {
            let addon = bundle.addons[j];
            itemsHtml += `
            <div class="flex justify-between items-center text-xs mb-1 text-gray-500 pl-3">
                <div class="flex-1 font-medium">+ ${addon.name}</div>
                <div class="w-8 text-center font-medium">${addon.qty}</div>
                <div class="w-24 text-right font-medium">${addon.price === 0 ? 'Gratis' : formatRupiah(addon.price * addon.qty)}</div>
            </div>`;
        }
        itemsHtml += `<div class="mb-2"></div>`;
    }

    let html = '';
    if (state === 'checkout') {
        let iconHtml = '';
        let title = '';
        let inputHtml = '';
        let btnText = 'Cek Pembayaran';

        if (selectedPaymentMethod === 'Cash') {
            iconHtml = `<svg class="w-16 h-16 mx-auto mb-3 text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>`;
            title = "PEMBAYARAN CASH";
            btnText = "Cetak Struk";
            inputHtml = `
                <div class="flex justify-between items-center text-sm font-semibold mb-3 text-gray-700 mt-4">
                    <span>Bayar (Cash)</span>
                    <div class="relative w-32">
                        <span class="absolute inset-y-0 left-2 flex items-center text-sm text-gray-500">Rp</span>
                        <input type="text" id="input-nominal" oninput="formatInputRupiah(this)" placeholder="0" class="w-full border border-gray-400 rounded px-2 pl-7 py-1 text-right text-sm focus:outline-none focus:border-black font-semibold">
                    </div>
                </div>
                
                <div class="flex flex-wrap justify-end gap-1.5 mb-4">
                    <button onclick="setNominal(${globalTotal})" class="px-2 py-1 border border-gray-300 rounded text-xs font-bold text-gray-700 hover:bg-gray-100 transition shadow-sm">Uang Pas</button>
                    <button onclick="setNominal(50000)" class="px-2 py-1 border border-gray-300 rounded text-xs font-bold text-gray-700 hover:bg-gray-100 transition shadow-sm">50.000</button>
                    <button onclick="setNominal(100000)" class="px-2 py-1 border border-gray-300 rounded text-xs font-bold text-gray-700 hover:bg-gray-100 transition shadow-sm">100.000</button>
                </div>

                <div class="flex justify-between items-center text-sm font-semibold text-gray-700">
                    <span>Kembali</span>
                    <span id="teks-kembali" class="w-32 border border-gray-300 bg-gray-100 rounded px-2 py-1 text-right text-sm text-gray-500">Rp 0</span>
                </div>
            `;
        } else if (selectedPaymentMethod === 'QRIS') {
            iconHtml = `<div class="w-24 h-24 mx-auto mb-3 bg-gray-200 flex items-center justify-center border-2 border-dashed border-gray-400 rounded-lg"><span class="text-xs font-bold text-gray-500">QR CODE</span></div>`;
            title = "PEMBAYARAN QRIS";
            inputHtml = `
                <div class="flex justify-between items-center text-sm font-semibold mb-2 text-gray-700 mt-4">
                    <span>Bayar (QRIS)</span>
                    <span>${formatRupiah(globalTotal)}</span>
                </div>
                <div class="flex justify-between items-center text-sm font-semibold text-gray-700">
                    <span>Kembali</span>
                    <span>Rp 0</span>
                </div>
            `;
        } else {
            iconHtml = `<svg class="w-20 h-20 mx-auto mb-3 text-blue-500" fill="currentColor" viewBox="0 0 24 24"><path d="M4 4h16a2 2 0 012 2v12a2 2 0 01-2 2H4a2 2 0 01-2-2V6a2 2 0 012-2zm0 4v10h16V8H4zm2 2h8v2H6v-2z"></path></svg>`;
            title = "PEMBAYARAN DEBIT";
            inputHtml = `
                <div class="flex justify-between items-center text-sm font-semibold mb-2 text-gray-700 mt-4">
                    <span>Bayar (Debit)</span>
                    <span>${formatRupiah(globalTotal)}</span>
                </div>
                <div class="flex justify-between items-center text-sm font-semibold text-gray-700">
                    <span>Kembali</span>
                    <span>Rp 0</span>
                </div>
            `;
        }

        html = `
            <div class="text-center mb-4">
                ${iconHtml}
                <h3 class="font-bold text-lg tracking-wide uppercase">${title}</h3>
            </div>
            <hr class="border-black mb-3">
            <div class="flex justify-between text-sm font-bold mb-3">
                <span>Produk</span>
                <span>Harga</span>
            </div>
            <div class="mb-2">
                ${itemsHtml}
            </div>
            <hr class="border-black mb-3">
            <div class="flex justify-between text-sm mb-1 text-gray-600">
                <span>Total QTY : ${globalTotalQty}</span>
            </div>
            <div class="flex justify-between text-sm font-semibold mb-1 text-gray-600">
                <span>Subtotal</span>
                <span>${formatRupiah(globalSubtotal)}</span>
            </div>
            <div class="flex justify-between text-sm font-semibold mb-1 text-gray-600">
                <span>Pajak (11%)</span>
                <span>${formatRupiah(globalTax)}</span>
            </div>
            <div class="flex justify-between text-lg font-bold mb-4 mt-2 text-gray-900 border-t border-gray-300 pt-2">
                <span>Total Bayar</span>
                <span>${formatRupiah(globalTotal)}</span>
            </div>
            ${inputHtml}
            <div class="mt-6 flex justify-center">
                <button id="btn-verify" onclick="verifyPayment()" class="border-2 border-gray-300 rounded-lg px-6 py-2.5 font-bold text-gray-700 hover:bg-gray-100 transition shadow-sm w-full">${btnText}</button>
            </div>
        `;
    } else if (state === 'success') {
        html = `
            <div class="text-center mb-4 mt-2" id="success-icon">
                <div class="w-16 h-16 mx-auto bg-blue-500 rounded-full flex items-center justify-center mb-3">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path></svg>
                </div>
                <h3 class="font-bold text-lg tracking-wide uppercase">PEMBAYARAN BERHASIL</h3>
            </div>
            <hr class="border-black mb-3">
            <div class="flex justify-between text-sm font-bold mb-3">
                <span>Produk</span>
                <span>Harga</span>
            </div>
            <div class="mb-2">
                ${itemsHtml}
            </div>
            <hr class="border-black mb-3">
            <div class="flex justify-between text-sm font-semibold mb-1 text-gray-600">
                <span>Subtotal</span>
                <span>${formatRupiah(globalSubtotal)}</span>
            </div>
            <div class="flex justify-between text-sm font-semibold mb-1 text-gray-600">
                <span>Pajak (11%)</span>
                <span>${formatRupiah(globalTax)}</span>
            </div>
            <div class="flex justify-between text-lg font-bold mb-4 mt-2 text-gray-900 border-t border-gray-300 pt-2">
                <span>Total Bayar</span>
                <span>${formatRupiah(globalTotal)}</span>
            </div>
            <div class="mt-6 flex justify-center">
                <button onclick="closeModal()" class="border-2 border-gray-300 rounded-lg px-6 py-2.5 font-bold text-gray-700 hover:bg-gray-100 transition shadow-sm w-full">Tutup & Selesai</button>
            </div>
        `;
    }

    container.innerHTML = html;
}
