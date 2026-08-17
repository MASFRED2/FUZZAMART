@extends('layouts.master')

@section('judul', 'Manajemen Data Barang')

@section('isi')
@if($errors->any())
    <div class="alert alert-danger">
        <strong>Data belum dapat disimpan.</strong>
        <ul class="mb-0 mt-2">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
    </div>
@endif

<div class="card mb-3">
    <div class="card-body">
        <form method="GET" class="row align-items-end">
            <div class="col-md-4 mb-2"><label>Cari Barang</label><input type="text" name="q" value="{{ request('q') }}" class="form-control" placeholder="Nama atau barcode kemasan"></div>
            <div class="col-md-3 mb-2"><label>Kategori</label><select name="kategori_id" class="custom-select"><option value="">Semua kategori</option>@foreach($kategori as $kat)<option value="{{ $kat->id }}" @selected(request('kategori_id')==$kat->id)>{{ $kat->nama_kategori }}</option>@endforeach</select></div>
            <div class="col-md-2 mb-2"><label>Status</label><select name="status" class="custom-select"><option value="aktif" @selected(request('status','aktif')==='aktif')>Aktif</option><option value="nonaktif" @selected(request('status')==='nonaktif')>Nonaktif</option></select></div>
            <div class="col-md-3 mb-2 d-flex"><button class="btn btn-dark mr-2"><i class="fas fa-search mr-1"></i>Filter</button><a href="{{ route('barang.index') }}" class="btn btn-light mr-2">Reset</a><button type="button" class="btn btn-primary ml-auto" data-toggle="modal" data-target="#modalTambahBarang"><i class="fas fa-plus mr-1"></i>Barang</button></div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body table-responsive p-0">
        <table class="table table-hover mb-0">
            <thead><tr><th>No</th><th>Barang</th><th>Kategori</th><th>Satuan & Harga Jual</th><th>Stok Dasar</th><th>Status</th><th width="110">Aksi</th></tr></thead>
            <tbody>
                @forelse($barang as $b)
                    <tr>
                        <td>{{ $barang->firstItem() + $loop->index }}</td>
                        <td>
                            <strong>{{ $b->nama_barang }}</strong><br>
                            <small class="text-muted">Barcode dasar: {{ $b->barcode }}</small>
                        </td>
                        <td>{{ $b->kategori?->nama_kategori ?? '-' }}</td>
                        <td>
                            @foreach($b->satuan_produk as $unit)
                                <div class="d-flex align-items-center justify-content-between mb-1" style="gap:12px;min-width:250px;">
                                    <span>
                                        <span class="badge badge-{{ $unit->is_default ? 'primary' : 'light' }} border">{{ $unit->satuan?->simbol ?? '-' }}</span>
                                        @if(!$unit->is_default)<small class="text-muted">1 = {{ $unit->konversi_satuan }} {{ $b->satuan }}</small>@endif
                                    </span>
                                    <strong>Rp {{ number_format($unit->harga_jual,0,',','.') }}</strong>
                                </div>
                            @endforeach
                        </td>
                        <td>
                            @if($b->stok_total <= 0)
                                <span class="badge badge-danger">Habis</span>
                            @elseif($b->stok_total <= $b->stok_minimal)
                                <span class="badge badge-warning">{{ $b->stok_total }} {{ $b->satuan }} · rendah</span>
                            @else
                                <span class="badge badge-success">{{ $b->stok_total }} {{ $b->satuan }}</span>
                            @endif
                            <br><small class="text-muted">Minimum: {{ $b->stok_minimal }} {{ $b->satuan }}</small>
                        </td>
                        <td><span class="badge badge-{{ $b->is_active ? 'success' : 'secondary' }}">{{ $b->is_active ? 'Aktif' : 'Nonaktif' }}</span></td>
                        <td>
                            <button class="btn btn-info btn-sm" data-toggle="modal" data-target="#modalEdit{{ $b->id }}"><i class="fas fa-edit"></i></button>
                            <form action="{{ route('barang.destroy',$b->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus/arsipkan barang ini? Riwayat transaksi tetap aman.')">
                                @csrf @method('DELETE')
                                <button class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="text-center text-muted py-4">Data barang belum tersedia.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-footer">{{ $barang->links() }}</div>
</div>

@foreach($barang as $b)
    @php($kemasanAktif = $b->satuan_produk->where('is_default', false)->values())
    <div class="modal fade" id="modalEdit{{ $b->id }}">
        <div class="modal-dialog modal-xl">
            <form action="{{ route('barang.update',$b->id) }}" method="POST">
                @csrf @method('PUT')
                <div class="modal-content">
                    <div class="modal-header"><div><h5 class="modal-title">Edit Barang</h5><small class="text-muted">{{ $b->nama_barang }}</small></div><button type="button" class="close" data-dismiss="modal">&times;</button></div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group"><label>Nama Barang</label><input type="text" name="nama_barang" value="{{ $b->nama_barang }}" class="form-control" required></div>
                                <div class="form-group"><label>Kategori</label><select name="kategori_id" class="custom-select" required>@foreach($kategori as $kat)<option value="{{ $kat->id }}" @selected($b->kategori_id==$kat->id)>{{ $kat->nama_kategori }}</option>@endforeach</select></div>
                                <div class="form-group"><label>Stok Minimal</label><input type="number" name="stok_minimal" value="{{ $b->stok_minimal }}" class="form-control" min="0" required><small class="text-muted">Dihitung menggunakan satuan dasar.</small></div>
                                <div class="form-group"><label>Harga Beli Terakhir / Satuan Dasar</label><input type="number" name="harga_beli_terakhir" value="{{ $b->harga_beli_terakhir }}" class="form-control" min="0"></div>
                                <div class="custom-control custom-switch"><input type="checkbox" class="custom-control-input" id="aktif{{ $b->id }}" name="is_active" value="1" @checked($b->is_active)><label class="custom-control-label" for="aktif{{ $b->id }}">Barang aktif dijual</label></div>
                            </div>
                            <div class="col-md-8">
                                <div class="card border-primary">
                                    <div class="card-header bg-light"><strong>Satuan Dasar</strong><small class="text-muted d-block">Stok selalu disimpan dalam satuan ini.</small></div>
                                    <div class="card-body"><div class="form-row">
                                        <div class="col-md-3"><label>Satuan</label><select name="satuan_dasar_id" class="custom-select" required>@foreach($satuan as $unit)<option value="{{ $unit->id }}" @selected($b->satuan_dasar?->satuan_id==$unit->id)>{{ $unit->nama_satuan }} ({{ $unit->simbol }})</option>@endforeach</select></div>
                                        <div class="col-md-4"><label>Barcode</label><input type="text" name="barcode" value="{{ $b->barcode }}" class="form-control" required></div>
                                        <div class="col-md-5"><label>Harga Jual</label><input type="number" name="harga_jual" value="{{ $b->harga_jual }}" class="form-control" min="0" required></div>
                                    </div></div>
                                </div>

                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <div><strong>Kemasan Tambahan</strong><small class="text-muted d-block">Setiap kemasan memiliki barcode, konversi, dan harga sendiri.</small></div>
                                    <button type="button" class="btn btn-outline-primary btn-sm" onclick="tambahKemasan('kemasanEdit{{ $b->id }}')"><i class="fas fa-plus mr-1"></i>Kemasan</button>
                                </div>
                                <div id="kemasanEdit{{ $b->id }}" data-next-index="{{ $kemasanAktif->count() }}">
                                    @foreach($kemasanAktif as $index => $unit)
                                        <div class="kemasan-row border rounded p-3 mb-2 bg-light">
                                            <div class="form-row align-items-end">
                                                <div class="col-md-3"><label>Satuan</label><select name="kemasan[{{ $index }}][satuan_id]" class="custom-select" required>@foreach($satuan as $opsi)<option value="{{ $opsi->id }}" @selected($unit->satuan_id==$opsi->id)>{{ $opsi->simbol }}</option>@endforeach</select></div>
                                                <div class="col-md-3"><label>Barcode</label><input type="text" name="kemasan[{{ $index }}][barcode]" value="{{ $unit->barcode }}" class="form-control" required></div>
                                                <div class="col-md-2"><label>Isi</label><input type="number" name="kemasan[{{ $index }}][konversi_satuan]" value="{{ $unit->konversi_satuan }}" class="form-control" min="2" required><small class="text-muted">dalam {{ $b->satuan }}</small></div>
                                                <div class="col-md-3"><label>Harga Jual</label><input type="number" name="kemasan[{{ $index }}][harga_jual]" value="{{ $unit->harga_jual }}" class="form-control" min="0" required></div>
                                                <div class="col-md-1"><button type="button" class="btn btn-outline-danger btn-block" onclick="hapusKemasan(this)"><i class="fas fa-times"></i></button></div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer"><button type="button" class="btn btn-light" data-dismiss="modal">Batal</button><button class="btn btn-primary">Simpan Perubahan</button></div>
                </div>
            </form>
        </div>
    </div>
@endforeach

<div class="modal fade" id="modalTambahBarang">
    <div class="modal-dialog modal-xl">
        <form action="{{ route('barang.store') }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header"><div><h5 class="modal-title">Tambah Data Barang</h5><small class="text-muted">Daftarkan barang beserta seluruh kemasan jualnya.</small></div><button type="button" class="close" data-dismiss="modal">&times;</button></div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group"><label>Nama Barang</label><input type="text" name="nama_barang" value="{{ old('nama_barang') }}" class="form-control" required autofocus></div>
                            <div class="form-group"><label>Kategori</label><select name="kategori_id" class="custom-select" required><option value="">Pilih kategori</option>@foreach($kategori as $kat)<option value="{{ $kat->id }}" @selected(old('kategori_id')==$kat->id)>{{ $kat->nama_kategori }}</option>@endforeach</select></div>
                            <div class="form-group"><label>Stok Minimal</label><input type="number" name="stok_minimal" value="{{ old('stok_minimal',5) }}" class="form-control" min="0" required><small class="text-muted">Dalam satuan dasar.</small></div>
                            <div class="form-group"><label>Harga Beli Terakhir / Satuan Dasar</label><input type="number" name="harga_beli_terakhir" value="{{ old('harga_beli_terakhir',0) }}" class="form-control" min="0"></div>
                            <div class="alert alert-info py-2 small"><i class="fas fa-info-circle mr-1"></i>Stok awal dicatat melalui menu <strong>Stok Masuk</strong> agar kartu stok tetap lengkap.</div>
                        </div>
                        <div class="col-md-8">
                            <div class="card border-primary">
                                <div class="card-header bg-light"><strong>Satuan Dasar</strong><small class="text-muted d-block">Contoh: pcs untuk barang yang dapat dijual satuan.</small></div>
                                <div class="card-body"><div class="form-row">
                                    <div class="col-md-3"><label>Satuan</label><select name="satuan_dasar_id" class="custom-select" required><option value="">Pilih</option>@foreach($satuan as $unit)<option value="{{ $unit->id }}" @selected(old('satuan_dasar_id')==$unit->id)>{{ $unit->nama_satuan }} ({{ $unit->simbol }})</option>@endforeach</select></div>
                                    <div class="col-md-4"><label>Barcode</label><input type="text" name="barcode" value="{{ old('barcode') }}" class="form-control" placeholder="Scan barcode dasar" required></div>
                                    <div class="col-md-5"><label>Harga Jual</label><input type="number" name="harga_jual" value="{{ old('harga_jual') }}" class="form-control" min="0" required></div>
                                </div></div>
                            </div>

                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <div><strong>Kemasan Tambahan</strong><small class="text-muted d-block">Contoh: renteng isi 10 dan dus isi 40, masing-masing dengan harga berbeda.</small></div>
                                <button type="button" class="btn btn-outline-primary btn-sm" onclick="tambahKemasan('kemasanTambah')"><i class="fas fa-plus mr-1"></i>Kemasan</button>
                            </div>
                            <div id="kemasanTambah" data-next-index="0"></div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer"><button type="button" class="btn btn-light" data-dismiss="modal">Batal</button><button class="btn btn-primary">Simpan Barang</button></div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
const pilihanSatuan = @json($satuan->map(fn($unit) => ['id' => $unit->id, 'nama' => $unit->nama_satuan, 'simbol' => $unit->simbol])->values());

function escapeHtml(value) {
    return String(value).replace(/[&<>'"]/g, char => ({'&':'&amp;','<':'&lt;','>':'&gt;',"'":'&#039;','"':'&quot;'}[char]));
}

function tambahKemasan(containerId) {
    const container = document.getElementById(containerId);
    const index = Number(container.dataset.nextIndex || 0);
    container.dataset.nextIndex = index + 1;
    const options = pilihanSatuan.map(unit => `<option value="${unit.id}">${escapeHtml(unit.nama)} (${escapeHtml(unit.simbol)})</option>`).join('');

    container.insertAdjacentHTML('beforeend', `
        <div class="kemasan-row border rounded p-3 mb-2 bg-light">
            <div class="form-row align-items-end">
                <div class="col-md-3"><label>Satuan</label><select name="kemasan[${index}][satuan_id]" class="custom-select" required><option value="">Pilih</option>${options}</select></div>
                <div class="col-md-3"><label>Barcode</label><input type="text" name="kemasan[${index}][barcode]" class="form-control" placeholder="Scan barcode" required></div>
                <div class="col-md-2"><label>Isi</label><input type="number" name="kemasan[${index}][konversi_satuan]" class="form-control" min="2" placeholder="10" required><small class="text-muted">satuan dasar</small></div>
                <div class="col-md-3"><label>Harga Jual</label><input type="number" name="kemasan[${index}][harga_jual]" class="form-control" min="0" placeholder="Rp" required></div>
                <div class="col-md-1"><button type="button" class="btn btn-outline-danger btn-block" onclick="hapusKemasan(this)"><i class="fas fa-times"></i></button></div>
            </div>
        </div>`);
}

function hapusKemasan(button) {
    button.closest('.kemasan-row').remove();
}
</script>
@endpush
