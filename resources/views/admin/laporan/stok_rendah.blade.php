@extends('layouts.master')

@section('isi')
<div class="container-fluid pt-4">
    <!-- Kotak Ringkasan Singkat -->
    <div class="alert border-0 bg-white shadow-sm mb-4" style="border-left: 4px solid #F59E0B !important;">
        <div class="d-flex align-items-center">
            <i class="fas fa-exclamation-triangle fa-2x text-warning mr-3"></i>
            <div>
                <h5 class="font-weight-bold mb-1" style="color: #334155;">Restock Perlengkapan Toko</h5>
                <p class="text-muted small mb-0">Daftar di bawah ini menampilkan seluruh produk yang telah menyentuh atau berada di bawah batas minimum sistem. Segera hubungi pemasok terkait.</p>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm bg-white">
        <div class="card-body p-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="font-weight-bold" style="color: #334155;">⚠️ Peringatan Batas Kritis Inventaris</h5>
                <span class="badge badge-pill text-white px-3 py-2" style="background-color: #1E293B;">
                    Total: {{ $barangKritis->count() }} Item Kritis
                </span>
            </div>

            <div class="table-responsive">
                <table class="table table-borderless align-middle">
                    <thead>
                        <tr>
                            <th class="rounded-left" style="background-color: #1E293B; color: white;">Barcode</th>
                            <th style="background-color: #1E293B; color: white;">Nama Produk</th>
                            <th style="background-color: #1E293B; color: white;">Kategori</th>
                            <th style="background-color: #1E293B; color: white;">Batas Minimal</th>
                            <th style="background-color: #1E293B; color: white;">Stok Riil Saat Ini</th>
                            <th class="rounded-right text-center" style="background-color: #1E293B; color: white;" width="150">Status Batas</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($barangKritis as $bk)
                        <tr style="border-bottom: 1px solid #E2E8F0; transition: background 0.2s;" onmouseover="this.style.backgroundColor='#FDF8F3'" onmouseout="this.style.backgroundColor='transparent'">
                            <td class="text-muted font-weight-bold">#{{ $bk->barcode }}</td>
                            <td class="font-weight-bold" style="color: #334155;">{{ $bk->nama_barang }}</td>
                            <td>
                                <span class="badge badge-light border text-secondary px-2 py-1">
                                    {{ $bk->kategori->nama_kategori ?? 'Umum' }}
                                </span>
                            </td>
                            <td class="text-muted font-weight-bold">{{ $bk->stok_minimal }} pcs</td>
                            <td>
                                <span class="h6 font-weight-bold {{ $bk->stok_total == 0 ? 'text-danger' : 'text-warning' }}">
                                    {{ $bk->stok_total }} pcs
                                </span>
                            </td>
                            <td class="text-center">
                                @if($bk->stok_total == 0)
                                    <span class="badge text-white px-3 py-1" style="background-color: #EF4444; border-radius: 8px;">
                                        ❌ HABIS TOTAL
                                    </span>
                                @else
                                    <span class="badge text-white px-3 py-1" style="background-color: #F59E0B; border-radius: 8px;">
                                        ⚠️ TIPIS
                                    </span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center text-success font-weight-bold py-5">
                                <i class="fas fa-check-circle mr-2"></i> Semua stok aman dan berada di atas ambang batas minimum operasional!
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection