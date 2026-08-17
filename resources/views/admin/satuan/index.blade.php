@extends('layouts.master')

@section('judul', 'Master Satuan Barang')

@section('isi')
<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between">
                <div>
                    <h3 class="card-title font-weight-bold">Daftar Satuan</h3>
                    <small class="text-muted d-block">Satuan dipakai sebagai kemasan dasar maupun kemasan jual barang.</small>
                </div>
                <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modalTambahSatuan">
                    <i class="fas fa-plus mr-1"></i> Satuan
                </button>
            </div>
            <div class="card-body table-responsive p-0">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr><th>Nama</th><th>Simbol</th><th>Dipakai Produk</th><th>Status</th><th width="130">Aksi</th></tr>
                    </thead>
                    <tbody>
                        @forelse($satuan as $item)
                            <tr>
                                <td class="font-weight-bold">{{ $item->nama_satuan }}</td>
                                <td><span class="badge badge-dark">{{ $item->simbol }}</span></td>
                                <td>{{ $item->barang_satuan_count }} kemasan</td>
                                <td><span class="badge badge-{{ $item->is_active ? 'success' : 'secondary' }}">{{ $item->is_active ? 'Aktif' : 'Nonaktif' }}</span></td>
                                <td>
                                    <button class="btn btn-info btn-sm" data-toggle="modal" data-target="#modalEditSatuan{{ $item->id }}"><i class="fas fa-edit"></i></button>
                                    <form action="{{ route('satuan.destroy', $item) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus satuan ini?')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-danger btn-sm" @disabled($item->barang_satuan_count > 0)><i class="fas fa-trash"></i></button>
                                    </form>
                                </td>
                            </tr>

                            <div class="modal fade" id="modalEditSatuan{{ $item->id }}">
                                <div class="modal-dialog">
                                    <form action="{{ route('satuan.update', $item) }}" method="POST">
                                        @csrf @method('PUT')
                                        <div class="modal-content">
                                            <div class="modal-header"><h5 class="modal-title">Edit Satuan</h5><button type="button" class="close" data-dismiss="modal">&times;</button></div>
                                            <div class="modal-body">
                                                <div class="form-group"><label>Nama Satuan</label><input type="text" name="nama_satuan" value="{{ $item->nama_satuan }}" class="form-control" required></div>
                                                <div class="form-group"><label>Simbol</label><input type="text" name="simbol" value="{{ $item->simbol }}" class="form-control" maxlength="20" required><small class="text-muted">Contoh: pcs, pak, renteng, dus.</small></div>
                                                <div class="custom-control custom-switch">
                                                    <input type="checkbox" class="custom-control-input" id="satuanAktif{{ $item->id }}" name="is_active" value="1" @checked($item->is_active)>
                                                    <label class="custom-control-label" for="satuanAktif{{ $item->id }}">Dapat dipilih pada barang baru</label>
                                                </div>
                                            </div>
                                            <div class="modal-footer"><button type="button" class="btn btn-light" data-dismiss="modal">Batal</button><button class="btn btn-primary">Simpan</button></div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        @empty
                            <tr><td colspan="5" class="text-center text-muted py-4">Belum ada satuan.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card bg-light">
            <div class="card-body">
                <h5 class="font-weight-bold"><i class="fas fa-info-circle mr-1 text-primary"></i> Cara Kerja</h5>
                <p class="text-muted mb-2">Satuan hanya mendefinisikan nama kemasan. Konversi dan harga ditentukan secara terpisah pada setiap barang.</p>
                <div class="small bg-white rounded p-3 border">
                    <strong>Contoh mi instan</strong><br>
                    pcs = satuan dasar<br>
                    renteng = 10 pcs<br>
                    dus = 40 pcs<br><br>
                    Harga setiap kemasan bebas ditentukan admin.
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalTambahSatuan">
    <div class="modal-dialog">
        <form action="{{ route('satuan.store') }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header"><h5 class="modal-title">Tambah Satuan</h5><button type="button" class="close" data-dismiss="modal">&times;</button></div>
                <div class="modal-body">
                    <div class="form-group"><label>Nama Satuan</label><input type="text" name="nama_satuan" class="form-control" placeholder="Contoh: Dus" required></div>
                    <div class="form-group"><label>Simbol</label><input type="text" name="simbol" class="form-control" placeholder="dus" maxlength="20" required><small class="text-muted">Gunakan simbol pendek dan konsisten.</small></div>
                </div>
                <div class="modal-footer"><button type="button" class="btn btn-light" data-dismiss="modal">Batal</button><button class="btn btn-primary">Simpan Satuan</button></div>
            </div>
        </form>
    </div>
</div>
@endsection
