@extends('layouts.master')

@section('judul', 'Dashboard Operasional')

@section('isi')
<div class="row">
    <div class="col-lg-3 col-6"><div class="small-box bg-success"><div class="inner"><h3>Rp {{ number_format($totalPenjualanHariIni,0,',','.') }}</h3><p>Penjualan Hari Ini</p></div><div class="icon"><i class="fas fa-money-bill-wave"></i></div></div></div>
    <div class="col-lg-3 col-6"><div class="small-box bg-info"><div class="inner"><h3>{{ $jumlahTransaksiHariIni }}</h3><p>Transaksi Hari Ini</p></div><div class="icon"><i class="fas fa-receipt"></i></div></div></div>
    <div class="col-lg-3 col-6"><div class="small-box bg-warning"><div class="inner"><h3>{{ $stokRendah }}</h3><p>Barang Stok Rendah</p></div><div class="icon"><i class="fas fa-exclamation-triangle"></i></div></div></div>
    <div class="col-lg-3 col-6"><div class="small-box bg-danger"><div class="inner"><h3>{{ $expiredDekat }}</h3><p>Barang Mendekati Expired</p></div><div class="icon"><i class="fas fa-calendar-times"></i></div></div></div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0 font-weight-bold"><i class="fas fa-clock mr-2"></i>Transaksi Terbaru</h5>
                <a href="{{ route('penjualan.riwayat') }}" class="btn btn-sm btn-dark">Lihat Semua</a>
            </div>
            <div class="card-body table-responsive p-0">
                <table class="table table-hover mb-0">
                    <thead><tr><th>Invoice</th><th>Kasir</th><th>Total</th><th>Status</th><th>Waktu</th></tr></thead>
                    <tbody>
                        @forelse($transaksiTerbaru as $trx)
                            <tr>
                                <td><a href="{{ route('penjualan.cetak',$trx->id) }}" target="_blank" class="font-weight-bold">{{ $trx->no_invoice }}</a></td>
                                <td>{{ $trx->kasir?->name ?? '-' }}</td>
                                <td>Rp {{ number_format($trx->total_harga,0,',','.') }}</td>
                                <td><span class="badge badge-{{ $trx->status_pembayaran === 'retur' ? 'danger' : 'success' }}">{{ strtoupper($trx->status_pembayaran) }}</span></td>
                                <td>{{ $trx->created_at->format('d/m/Y H:i') }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="text-center text-muted py-4">Belum ada transaksi.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header"><h5 class="mb-0 font-weight-bold"><i class="fas fa-box-open mr-2"></i>Stok Perlu Perhatian</h5></div>
            <div class="card-body p-0">
                <table class="table table-sm mb-0">
                    <thead><tr><th>Barang</th><th>Stok</th></tr></thead>
                    <tbody>
                        @forelse($barangKritis as $b)
                            <tr>
                                <td>{{ $b->nama_barang }}<br><small class="text-muted">Min: {{ $b->stok_minimal }}</small></td>
                                <td><span class="badge badge-{{ $b->stok_total <= 0 ? 'danger' : 'warning' }}">{{ $b->stok_total }}</span></td>
                            </tr>
                        @empty
                            <tr><td colspan="2" class="text-center text-muted py-4">Stok aman.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card">
            <div class="card-header"><h5 class="mb-0 font-weight-bold"><i class="fas fa-user-clock mr-2"></i>Shift Aktif</h5></div>
            <div class="card-body">
                @forelse($shiftAktif as $s)
                    <div class="d-flex justify-content-between border-bottom py-2">
                        <span>{{ $s->user?->name }}</span><small>{{ $s->waktu_buka->format('H:i') }}</small>
                    </div>
                @empty
                    <p class="text-muted mb-0">Belum ada shift aktif.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
