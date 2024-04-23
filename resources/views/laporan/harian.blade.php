@extends('layouts.laporan', ['title' => 'Laporan Harian'])

@section('content')

<h1 class="text-center">Laporan Harian</h1>
<p>Tanggal : {{date('d/m/Y', strtotime( request()->tanggal ))}}</p>


<table class="table table-bordered table-sm">
    <thead>
        <tr>
            <th>No</th>
            <th>No. Transaksi</th>
            <th>Nama</th>
            <th>Kasir</th>
            <th>Status</th>
            <th>Waktu</th>
            <th>Total</th>
        </tr>
    </thead>
    <tbody>
        @php
        $totalPenjualan = 0;
        $nomor = 1;
        @endphp

        @foreach ($penjualan as $key => $row)
        @if($row->status != 'batal')
        <tr>
            <td>{{ $nomor++ }}</td>
            <td>{{ $row->nomor_transaksi }}</td>
            <td>{{ $row->nama_pelanggan }}</td>
            <td>{{ $row->nama_kasir }}</td>
            <td>
                @if($row->status == 'selesai')
                <span class="badge badge-success">Selesai</span>
                @elseif($row->status == 'batal')
                <span class="badge badge-danger">Dibatalkan</span>
                @endif
            </td>
            <td>{{ date('H:i:s', strtotime($row->tanggal)) }}</td>
            <td>{{ number_format($row->total, 0, ',', '.') }}</td>
        </tr>
        @php
        $totalPenjualan += $row->total;
        @endphp
        @endif
        @endforeach


    </tbody>
    <tfoot>
        <tr>
            <th colspan="6">
                Jumlah Total
            </th>
            <th>
                {{ number_format($totalPenjualan, 0,',','.') }}
            </th>
        </tr>
    </tfoot>
</table>
@endsection