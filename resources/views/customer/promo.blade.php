@extends('layouts.master')

@section('judul', 'Promo Spesial Untukmu')

@section('isi')
<div class="row">
    @forelse($promo as $p)
    <div class="col-md-4">
        <div class="card elevation-2">
            <div class="card-body text-center">
                <i class="fas fa-tag text-danger fa-3x mb-2"></i>
                <h5>{{ $p->barang->nama_barang }}</h5>
                <h4 class="text-success font-weight-bold">
                    Diskon {{ $p->jenis_diskon == 'persentase' ? $p->nilai_diskon.'%' : 'Rp '.number_format($p->nilai_diskon) }}
                </h4>
                <p class="text-muted">Berlaku s/d {{ date('d F Y', strtotime($p->tgl_selesai)) }}</p>
                <small>*Minimal pembelian {{ $p->minimal_beli }} pcs</small>
            </div>
        </div>
    </div>
    @empty
    <div class="col-12 text-center">
        <p>Belum ada promo aktif saat ini. Cek kembali nanti ya!</p>
    </div>
    @endforelse
</div>
@endsection