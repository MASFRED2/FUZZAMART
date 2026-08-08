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
                <h5 class="font-weight-bold" style="color: #334155;">🤝 Mitra Pemasok (Supplier)</h5>
                <button class="btn text-white px-4" data-toggle="modal" data-target="#modalPemasok" style="background-color: #B69377;">
                    <i class="fas fa-plus mr-2"></i> Tambah Pemasok
                </button>
            </div>

            <div class="table-responsive">
                <table class="table table-borderless align-middle">
                    <thead>
                        <tr>
                            <th class="rounded-left" style="background-color: #1E293B; color: white;">Nama Pemasok / Vendor</th>
                            <th style="background-color: #1E293B; color: white;">Kontak / Nomor Telepon</th>
                            <th class="rounded-right text-center" style="background-color: #1E293B; color: white;" width="100">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pemasok as $p)
                        <tr style="border-bottom: 1px solid #E2E8F0;">
                            <td class="font-weight-bold" style="color: #334155;">{{ $p->nama_pemasok }}</td>
                            <td class="text-muted">{{ $p->kontak }}</td>
                            <td class="text-center">
                                <form action="{{ route('pemasok.destroy', $p->id) }}" method="POST">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm text-white" style="background-color: #EF4444;" onclick="return confirm('Hapus data rekanan ini?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah Pemasok -->
<div class="modal fade" id="modalPemasok" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <form action="{{ route('pemasok.store') }}" method="POST" class="modal-content border-0" style="border-radius: 12px;">
            @csrf
            <div class="modal-header border-0 p-4">
                <h5 class="font-weight-bold" style="color: #334155;">Tambah Rekanan Pemasok</h5>
            </div>
            <div class="modal-body px-4 py-0">
                <div class="form-group mb-3">
                    <label class="small font-weight-bold text-uppercase text-muted">Nama Perusahaan / Distributor</label>
                    <input type="text" name="nama_pemasok" class="form-control" placeholder="Contoh: PT. Sinar Niaga" required style="border-radius: 8px;">
                </div>
                <div class="form-group mb-4">
                    <label class="small font-weight-bold text-uppercase text-muted">No. Telepon / Sales</label>
                    <input type="text" name="kontak" class="form-control" placeholder="Contoh: 0812XXXXXXXX" required style="border-radius: 8px;">
                </div>
            </div>
            <div class="modal-footer border-0 p-4">
                <button type="button" class="btn btn-light px-4" data-dismiss="modal" style="border-radius: 8px;">Batal</button>
                <button type="submit" class="btn text-white px-4" style="background-color: #B69377; border-radius: 8px;">Simpan Vendor</button>
            </div>
        </form>
    </div>
</div>
@endsection