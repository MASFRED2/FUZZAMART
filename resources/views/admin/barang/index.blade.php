@extends('layouts.master')

@section('judul', 'Manajemen Data Barang')

@section('isi')
<div class="card mb-3">
    <div class="card-body">
        <form method="GET" class="row align-items-end">
            <div class="col-md-4 mb-2"><label>Cari Barang</label><input type="text" name="q" value="{{ request('q') }}" class="form-control" placeholder="Nama barang atau barcode"></div>
            <div class="col-md-3 mb-2"><label>Kategori</label><select name="kategori_id" class="custom-select"><option value="">Semua kategori</option>@foreach($kategori as $kat)<option value="{{ $kat->id }}" @selected(request('kategori_id')==$kat->id)>{{ $kat->nama_kategori }}</option>@endforeach</select></div>
            <div class="col-md-2 mb-2"><label>Status</label><select name="status" class="custom-select"><option value="aktif" @selected(request('status','aktif')==='aktif')>Aktif</option><option value="nonaktif" @selected(request('status')==='nonaktif')>Nonaktif</option></select></div>
            <div class="col-md-3 mb-2 d-flex"><button class="btn btn-dark mr-2"><i class="fas fa-search mr-1"></i>Filter</button><a href="{{ route('barang.index') }}" class="btn btn-light mr-2">Reset</a><button type="button" class="btn btn-primary ml-auto" data-toggle="modal" data-target="#modalTambahBarang"><i class="fas fa-plus mr-1"></i>Barang</button></div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body table-responsive p-0">
        <table class="table table-hover mb-0">
            <thead><tr><th>No</th><th>Barcode</th><th>Barang</th><th>Kategori</th><th>Harga</th><th>Stok</th><th>Status</th><th width="150">Aksi</th></tr></thead>
            <tbody>
                @forelse($barang as $b)
                    <tr>
                        <td>{{ $barang->firstItem() + $loop->index }}</td>
                        <td><span class="badge badge-secondary">{{ $b->barcode }}</span></td>
                        <td><strong>{{ $b->nama_barang }}</strong><br><small class="text-muted">Satuan: {{ $b->satuan ?? 'pcs' }}</small></td>
                        <td>{{ $b->kategori?->nama_kategori ?? '-' }}</td>
                        <td>Rp {{ number_format($b->harga_jual,0,',','.') }}</td>
                        <td>@if($b->stok_total <= 0)<span class="badge badge-danger">Habis</span>@elseif($b->stok_total <= $b->stok_minimal)<span class="badge badge-warning">{{ $b->stok_total }} rendah</span>@else<span class="badge badge-success">{{ $b->stok_total }}</span>@endif<br><small class="text-muted">Min: {{ $b->stok_minimal }}</small></td>
                        <td><span class="badge badge-{{ $b->is_active ? 'success' : 'secondary' }}">{{ $b->is_active ? 'Aktif' : 'Nonaktif' }}</span></td>
                        <td>
                            <button class="btn btn-info btn-sm" data-toggle="modal" data-target="#modalEdit{{ $b->id }}"><i class="fas fa-edit"></i></button>
                            <form action="{{ route('barang.destroy',$b->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus/arsipkan barang ini? Riwayat transaksi tetap aman.')">
                                @csrf @method('DELETE')
                                <button class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>

                    <div class="modal fade" id="modalEdit{{ $b->id }}">
                        <div class="modal-dialog modal-lg"><form action="{{ route('barang.update',$b->id) }}" method="POST">@csrf @method('PUT')
                            <div class="modal-content"><div class="modal-header"><h5 class="modal-title">Edit Barang</h5><button type="button" class="close" data-dismiss="modal">&times;</button></div>
                                <div class="modal-body"><div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group"><label>Barcode</label><input type="text" name="barcode" value="{{ $b->barcode }}" class="form-control" required></div>
                                        <div class="form-group"><label>Nama Barang</label><input type="text" name="nama_barang" value="{{ $b->nama_barang }}" class="form-control" required></div>
                                        <div class="form-group"><label>Kategori</label><select name="kategori_id" class="custom-select" required>@foreach($kategori as $kat)<option value="{{ $kat->id }}" @selected($b->kategori_id==$kat->id)>{{ $kat->nama_kategori }}</option>@endforeach</select></div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group"><label>Satuan</label><input type="text" name="satuan" value="{{ $b->satuan ?? 'pcs' }}" class="form-control"></div>
                                        <div class="form-group"><label>Harga Jual</label><input type="number" name="harga_jual" value="{{ $b->harga_jual }}" class="form-control" required></div>
                                        <div class="form-group"><label>Harga Beli Terakhir</label><input type="number" name="harga_beli_terakhir" value="{{ $b->harga_beli_terakhir }}" class="form-control"></div>
                                        <div class="form-group"><label>Stok Minimal</label><input type="number" name="stok_minimal" value="{{ $b->stok_minimal }}" class="form-control" required></div>
                                        <div class="custom-control custom-switch"><input type="checkbox" class="custom-control-input" id="aktif{{ $b->id }}" name="is_active" value="1" @checked($b->is_active)><label class="custom-control-label" for="aktif{{ $b->id }}">Barang aktif dijual</label></div>
                                    </div>
                                </div></div>
                                <div class="modal-footer"><button type="button" class="btn btn-light" data-dismiss="modal">Batal</button><button class="btn btn-primary">Simpan Perubahan</button></div>
                            </div>
                        </form></div>
                    </div>
                @empty
                    <tr><td colspan="8" class="text-center text-muted py-4">Data barang belum tersedia.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-footer">{{ $barang->links() }}</div>
</div>

<div class="modal fade" id="modalTambahBarang">
    <div class="modal-dialog modal-lg"><form action="{{ route('barang.store') }}" method="POST">@csrf
        <div class="modal-content"><div class="modal-header"><h5 class="modal-title">Tambah Data Barang</h5><button type="button" class="close" data-dismiss="modal">&times;</button></div>
            <div class="modal-body"><div class="row">
                <div class="col-md-6">
                    <div class="form-group"><label>Barcode</label><input type="text" name="barcode" class="form-control" placeholder="Scan atau ketik barcode" required autofocus></div>
                    <div class="form-group"><label>Nama Barang</label><input type="text" name="nama_barang" class="form-control" required></div>
                    <div class="form-group"><label>Kategori</label><select name="kategori_id" class="custom-select" required><option value="">Pilih kategori</option>@foreach($kategori as $kat)<option value="{{ $kat->id }}">{{ $kat->nama_kategori }}</option>@endforeach</select></div>
                </div>
                <div class="col-md-6">
                    <div class="form-group"><label>Satuan</label><input type="text" name="satuan" value="pcs" class="form-control"></div>
                    <div class="form-group"><label>Harga Jual</label><input type="number" name="harga_jual" class="form-control" required></div>
                    <div class="form-group"><label>Harga Beli Terakhir</label><input type="number" name="harga_beli_terakhir" class="form-control" value="0"></div>
                    <div class="form-group"><label>Stok Minimal</label><input type="number" name="stok_minimal" class="form-control" value="5" required></div>
                    <div class="form-group"><label>Stok Awal</label><input type="number" name="stok_total" class="form-control" value="0"><small class="text-muted">Saran: stok utama tetap dicatat lewat menu Stok Masuk.</small></div>
                </div>
            </div></div>
            <div class="modal-footer"><button type="button" class="btn btn-light" data-dismiss="modal">Batal</button><button class="btn btn-primary">Simpan Barang</button></div>
        </div>
    </form></div>
</div>
@endsection
