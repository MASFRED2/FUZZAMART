@extends('layouts.master')

@section('judul', 'Manajemen Diskon Barang')

@section('isi')
<div class="card">
    <div class="card-header">
        <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modalDiskon">
            <i class="fas fa-percent"></i> Buat Diskon Baru
        </button>
    </div>
    <div class="card-body">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Barang</th>
                    <th>Potongan</th>
                    <th>Minimal Beli</th>
                    <th>Periode</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($diskon as $d)
                <tr>
                    <td>{{ $d->barang->nama_barang }}</td>
                    <td>
                        {{ $d->jenis_diskon == 'persentase' ? $d->nilai_diskon.'%' : 'Rp '.number_format($d->nilai_diskon) }}
                    </td>
                    <td>{{ $d->minimal_beli }} pcs</td>
                    <td>{{ date('d M', strtotime($d->tgl_mulai)) }} - {{ date('d M Y', strtotime($d->tgl_selesai)) }}</td>
                    <td>
                        @if(now()->between($d->tgl_mulai, $d->tgl_selesai))
                            <span class="badge badge-success">Aktif</span>
                        @else
                            <span class="badge badge-secondary">Menunggu/Berakhir</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

{{-- Modal Tambah Diskon --}}
<div class="modal fade" id="modalDiskon">
    <div class="modal-dialog">
        <form action="{{ route('diskon.store') }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header"><h4>Set Jadwal Diskon</h4></div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Pilih Barang</label>
                        <select name="barang_id" class="form-control" required>
                            @foreach($barang as $b)
                                <option value="{{ $b->id }}">{{ $b->nama_barang }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <label>Jenis</label>
                            <select name="jenis_diskon" class="form-control">
                                <option value="persentase">Persentase (%)</option>
                                <option value="nominal">Nominal (Rp)</option>
                            </select>
                        </div>
                        <div class="col-6">
                            <label>Nilai Potongan</label>
                            <input type="number" name="nilai_diskon" class="form-control" required>
                        </div>
                    </div>
                    <div class="form-group mt-2">
                        <label>Minimal Pembelian (Qty)</label>
                        <input type="number" name="minimal_beli" class="form-control" value="1">
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <label>Tgl Mulai</label>
                            <input type="date" name="tgl_mulai" class="form-control" required>
                        </div>
                        <div class="col-6">
                            <label>Tgl Selesai</label>
                            <input type="date" name="tgl_selesai" class="form-control" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer"><button type="submit" class="btn btn-primary">Simpan Jadwal</button></div>
            </div>
        </form>
    </div>
</div>
@endsection