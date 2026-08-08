@extends('layouts.master')
@section('judul','Manajemen Shift Kasir')
@section('isi')
<div class="row">
    <div class="col-lg-5">
        <div class="card">
            <div class="card-header"><h5 class="mb-0 font-weight-bold">Status Shift</h5></div>
            <div class="card-body">
                @if($shiftAktif)
                    <div class="alert alert-success"><strong>Shift sedang aktif</strong><br>Dibuka: {{ $shiftAktif->waktu_buka->format('d/m/Y H:i') }}</div>
                    <div class="row text-center mb-3">
                        <div class="col-6"><small class="text-muted">Saldo Awal</small><h5>Rp {{ number_format($shiftAktif->saldo_awal,0,',','.') }}</h5></div>
                        <div class="col-6"><small class="text-muted">Estimasi Kas</small><h5>Rp {{ number_format($estimasiKas,0,',','.') }}</h5></div>
                    </div>
                    <div class="row text-center mb-3">
                        <div class="col-6"><small class="text-muted">Cash</small><h5>Rp {{ number_format($totalPenjualanCash,0,',','.') }}</h5></div>
                        <div class="col-6"><small class="text-muted">Non Cash</small><h5>Rp {{ number_format($totalPenjualanNonCash,0,',','.') }}</h5></div>
                    </div>
                    <form method="POST" action="{{ route('shift.tutup') }}">@csrf
                        <div class="form-group"><label>Saldo Akhir di Laci</label><input type="number" name="saldo_akhir" class="form-control" value="{{ $estimasiKas }}" required></div>
                        <div class="form-group"><label>Catatan</label><textarea name="catatan" class="form-control" rows="3" placeholder="Opsional"></textarea></div>
                        <button class="btn btn-danger btn-block" onclick="return confirm('Tutup shift sekarang?')"><i class="fas fa-lock mr-1"></i>Tutup Shift</button>
                    </form>
                @else
                    <div class="alert alert-warning"><strong>Belum ada shift aktif.</strong><br>Buka shift sebelum masuk ke halaman kasir POS.</div>
                    <form method="POST" action="{{ route('shift.buka') }}">@csrf
                        <div class="form-group"><label>Saldo Awal Kasir</label><input type="number" name="saldo_awal" class="form-control form-control-lg" value="0" required></div>
                        <button class="btn btn-primary btn-block btn-lg"><i class="fas fa-unlock mr-1"></i>Buka Shift</button>
                    </form>
                @endif
            </div>
        </div>
    </div>
    <div class="col-lg-7">
        <div class="card">
            <div class="card-header"><h5 class="mb-0 font-weight-bold">Riwayat Shift Terakhir</h5></div>
            <div class="card-body table-responsive p-0">
                <table class="table table-hover mb-0"><thead><tr><th>Buka</th><th>Tutup</th><th>Saldo Awal</th><th>Saldo Akhir</th><th>Catatan</th></tr></thead><tbody>
                    @forelse($riwayatShift as $s)
                        <tr><td>{{ $s->waktu_buka->format('d/m/Y H:i') }}</td><td>{{ $s->waktu_tutup?->format('d/m/Y H:i') ?? 'Aktif' }}</td><td>Rp {{ number_format($s->saldo_awal,0,',','.') }}</td><td>{{ $s->saldo_akhir ? 'Rp '.number_format($s->saldo_akhir,0,',','.') : '-' }}</td><td>{{ $s->catatan ?? '-' }}</td></tr>
                    @empty
                        <tr><td colspan="5" class="text-center text-muted py-4">Belum ada riwayat shift.</td></tr>
                    @endforelse
                </tbody></table>
            </div>
        </div>
    </div>
</div>
@endsection
