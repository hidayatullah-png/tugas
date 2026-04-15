<?php
namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class VendorCustomerController extends Controller
{
    protected $baseUrl = 'https://www.emsifa.com/api-wilayah-indonesia/api';

    // ===================== INDEX =====================
    public function index()
    {
        $customers = DB::table('customers')->orderBy('created_at', 'desc')->get();
        return view('dashboard.vendor.data_master.customer.index', compact('customers'));
    }

    // ===================== CREATE 1 (BLOB) =====================
    public function create1()
    {
        $provinces = Http::get("{$this->baseUrl}/provinces.json")->json();
        return view('dashboard.vendor.data_master.customer.create1', compact('provinces'));
    }

    // ===================== STORE 1 (BLOB) =====================
    public function store1(Request $request)
    {
        $request->validate(['nama_customer' => 'required', 'image_data' => 'required']);

        $image_parts = explode(";base64,", $request->image_data);
        $image_base64 = base64_decode($image_parts[1]);

        DB::table('customers')->insert([
            'nama_customer' => $request->nama_customer,
            'alamat' => $request->alamat,
            'provinsi' => $request->provinsi,
            'kota' => $request->kota,
            'kecamatan' => $request->kecamatan,
            'kelurahan_kodepos' => $request->kelurahan_kodepos,
            'foto_blob' => $image_base64,
            'created_at' => now()
        ]);

        return redirect()->route('vendor.customer.index')->with('success', 'Customer (BLOB) Berhasil Terdaftar!');
    }

    // ===================== CREATE 2 (PATH) =====================
    public function create2()
    {
        $provinces = Http::get("{$this->baseUrl}/provinces.json")->json();
        return view('dashboard.vendor.data_master.customer.create2', compact('provinces'));
    }

    // ===================== STORE 2 (PATH) =====================
    public function store2(Request $request)
    {
        $request->validate(['nama_customer' => 'required', 'image_data' => 'required']);

        $image_parts = explode(";base64,", $request->image_data);
        $image_base64 = base64_decode($image_parts[1]);

        $fileName = 'cust_' . time() . '.png';
        Storage::put('public/customers/' . $fileName, $image_base64);

        DB::table('customers')->insert([
            'nama_customer' => $request->nama_customer,
            'alamat' => $request->alamat,
            'provinsi' => $request->provinsi,
            'kota' => $request->kota,
            'kecamatan' => $request->kecamatan,
            'kelurahan_kodepos' => $request->kelurahan_kodepos,
            'foto_path' => $fileName,
            'created_at' => now()
        ]);

        return redirect()->route('vendor.customer.index')->with('success', 'Customer (Path) Berhasil Terdaftar!');
    }
    // ===================== DESTROY =====================
    public function destroy($id)
    {
        // 1. Cari data customer
        $customer = DB::table('customers')->where('id', $id)->first();

        if (!$customer) {
            return redirect()->back()->with('error', 'Data tidak ditemukan!');
        }

        // 2. LOGIKA KHUSUS UNTUK PATH: Hapus file dari folder storage
        if ($customer->foto_path) {
            $path = 'public/customers/' . $customer->foto_path;
            if (Storage::exists($path)) {
                Storage::delete($path);
            }
        }

        // 3. Hapus data dari Database (Ini otomatis menghapus BLOB juga karena BLOB ada di dalam row)
        DB::table('customers')->where('id', $id)->delete();

        return redirect()->route('vendor.customer.index')->with('success', 'Customer berhasil dihapus!');
    }

    // ===================== API WILAYAH =====================

    /**
     * Endpoint: GET kota by provinsi id
     */
    public function getKota(Request $request)
    {
        $response = Http::get("{$this->baseUrl}/regencies/{$request->id}.json");
        return response()->json([
            'status' => 'success',
            'data' => $response->json()
        ]);
    }

    /**
     * Endpoint: GET kecamatan by kota id
     */
    public function getKecamatan(Request $request)
    {
        $response = Http::get("{$this->baseUrl}/districts/{$request->id}.json");
        return response()->json([
            'status' => 'success',
            'data' => $response->json()
        ]);
    }

    /**
     * Endpoint: GET kelurahan by kecamatan id
     */
    public function getKelurahan(Request $request)
    {
        $response = Http::get("{$this->baseUrl}/villages/{$request->id}.json");
        return response()->json([
            'status' => 'success',
            'data' => $response->json()
        ]);
    }
}