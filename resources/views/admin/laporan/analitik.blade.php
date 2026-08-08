@extends('layouts.master')

@section('judul', 'Analitik Produk (Slow Moving)')

@section('isi')
<div class="row">
    <div class="col-md-12">
        <div class="card card-info">
            <div class="card-header">
                <h3 class="card-title">10 Barang Paling Jarang Dibeli</h3>
            </div>
            <div class="card-body">
                <p class="text-muted small">*Data berdasarkan frekuensi munculnya barang dalam struk belanja.</p>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Nama Barang</th>
                            <th>Total Kali Dibeli</th>
                            <th>Stok Saat Ini</th>
                            <th>Saran Tindakan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($slowMoving as $b)
                        <tr>
                            <td>{{ $b->nama_barang }}</td>
                            <td>{{ $b->penjualan_detail_count }} kali</td>
                            <td>{{ $b->stok_total }}</td>
                            <td>
                                @if($b->penjualan_detail_count == 0)
                                    <span class="text-danger">Berikan Diskon Besar / Hentikan Stok</span>
                                @else
                                    <span class="text-warning">Promosikan di Dashboard Customer</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection