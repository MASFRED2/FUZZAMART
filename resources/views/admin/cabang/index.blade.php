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
                <h5 class="font-weight-bold" style="color: #334155;">📍 Manajemen Cabang Toko</h5>
                <button class="btn text-white px-4" data-toggle="modal" data-target="#modalCabang" style="background-color: #B69377;">
                    <i class="fas fa-plus mr-2"></i> Tambah Cabang
                </button>
            </div>

            <div class="table-responsive">
                <table class="table table-borderless align-middle">
                    <thead>
                        <tr>
                            <th class="rounded-left" style="background-color: #1E293B; color: white;">Nama Cabang</th>
                            <th style="background-color: #1E293B; color: white;">Alamat Lokasi</th>
                            <th class="rounded-right text-center" style="background-color: #1E293B; color: white;" width="100">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($cabang as $c)
                        <tr style="border-bottom: 1px solid #E2E8F0;">
                            <td class="font-weight-bold" style="color: #334155;">{{ $c->nama_cabang }}</td>
                            <td class="text-muted">{{ $c->alamat }}</td>
                            <td class="text-center">
                                <form action="{{ route('cabang.destroy', $c->id) }}" method="POST">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm text-white" style="background-color: #EF4444;" onclick="return confirm('Hapus cabang ini?')">
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

<!-- Modal Tambah Cabang -->
<div class="modal fade" id="modalCabang" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <form action="{{ route('cabang.store') }}" method="POST" class="modal-content border-0" style="border-radius: 12px;">
            @csrf
            <div class="modal-header border-0 p-4">
                <h5 class="font-weight-bold" style="color: #334155;">Registrasi Cabang Baru</h5>
            </div>
            <div class="modal-body px-4 py-0">
                <div class="form-group mb-3">
                    <label class="small font-weight-bold text-uppercase text-muted">Nama Cabang / Lokasi</label>
                    <input type="text" name="nama_cabang" class="form-control" placeholder="Contoh: Cabang Pamulang 2" required style="border-radius: 8px;">
                </div>
                <div class="form-group mb-4">
                    <label class="small font-weight-bold text-uppercase text-muted">Alamat Lengkap</label>
                    <textarea name="alamat" class="form-control" rows="3" placeholder="Masukkan alamat operasional cabang..." required style="border-radius: 8px;"></textarea>
                </div>
            </div>
            <div class="modal-footer border-0 p-4">
                <button type="button" class="btn btn-light px-4" data-dismiss="modal" style="border-radius: 8px;">Batal</button>
                <button type="submit" class="btn text-white px-4" style="background-color: #B69377; border-radius: 8px;">Simpan Lokasi</button>
            </div>
        </form>
    </div>
</div>
@endsection