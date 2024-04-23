<?php

namespace App\Http\Controllers;

use App\Models\Penjualan;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;

class LaporanController extends Controller
{
    public function index()
    {
        return view('laporan.form');
    }

    public function harian(Request $request)
    {
        $penjualan = Penjualan::join('users', 'users.id', 'penjualans.user_id')
            ->join('pelanggans', 'pelanggans.id', 'penjualans.pelanggan_id')
            ->whereDate('tanggal', $request->tanggal)
            ->orderBy('id')
            ->select('penjualans.*', 'pelanggans.nama as nama_pelanggan', 'users.nama as nama_kasir')
            ->get();

        return view('laporan.harian', [
            'penjualan' => $penjualan]);
    }

    public function bulanan(Request $request)
    {
        $bulan = $request->bulan;
        $tahun = $request->tahun;
        $role = $request->role; // tambahkan parameter role

        // Mulai query dengan mengambil data penjualan
        $penjualan = Penjualan::leftJoin('users', 'users.id', '=', 'penjualans.user_id') // tambahkan penggabungan
            ->leftJoin('pelanggans', 'pelanggans.id', '=', 'penjualans.pelanggan_id') // tambahkan penggabungan
            ->select(
                DB::raw('COUNT(penjualans.id) as jumlah_transaksi'), // tambahkan 'penjualans.' sebelum 'id'
                DB::raw('SUM(penjualans.total) as jumlah_total'),
                DB::raw('DATE_FORMAT(penjualans.tanggal, "%d/%m/%Y") as tgl'),
                'users.nama as nama_kasir' // tambahkan 'users.' sebelum 'nama'
            )
            ->whereMonth('penjualans.tanggal', $bulan) // ubah 'tanggal' menjadi 'penjualans.tanggal'
            ->whereYear('penjualans.tanggal', $tahun) // ubah 'tanggal' menjadi 'penjualans.tanggal'
            ->where('penjualans.status', '!=', 'batal'); // tambahkan 'penjualans.' sebelum 'status'

        // Jika parameter role ada, tambahkan filter berdasarkan role
        if ($role) {
            $penjualan->where('users.role', $role);
        }

        // Eksekusi query dan dapatkan data penjualan
        $penjualan = $penjualan->groupBy('tgl', 'nama_kasir')->get();

        // Tentukan nama bulan berdasarkan nomor bulan yang dipilih
        $nama_bulan = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei', 6 => 'Juni',
            7 => 'Juli', 8 => 'Agustus', 9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember',
        ];

        // Ambil nama bulan sesuai dengan nomor bulan yang dipilih
        $bulan_nama = isset($nama_bulan[$bulan]) ? $nama_bulan[$bulan] : null;

        // Jika bulan tidak ditemukan, berikan respons sesuai
        if (!$bulan_nama) {
            return response()->json(['error' => 'Bulan tidak valid'], 400);
        }

        // Kirim data penjualan dan nama bulan ke view
        return view('laporan.bulanan', ['penjualan' => $penjualan, 'bulan' => $bulan_nama]);
    }
}