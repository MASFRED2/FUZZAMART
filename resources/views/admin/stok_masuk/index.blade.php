@extends('layouts.master')

@section('isi')
<div class="container-fluid pt-4">
    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm" style="background-color: #22C55E; color: white;">
            {{ session('success') }}
        </div>
    @endif

    <div class="card border-0 shadow-sm bg-white">
        <div class="card-body p-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="font-weight-bold" style="color: #334155;">📦 Riwayat Pengadaan & Stok Masuk</h5>
                <button class="btn text-white px-4" data-toggle="modal" data-target="#modalStokMasuk" style="background-color: #B69377;">
                    <i class="fas fa-download mr-2"></i> Catat Barang Masuk
                </button>
            </div>

            <div class="table-responsive">
                <table class="table table-borderless align-middle">
                    <thead>
                        <tr>
                            <th class="rounded-left" style="background-color: #1E293B; color: white;">Tgl Masuk</th>
                            <th style="background-color: #1E293B; color: white;">Nama Barang</th>
                            <th style="background-color: #1E293B; color: white;">Pemasok / Vendor</th>
                            <th style="background-color: #1E293B; color: white;">Jumlah</th>
                            <th style="background-color: #1E293B; color: white;">Harga Beli (Satuan)</th>
                            <th class="rounded-right" style="background-color: #1E293B; color: white;">Tgl Expired</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($stok_masuk as $sm)
                        <tr style="border-bottom: 1px solid #E2E8F0; transition: background 0.2s;" onmouseover="this.style.backgroundColor='#FDF8F3'" onmouseout="this.style.backgroundColor='transparent'">
                            <td class="text-muted">{{ $sm->created_at->format('d/m/Y') }}</td>
                            <td class="font-weight-bold" style="color: #334155;">{{ $sm->barang->nama_barang }}</td>
                            <td>
                                <span class="badge badge-light px-2 py-1 border text-secondary">
                                    <i class="fas fa-handshake mr-1"></i> {{ $sm->pemasok->nama_pemasok ?? 'Tanpa Vendor' }}
                                </span>
                            </td>
                            <td><span class="font-weight-bold" style="color: #334155;">{{ $sm->jumlah_masuk }}</span> pcs</td>
                            <td>Rp {{ number_format($sm->harga_beli, 0, ',', '.') }}</td>
                            <td>
                                @if($sm->tgl_kadaluwarsa)
                                    <span class="badge {{ $sm->tgl_kadaluwarsa->isPast() ? 'badge-danger' : 'badge-warning text-white' }} px-2 py-1">
                                        {{ $sm->tgl_kadaluwarsa->format('d/m/Y') }}
                                    </span>
                                @else
                                    <span class="text-muted">-</span>
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

<!-- Modal Input Stok Masuk -->
<div class="modal fade" id="modalStokMasuk" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <form action="{{ route('stok-masuk.store') }}" method="POST" class="modal-content border-0" style="border-radius: 12px;">
            @csrf
            <div class="modal-header border-0 p-4">
                <h5 class="font-weight-bold" style="color: #334155;">Form Penerimaan Barang</h5>
            </div>
            <div class="modal-body px-4 py-0">
                <div class="form-group mb-3">
                    <label class="small font-weight-bold text-uppercase text-muted">Pilih Barang</label>
                    <select name="barang_id" class="form-control" required style="border-radius: 8px;">
                        <option value="">-- Pilih item barang --</option>
                        @foreach($barang as $b)
                            <option value="{{ $b->id }}">{{ $b->nama_barang }} (Stok saat ini: {{ $b->stok_total }})</option>
                        @endforeach
                    </select>
                </div>
                
                <div class="form-group mb-3">
                    <label class="small font-weight-bold text-uppercase text-muted">Mitra Pemasok (Supplier)</label>
                    <select name="pemasok_id" class="form-control" required style="border-radius: 8px;">
                        <option value="">-- Pilih perusahaan vendor --</option>
                        @foreach($pemasok as $p)
                            <option value="{{ $p->id }}">{{ $p->nama_pemasok }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="row">
                    <div class="col-md-6 form-group mb-3">
                        <label class="small font-weight-bold text-uppercase text-muted">Jumlah Masuk</label>
                        <input type="number" name="jumlah_masuk" class="form-control" min="1" placeholder="0" required style="border-radius: 8px;">
                    </div>
                    <div class="col-md-6 form-group mb-3">
                        <label class="small font-weight-bold text-uppercase text-muted">Harga Beli / Pcs</label>
                        <input type="number" name="harga_beli" class="form-control" placeholder="Rp" required style="border-radius: 8px;">
                    </div>
                </div>

                <div class="form-group mb-4">
                    <label class="small font-weight-bold text-uppercase text-muted">Tanggal Kadaluwarsa (Opsional)</label>
                    <input type="date" name="tgl_kadaluwarsa" class="form-control" style="border-radius: 8px;">
                </div>
            </div>
            <div class="modal-footer border-0 p-4">
                <button type="button" class="btn btn-light px-4" data-dismiss="modal" style="border-radius: 8px;">Batal</button>
                <button type="submit" class="btn text-white px-4" style="background-color: #B69377; border-radius: 8px;">Simpan Logistik</button>
            </div>
        </form>
    </div>
</div>
@endsection