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
                <h5 class="font-weight-bold" style="color: #334155;">💸 Pelacakan Biaya Operasional (Pengeluaran)</h5>
                <button class="btn text-white px-4" data-toggle="modal" data-target="#modalPengeluaran" style="background-color: #B69377;">
                    <i class="fas fa-wallet mr-2"></i> Catat Biaya Baru
                </button>
            </div>

            <div class="table-responsive">
                <table class="table table-borderless align-middle">
                    <thead>
                        <tr>
                            <th class="rounded-left" style="background-color: #1E293B; color: white;">Tanggal</th>
                            <th style="background-color: #1E293B; color: white;">Lokasi Cabang</th>
                            <th style="background-color: #1E293B; color: white;">Alokasi Pengeluaran</th>
                            <th style="background-color: #1E293B; color: white;">Nominal Biaya</th>
                            <th class="rounded-right text-center" style="background-color: #1E293B; color: white;" width="100">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pengeluaran as $p)
                        <tr style="border-bottom: 1px solid #E2E8F0; transition: background 0.2s;" onmouseover="this.style.backgroundColor='#FDF8F3'" onmouseout="this.style.backgroundColor='transparent'">
                            <td class="text-muted">{{ $p->tgl_pengeluaran->format('d/m/Y') }}</td>
                            <td>
                                <span class="badge badge-secondary px-2 py-1" style="background-color: #1E293B; color: white;">
                                    {{ $p->cabang->nama_cabang }}
                                </span>
                            </td>
                            <td class="font-weight-bold" style="color: #334155;">{{ $p->nama_pengeluaran }}</td>
                            <td class="font-weight-bold text-danger">Rp {{ number_format($p->nominal, 0, ',', '.') }}</td>
                            <td class="text-center">
                                <form action="{{ route('pengeluaran.destroy', $p->id) }}" method="POST">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm text-white" style="background-color: #EF4444;" onclick="return confirm('Hapus catatan pengeluaran ini?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">Belum ada catatan pengeluaran operasional bulan ini.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah Pengeluaran -->
<div class="modal fade" id="modalPengeluaran" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <form action="{{ route('pengeluaran.store') }}" method="POST" class="modal-content border-0" style="border-radius: 12px;">
            @csrf
            <div class="modal-header border-0 p-4">
                <h5 class="font-weight-bold" style="color: #334155;">Form Buku Kas Keluar</h5>
            </div>
            <div class="modal-body px-4 py-0">
                <div class="form-group mb-3">
                    <label class="small font-weight-bold text-uppercase text-muted">Beban Lokasi Cabang</label>
                    <select name="cabang_id" class="form-control" required style="border-radius: 8px;">
                        <option value="">-- Pilih lokasi cabang pembayar --</option>
                        @foreach($cabang as $c)
                            <option value="{{ $c->id }}">{{ $c->nama_cabang }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group mb-3">
                    <label class="small font-weight-bold text-uppercase text-muted">Nama Pengeluaran / Keperluan</label>
                    <input type="text" name="nama_pengeluaran" class="form-control" placeholder="Contoh: Pembayaran Listrik Bulanan / Gaji Karyawan" required style="border-radius: 8px;">
                </div>
                <div class="row">
                    <div class="col-md-6 form-group mb-4">
                        <label class="small font-weight-bold text-uppercase text-muted">Nominal Biaya (Rp)</label>
                        <input type="number" name="nominal" class="form-control" placeholder="0" min="0" required style="border-radius: 8px;">
                    </div>
                    <div class="col-md-6 form-group mb-4">
                        <label class="small font-weight-bold text-uppercase text-muted">Tanggal Pengeluaran</label>
                        <input type="date" name="tgl_pengeluaran" class="form-control" required style="border-radius: 8px;" value="{{ date('Y-m-d') }}">
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0 p-4">
                <button type="button" class="btn btn-light px-4" data-dismiss="modal" style="border-radius: 8px;">Batal</button>
                <button type="submit" class="btn text-white px-4" style="background-color: #B69377; border-radius: 8px;">Simpan Catatan</button>
            </div>
        </form>
    </div>
</div>
@endsection