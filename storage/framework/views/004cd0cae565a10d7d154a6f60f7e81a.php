<?php $__env->startSection('judul','Kasir POS'); ?>

<?php $__env->startPush('styles'); ?>
<style>
    .pos-total{font-size:2.8rem;font-weight:900;letter-spacing:-1px}.qty-control{max-width:86px}.product-empty{border:2px dashed #dbe3ef;border-radius:16px;padding:42px;text-align:center;color:#94a3b8}.sticky-payment{position:sticky;top:80px}@media(max-width:992px){.sticky-payment{position:static}.pos-total{font-size:2rem}}
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('isi'); ?>
<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div><strong>Shift Aktif</strong><br><small class="text-muted">Buka: <?php echo e($shiftAktif->waktu_buka->format('d/m/Y H:i')); ?></small></div>
                <button type="button" onclick="simpanDraft()" class="btn btn-light btn-sm no-print"><i class="fas fa-save mr-1"></i>Hold Transaksi</button>
            </div>
            <div class="card-body">
                <div class="input-group input-group-lg mb-3">
                    <div class="input-group-prepend"><span class="input-group-text bg-white"><i class="fas fa-barcode"></i></span></div>
                    <input type="text" id="scan_barcode" class="form-control" placeholder="Scan barcode / ketik nama barang lalu Enter" autofocus>
                    <div class="input-group-append"><button type="button" class="btn btn-dark" onclick="cariBarangManual()"><i class="fas fa-search"></i></button></div>
                </div>
                <div class="alert alert-info py-2"><i class="fas fa-keyboard mr-1"></i>Shortcut: <b>Enter</b> tambah barang, <b>F10</b> proses bayar, <b>Esc</b> fokus ke input barcode.</div>
                <div class="table-responsive">
                    <table class="table table-hover" id="tabel_keranjang">
                        <thead><tr><th>Produk</th><th>Harga</th><th>Qty</th><th>Subtotal</th><th width="60">Aksi</th></tr></thead>
                        <tbody id="keranjang_body"><tr><td colspan="5"><div class="product-empty"><i class="fas fa-shopping-basket fa-3x mb-3"></i><br>Keranjang masih kosong. Scan barang untuk memulai transaksi.</div></td></tr></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card sticky-payment">
            <div class="card-body">
                <small class="text-muted text-uppercase font-weight-bold">Total Pembayaran</small>
                <div id="total_tampilan" data-total="0" class="pos-total text-right">Rp 0</div>
                <hr>
                <div class="form-group"><label>Member/Pelanggan</label><select id="pelanggan_id" class="custom-select"><option value="">Umum / Non-member</option><?php $__currentLoopData = $pelanggan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($p->id); ?>"><?php echo e($p->name); ?> <?php echo e($p->telepon ? ' - '.$p->telepon : ''); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></select></div>
                <div class="form-group"><label>Metode Pembayaran</label><select id="metode_bayar" class="custom-select"><option value="cash">Tunai</option><option value="qris">QRIS/E-Wallet</option><option value="debit">Debit</option><option value="transfer">Transfer</option></select></div>
                <div class="form-group" id="input_bayar_tunai"><label>Uang Diterima</label><input type="number" id="nominal_bayar" class="form-control form-control-lg text-right font-weight-bold" placeholder="0"></div>
                <div class="bg-light rounded p-3 mb-3 d-flex justify-content-between"><span>Kembalian</span><strong id="kembalian_tampilan">Rp 0</strong></div>
                <button type="button" id="btn_proses" class="btn btn-warning btn-block btn-lg py-3"><i class="fas fa-check-circle mr-2"></i>PROSES BAYAR</button>
                <button type="button" onclick="kosongkanKeranjang()" class="btn btn-outline-danger btn-block mt-2"><i class="fas fa-trash mr-1"></i>Kosongkan</button>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
let keranjang = [];
const inputBarcode = document.getElementById('scan_barcode');
const nominalBayar = document.getElementById('nominal_bayar');
const btnProses = document.getElementById('btn_proses');
const metodeBayar = document.getElementById('metode_bayar');

function formatRupiah(n){ return 'Rp ' + new Intl.NumberFormat('id-ID').format(Number(n || 0)); }
function toast(msg, type='info'){ if(window.toastr){ toastr[type](msg); } else { alert(msg); } }

function ambilDataBarang(keyword){
    keyword = (keyword || '').trim();
    if(!keyword) return;
    let url = "<?php echo e(route('kasir.get-barang', 'KODE_BARCODE')); ?>".replace('KODE_BARCODE', encodeURIComponent(keyword));
    fetch(url).then(async response => {
        const res = await response.json().catch(() => ({}));
        if(!response.ok) throw new Error(res.message || 'Barang tidak ditemukan');
        return res;
    }).then(res => {
        if(res.status === 'success') tambahKeKeranjang(res.data); else toast(res.message || 'Barang tidak ditemukan', 'warning');
    }).catch(err => toast(err.message || 'Gagal mengambil data barang', 'error'));
}

function tambahKeKeranjang(barang){
    if(Number(barang.stok_total) <= 0){ toast('Stok ' + barang.nama_barang + ' habis.', 'warning'); return; }
    let index = keranjang.findIndex(item => item.id === barang.id);
    if(index >= 0){
        if(keranjang[index].qty + 1 > barang.stok_total){ toast('Stok tidak mencukupi. Sisa: ' + barang.stok_total, 'warning'); return; }
        keranjang[index].qty++;
    } else {
        keranjang.push({ id: barang.id, barcode: barang.barcode, nama_barang: barang.nama_barang, harga: Number(barang.harga_final || barang.harga_jual), stok_maksimal: Number(barang.stok_total), qty: 1, ada_diskon: barang.ada_diskon });
    }
    renderKeranjang();
}

function renderKeranjang(){
    const body = document.getElementById('keranjang_body');
    body.innerHTML = '';
    if(keranjang.length === 0){
        body.innerHTML = '<tr><td colspan="5"><div class="product-empty"><i class="fas fa-shopping-basket fa-3x mb-3"></i><br>Keranjang masih kosong. Scan barang untuk memulai transaksi.</div></td></tr>';
        hitungTotal(); return;
    }
    keranjang.forEach((item, index) => {
        const subtotal = item.harga * item.qty;
        body.innerHTML += `<tr>
            <td><strong>${item.nama_barang}</strong><br><small class="text-muted">${item.barcode}${item.ada_diskon ? ' · diskon aktif' : ''}</small></td>
            <td>${formatRupiah(item.harga)}</td>
            <td><input type="number" class="form-control qty-control" value="${item.qty}" min="1" max="${item.stok_maksimal}" onchange="updateQty(${index}, this.value)"></td>
            <td><strong>${formatRupiah(subtotal)}</strong></td>
            <td><button type="button" class="btn btn-sm btn-light text-danger" onclick="hapusItem(${index})"><i class="fas fa-times"></i></button></td>
        </tr>`;
    });
    hitungTotal();
}

function updateQty(index, value){
    let qty = parseInt(value || 1);
    if(qty < 1) qty = 1;
    if(qty > keranjang[index].stok_maksimal){ qty = keranjang[index].stok_maksimal; toast('Qty disesuaikan dengan stok tersedia.', 'warning'); }
    keranjang[index].qty = qty;
    renderKeranjang();
}
function hapusItem(index){ keranjang.splice(index,1); renderKeranjang(); }
function kosongkanKeranjang(){ if(keranjang.length && !confirm('Kosongkan keranjang?')) return; keranjang=[]; localStorage.removeItem('draft_pos_fuzza'); renderKeranjang(); inputBarcode.focus(); }
function simpanDraft(){ localStorage.setItem('draft_pos_fuzza', JSON.stringify(keranjang)); toast('Transaksi berhasil di-hold di browser ini.', 'success'); }
function loadDraft(){ const draft = localStorage.getItem('draft_pos_fuzza'); if(draft && confirm('Ada transaksi hold. Muat kembali?')){ keranjang = JSON.parse(draft); renderKeranjang(); } }
function hitungTotal(){ const total = keranjang.reduce((sum,item)=>sum+(item.harga*item.qty),0); document.getElementById('total_tampilan').dataset.total = total; document.getElementById('total_tampilan').innerText = formatRupiah(total); hitungKembalian(); }
function hitungKembalian(){ const total = Number(document.getElementById('total_tampilan').dataset.total || 0); const bayar = Number(nominalBayar.value || 0); document.getElementById('kembalian_tampilan').innerText = formatRupiah(Math.max(0, bayar-total)); }
function cariBarangManual(){ const key = prompt('Masukkan nama barang atau barcode:'); if(key) ambilDataBarang(key); }

inputBarcode.addEventListener('keypress', e => { if(e.key === 'Enter'){ e.preventDefault(); ambilDataBarang(inputBarcode.value); inputBarcode.value=''; }});
nominalBayar.addEventListener('input', hitungKembalian);
metodeBayar.addEventListener('change', function(){ document.getElementById('input_bayar_tunai').style.display = this.value === 'cash' ? 'block' : 'none'; hitungKembalian(); });
document.addEventListener('keydown', e => { if(e.key === 'F10'){ e.preventDefault(); btnProses.click(); } if(e.key === 'Escape'){ inputBarcode.focus(); }});

btnProses.addEventListener('click', function(){
    if(keranjang.length === 0){ toast('Keranjang masih kosong.', 'warning'); return; }
    const metode = metodeBayar.value;
    const total = Number(document.getElementById('total_tampilan').dataset.total || 0);
    const bayar = metode === 'cash' ? Number(nominalBayar.value || 0) : total;
    if(metode === 'cash' && bayar < total){ toast('Uang diterima kurang dari total pembayaran.', 'warning'); return; }
    if(!confirm('Proses pembayaran sekarang?')) return;

    btnProses.disabled = true; btnProses.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Memproses...';
    fetch("<?php echo e(route('penjualan.store')); ?>", { method:'POST', headers:{'Content-Type':'application/json','X-CSRF-TOKEN':'<?php echo e(csrf_token()); ?>'}, body: JSON.stringify({keranjang, metode_pembayaran: metode, pelanggan_id: document.getElementById('pelanggan_id').value || null, total_bayar: bayar}) })
    .then(async response => { const res = await response.json().catch(() => ({})); if(!response.ok) throw new Error(res.message || 'Transaksi gagal'); return res; })
    .then(res => { localStorage.removeItem('draft_pos_fuzza'); window.location.href = `/transaksi/cetak/${res.id_penjualan}`; })
    .catch(err => { toast(err.message, 'error'); btnProses.disabled=false; btnProses.innerHTML='<i class="fas fa-check-circle mr-2"></i>PROSES BAYAR'; });
});

loadDraft(); renderKeranjang();
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Users\Albar\Downloads\PROJECT_KP_FUZZA_MART_FIXED\PROJECT-KP\resources\views/admin/penjualan/index.blade.php ENDPATH**/ ?>