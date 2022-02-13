<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Product;
use Illuminate\Http\Request;

class AnalisisSentimenController extends Controller
{
    public function index()
    {
        $sentimens = [];
        $sentimens = [
            [
                'nama_produk' => 'MSI GF65 Thin 10UE [9S7-16W212-202] i5-10200H 16G 512GB RTX3060 6GB',
                'ulasan' => 'Wahhh laptopnya mantep banget, bagus dan berfungsi dengan baik',
                'stemming' => ['mantep', 'bagus', 'berfungsi', 'baik'],
                'status' => 1
            ],
            [
                'nama_produk' => 'MSI GF65 Thin 10UE [9S7-16W212-202] i5-10200H 16G 512GB RTX3060 6GB',
                'ulasan' => 'Laptopnya bagus banget dipake buat main game',
                'stemming' => ['bagus'],
                'status' => 1
            ],
            [
                'nama_produk' => 'SAMSUNG SSD 860EVO 1TB / 2.5" SATA / 860 EVO SSD / 5 years warranty',
                'ulasan' => 'bagus lah mantap kualitas nya canggih',
                'stemming' => ['mantap', 'canggih'],
                'status' => 1
            ],
            [
                'nama_produk' => 'MSI GF65 Thin 10UE [9S7-16W212-202] i5-10200H 16G 512GB RTX3060 6GB',
                'ulasan' => 'Apaan nih kok laptopnya cepet banget panas? suka ngefreeze pula kalo dipake lama2',
                'stemming' => ['panas', 'freeze'],
                'status' => 0
            ],
            [
                'nama_produk' => 'CANON EOS 750D KIT 18-55MM IS STM PAKET BONUS 6 ITEM - KAMERA SLR - A. STANDARD',
                'ulasan' => 'Sumpah kecewa banget kameranya buramm nyesal gue beli',
                'stemming' => ['kecewa', 'buram', 'nyesal'],
                'status' => 0
            ],
            [
                'nama_produk' => 'SAMSUNG SSD 860EVO 1TB / 2.5" SATA / 860 EVO SSD / 5 years warranty',
                'ulasan' => 'Barangnya bagus dan ngebaca datanya itu cepet banget',
                'stemming' => ['bagus', 'cepet'],
                'status' => 1
            ],
            [
                'nama_produk' => 'MSI GF65 Thin 10UE [9S7-16W212-202] i5-10200H 16G 512GB RTX3060 6GB',
                'ulasan' => 'Airflownya jelek banget, jadi bikin laptop cepet panas klo buat main game',
                'stemming' => ['jelek', 'panas'],
                'status' => 0
            ],
            [
                'nama_produk' => 'CANON EOS 750D KIT 18-55MM IS STM PAKET BONUS 6 ITEM - KAMERA SLR - A. STANDARD',
                'ulasan' => 'kecewa kamera nya blank gabisa dipake , saya mau kembaliin kamera nya nih . kalo bisa uang nya juga kembali.',
                'stemming' => ['kecewa', 'blank'],
                'status' => 0
            ],
        ];

        if (request()->ajax()) {

            return datatables()::of($sentimens)
            ->addColumn('status', function($data) {
                return '<span class="badge '. (($data['status'] == 1) ? "badge-primary":"badge-danger") .'">' . (($data['status'] == 1) ? "Positif":"Negatif") .'</span>';
            })
            ->rawColumns(['status'])
            ->addIndexColumn()
            ->make(true);
        }
        // return $sentimens;
        return view('admin.sentimen.index');
    }
}
