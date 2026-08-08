<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk POS - {{ $penjualan->no_invoice }}</title>
    <style>
        @media print {
            body { width: 100%; margin: 0; padding: 0; }
            @page { margin: 0; }
            .no-print { display: none !important; } /* Menyembunyikan tombol saat kertas diprint */
        }
        /* CSS khusus untuk printer thermal kasir */
        body { 
            font-family: 'Courier New', Courier, monospace; 
            width: 80mm; /* Standar ukuran kertas struk kasir */
            margin: 0 auto; 
            padding: 10px; 
            font-size: 13px; 
            color: #000; 
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .font-bold { font-weight: bold; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; margin-bottom: 10px; }
        th, td { padding: 4px 0; }
        .border-top { border-top: 1px dashed #000; }
        .border-bottom { border-bottom: 1px dashed #000; }
        
        /* Hilangkan margin/padding bawaan browser saat benar-benar dicetak */
        @media print {
            body { width: 100%; margin: 0; padding: 0; }
            @page { margin: 0; }
        }
    </style>
</head>

<body onload="window.print()">
    
    <div class="text-center mb-3">
        <h2 style="margin-bottom: 5px; margin-top: 0;">FUZZA MART</h2>
        <div>Cabang: {{ $penjualan->cabang->nama_cabang ?? 'Pusat' }}</div>
        <div>Tangerang Selatan, Banten</div>
        <div class="border-bottom" style="margin-top: 10px; margin-bottom: 10px;"></div>
    </div>

    <table>
        <tr>
            <td>Inv: {{ $penjualan->no_invoice }}</td>
            <td class="text-right">{{ $penjualan->created_at->format('d/m/Y H:i') }}</td>
        </tr>
        <tr>
            <td>Opr: {{ $penjualan->kasir->name }}</td>
            <td class="text-right">Tipe: {{ strtoupper($penjualan->metode_pembayaran) }}</td>
        </tr>
        @if($penjualan->pelanggan_id)
        <tr>
            <td>Mem: {{ $penjualan->pelanggan->name ?? '-' }}</td>
            <td class="text-right"></td>
        </tr>
        @endif
    </table>

    <div class="border-top border-bottom" style="margin-top: 10px; margin-bottom: 10px;"></div>

    <table>
        @foreach($penjualan->detail_penjualan as $detail)
        <tr>
            <td colspan="2" class="font-bold">{{ $detail->barang->nama_barang }}</td>
        </tr>
        <tr>
            <td>{{ $detail->qty }} x {{ number_format($detail->harga_satuan, 0, ',', '.') }}</td>
            <td class="text-right">{{ number_format($detail->subtotal, 0, ',', '.') }}</td>
        </tr>
        @endforeach
    </table>

    <div class="border-top" style="margin-top: 10px; margin-bottom: 10px;"></div>

    <table>
        <tr>
            <td class="font-bold">TOTAL</td>
            <td class="text-right font-bold">Rp {{ number_format($penjualan->total_harga, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td>DIBAYAR</td>
            <td class="text-right">Rp {{ number_format($penjualan->total_bayar, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td>KEMBALIAN</td>
            <td class="text-right">Rp {{ number_format($penjualan->kembalian, 0, ',', '.') }}</td>
        </tr>
    </table>

    <div class="border-top text-center" style="margin-top: 20px; padding-top: 10px;">
        <div class="font-bold">TERIMA KASIH ATAS KUNJUNGAN ANDA</div>
        <div style="font-size: 11px; margin-top: 5px;">Barang yang sudah dibeli tidak dapat ditukar atau dikembalikan.</div>
    </div>
<div class="no-print text-center" style="margin-top: 30px;">
        <button onclick="window.print()" style="padding: 12px 20px; background-color: #1E293B; color: white; border: none; border-radius: 8px; cursor: pointer; font-weight: bold; margin-right: 10px;">
            <i class="fas fa-print"></i> Cetak Ulang
        </button>
        <a href="{{ route('penjualan.index') }}" style="padding: 12px 20px; background-color: #B69377; color: white; border: none; border-radius: 8px; cursor: pointer; text-decoration: none; font-weight: bold;">
            Kembali
        </a>
    </div>
    
</body>
</html>