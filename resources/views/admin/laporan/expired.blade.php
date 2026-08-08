@extends('layouts.master')

@section('judul', 'Kontrol Barang Expired')

@section('isi')
<div class="card">
    <div class="card-header bg-danger text-white">
        <h3 class="card-title">Peringatan Expired (30 Hari ke Depan)</h3>
    </div>
    <div class="card-body">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Nama Barang</th>
                    <th>Kode Batch</th>
                    <th>Sisa Stok Batch</th>
                    <th>Tgl Expired</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data as $sm)
                <tr class="{{ $sm->tgl_kadaluwarsa < now() ? 'table-danger' : '' }}">
                    <td>{{ $sm->barang->nama_barang }}</td>
                    <td>Batch-{{ $sm->id }}</td>
                    <td>{{ $sm->jumlah_sisa }}</td>
                    <td>{{ $sm->tgl_kadaluwarsa->format('d/m/Y') }}</td>
                    <td>
                        @if($sm->tgl_kadaluwarsa < now())
                            <span class="badge badge-danger">SUDAH EXPIRED</span>
                        @else
                            <span class="badge badge-warning">Mendekati Expired</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection