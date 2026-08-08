@extends('layouts.master')

@section('isi')
<div class="container-fluid pt-4">
    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm mb-4" style="background-color: #22C55E; color: white;">
            <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger border-0 shadow-sm mb-4" style="background-color: #EF4444; color: white;">
            <i class="fas fa-exclamation-circle mr-2"></i> {{ session('error') }}
        </div>
    @endif

    <div class="row">
        <!-- SISI KIRI: FORM CEK INVOICE & PROSES RETUR -->
        <div class="col-md-5">
            <div class="card border-0 shadow-sm bg-white mb-4">
                <div class="card-body p-4">
                    <h5 class="font-weight-bold mb-4" style="color: #334155;">🔄 Input Retur Penjualan</h5>
                    
                    <div class="form-group mb-3">
                        <label class="small font-weight-bold text-uppercase text-muted">Cari Nomor Invoice</label>
                        <div class="input-group">
                            <input type="text" id="input_invoice" class="form-control" placeholder="Contoh: POS-20260517-0001">
                            <div class="input-group-append">
                                <button type="button" class="btn text-white" id="btn_cek" style="background-color: #B69377;">Cek</button>
                            </div>
                        </div>
                    </div>

                    <!-- Form ini akan muncul via Javascript jika invoice ditemukan -->
                    <form action="{{ route('retur.store') }}" method="POST" id="form_proses_retur" style="display: none;">
                        @csrf
                        <input type="hidden" name="penjualan_id" id="res_penjualan_id">
                        
                        <div class="bg-light p-3 rounded-lg mb-3">
                            <p class="small text-muted mb-1">Ringkasan Transaksi:</p>
                            <div class="d-flex justify-content-between font-weight-bold mb-1">
                                <span>Nilai Invoice:</span>
                                <span class="text-dark" id="text_total_harga">Rp 0</span>
                            </div>
                            <div class="small text-muted" id="text_detail_barang"></div>
                        </div>

                        <div class="form-group mb-4">
                            <label class="small font-weight-bold text-uppercase text-muted">Alasan Pengembalian</label>
                            <textarea name="alasan" class="form-control" rows="3" placeholder="Contoh: Barang cacat produksi / kedaluwarsa" required style="border-radius: 8px;"></textarea>
                        </div>

                        <button type="submit" class="btn btn-danger btn-block btn-lg" style="background-color: #EF4444; border: none; border-radius: 8px;">
                            <i class="fas fa-undo mr-2"></i> Eksekusi Refund & Stok
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- SISI KANAN: TABEL RIWAYAT RETUR -->
        <div class="col-md-7">
            <div class="card border-0 shadow-sm bg-white">
                <div class="card-body p-4">
                    <h5 class="font-weight-bold mb-4" style="color: #334155;">📜 Riwayat Refund Dana</h5>
                    
                    <div class="table-responsive">
                        <table class="table table-borderless align-middle">
                            <thead>
                                <tr>
                                    <th class="rounded-left" style="background-color: #1E293B; color: white;">Invoice</th>
                                    <th style="background-color: #1E293B; color: white;">Dana Refund</th>
                                    <th class="rounded-right" style="background-color: #1E293B; color: white;">Alasan / Keterangan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($retur as $r)
                                <tr style="border-bottom: 1px solid #E2E8F0; transition: background 0.2s;" onmouseover="this.style.backgroundColor='#FDF8F3'" onmouseout="this.style.backgroundColor='transparent'">
                                    <td class="font-weight-bold" style="color: #334155;">{{ $r->penjualan->no_invoice }}</td>
                                    <td class="font-weight-bold text-danger">Rp {{ number_format($r->total_refund, 0, ',', '.') }}</td>
                                    <td><small class="text-muted">{{ $r->alasan }}</small></td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="text-center text-muted py-4">Belum ada riwayat pengembalian barang.</td>
                                
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- AJAX SCRIPT UNTUK EFISIENSI KASIR -->
<script>
document.addEventListener("DOMContentLoaded", function() {
    const btnCek = document.getElementById('btn_cek');
    
    btnCek.addEventListener('click', function() {
        const noInvoice = document.getElementById('input_invoice').value;
        if(!noInvoice) return alert('Ketik nomor invoice terlebih dahulu!');

        fetch(`/ambil-invoice/${noInvoice}`)
            .then(response => response.json())
            .then(res => {
                if(res.status === 'success') {
                    document.getElementById('form_proses_retur').style.display = 'block';
                    document.getElementById('res_penjualan_id').value = res.data.id;
                    document.getElementById('text_total_harga').innerText = 'Rp ' + new Intl.NumberFormat('id-ID').format(res.data.total_harga);
                    
                    let barangHtml = '<ul>';
                    res.data.detail_penjualan.forEach(item => {
                        barangHtml += `<li>${item.barang.nama_barang} (${item.qty} pcs)</li>`;
                    });
                    barangHtml += '</ul>';
                    document.getElementById('text_detail_barang').innerHTML = barangHtml;
                } else {
                    alert(res.message);
                    document.getElementById('form_proses_retur').style.display = 'none';
                }
            });
    });
});
</script>
@endsection