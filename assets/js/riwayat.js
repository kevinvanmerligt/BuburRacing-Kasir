function toggleSort() {
    const dropdown = document.getElementById('sort-dropdown');
    if (dropdown) {
        dropdown.classList.toggle('hidden');
    }
}

window.onclick = function (event) {
    if (!event.target.closest('button[onclick="toggleSort()"]')) {
        const dropdowns = document.getElementsByClassName("absolute");
        for (let i = 0; i < dropdowns.length; i++) {
            let openDropdown = dropdowns[i];
            if (openDropdown.id === 'sort-dropdown' && !openDropdown.classList.contains('hidden')) {
                openDropdown.classList.add('hidden');
            }
        }
    }
};

function formatRupiah(angka) {
    return 'Rp. ' + parseInt(angka).toLocaleString('id-ID');
}

function searchRiwayat() {
    const searchInput = document.getElementById('search-input');
    if (!searchInput) return;
    let filter = searchInput.value.toUpperCase();
    let rows = document.querySelectorAll('tbody tr');
    
    rows.forEach(row => {
        let orderIdCol = row.querySelector('td:nth-child(1)');
        if (orderIdCol) {
            let textValue = orderIdCol.textContent || orderIdCol.innerText;
            if (textValue.toUpperCase().indexOf(filter) > -1) {
                row.style.display = "";
            } else {
                row.style.display = "none";
            }
        }
    });
}

window.searchProduct = searchRiwayat;

function openDetailModal(data) {
    document.getElementById('modal-order-id').innerText = data.order_id;
    document.getElementById('modal-tanggal').innerText = 'pada ' + data.tanggal;
    
    let produkHtml = '';
    data.detail_produk.forEach(item => {
        let hargaText = item.harga === 0 ? 'Gratis' : formatRupiah(item.total_harga);
        
        produkHtml += `
        <div class="flex justify-between items-start text-sm mb-1 ${item.is_toping ? 'text-gray-500 pl-2' : 'font-bold text-gray-800'}">
            <div class="flex-1">${item.nama}</div>
            <div class="w-8 text-center">${item.qty}</div>
            <div class="w-24 text-right">${hargaText}</div>
        </div>
        `;
    });
    document.getElementById('modal-produk-list').innerHTML = produkHtml;

    document.getElementById('modal-qty').innerText = data.qty;
    document.getElementById('modal-subtotal').innerText = formatRupiah(data.subtotal);
    document.getElementById('modal-total').innerText = formatRupiah(data.total);
    document.getElementById('modal-metode').innerText = data.metode;
    document.getElementById('modal-bayar').innerText = formatRupiah(data.uang);
    document.getElementById('modal-kembali').innerText = formatRupiah(data.kembali);

    document.getElementById('detail-modal').classList.remove('hidden');
}

function closeDetailModal() {
    document.getElementById('detail-modal').classList.add('hidden');
}

document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.getElementById('search-input');
    if (searchInput) {
        searchInput.addEventListener('input', searchRiwayat);
    }
});
