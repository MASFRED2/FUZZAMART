@extends('layouts.master')
@section('judul','Manajemen Kategori')
@section('isi')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center"><h5 class="mb-0 font-weight-bold">Daftar Kategori Barang</h5><button class="btn btn-primary" data-toggle="modal" data-target="#modalTambah"><i class="fas fa-plus mr-1"></i>Tambah Kategori</button></div>
    <div class="card-body table-responsive p-0">
        <table class="table table-hover mb-0"><thead><tr><th>No</th><th>Nama Kategori</th><th>Jumlah Barang</th><th width="150">Aksi</th></tr></thead><tbody>
            @forelse($kategori as $k)
                <tr><td>{{ $loop->iteration }}</td><td><strong>{{ $k->nama_kategori }}</strong></td><td><span class="badge badge-info">{{ $k->barang_count }} barang</span></td><td>
                    <button class="btn btn-info btn-sm" data-toggle="modal" data-target="#edit{{ $k->id }}"><i class="fas fa-edit"></i></button>
                    <form action="{{ route('kategori.destroy',$k->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus kategori ini?')">@csrf @method('DELETE')<button class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button></form>
                </td></tr>
                <div class="modal fade" id="edit{{ $k->id }}"><div class="modal-dialog"><form action="{{ route('kategori.update',$k->id) }}" method="POST">@csrf @method('PUT')<div class="modal-content"><div class="modal-header"><h5>Edit Kategori</h5><button type="button" class="close" data-dismiss="modal">&times;</button></div><div class="modal-body"><label>Nama Kategori</label><input name="nama_kategori" value="{{ $k->nama_kategori }}" class="form-control" required></div><div class="modal-footer"><button type="button" class="btn btn-light" data-dismiss="modal">Batal</button><button class="btn btn-primary">Simpan</button></div></div></form></div></div>
            @empty
                <tr><td colspan="4" class="text-center text-muted py-4">Belum ada kategori.</td></tr>
            @endforelse
        </tbody></table>
    </div>
</div>
<div class="modal fade" id="modalTambah"><div class="modal-dialog"><form action="{{ route('kategori.store') }}" method="POST">@csrf<div class="modal-content"><div class="modal-header"><h5>Tambah Kategori</h5><button type="button" class="close" data-dismiss="modal">&times;</button></div><div class="modal-body"><label>Nama Kategori</label><input type="text" name="nama_kategori" class="form-control" required></div><div class="modal-footer"><button type="button" class="btn btn-light" data-dismiss="modal">Batal</button><button class="btn btn-primary">Simpan</button></div></div></form></div></div>
@endsection
