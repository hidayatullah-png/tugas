<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // Import DB facade for database operations

class BukuController extends Controller
{
    public function index()
    {
        // Fetch all books from the 'buku' table
        $bukus = DB::table('buku')->leftJoin('kategori', 'buku.kategori_id', '=', 'kategori.id')->select('buku.*', 'kategori.nama as kategori_nama')->get();

        // Return the view with the list of books
        return view('admin.buku.index', compact('buku'));
    }

    public function create()
    {
        // Fetch all categories to populate the category dropdown
        $kategoris = DB::table('kategori')->get();

        // Return the view for creating a new book
        return view('admin.buku.create', compact('kategori'));
    }

    public function store(Request $request)
    {
        // Validate the incoming request data
        $request->validate([
            'kode' => 'required|unique:buku,kode|max:20',
            'judul' => 'required|max:500',
            'pengarang' => 'nullable|max:200',
            'idkategori' => 'nullable|exists:kategori,idkategori',
        ]);

        // Insert the new book into the database
        DB::table('buku')->insert([
            'judul' => $request->input('judul'),
            'pengarang' => $request->input('pengarang'),
            'penerbit' => $request->input('penerbit'),
            'tahun_terbit' => $request->input('tahun_terbit'),
            'kategori_id' => $request->input('kategori_id'),
            'created_at' => now(),
            'deleted_at' => null,
        ]);

        // Redirect back to the book index page with a success message
        return redirect()->route('admin.buku.index')->with('success', 'Buku berhasil ditambahkan!');
    }
    public function edit($id)
    {
        // Fetch the book to be edited
        $buku = DB::table('buku')->where('id', $id)->first();

        // Fetch all categories to populate the category dropdown
        $kategoris = DB::table('kategori')->get();

        // Return the view for editing the book
        return view('buku.edit', compact('buku', 'kategori'));
    }
    public function delete($id)
    {
        // Soft delete the book by setting the 'deleted_at' timestamp
        DB::table('buku')->where('id', $id)->update(['deleted_at' => now()]);

        // Redirect back to the book index page with a success message
        return redirect()->route('admin.buku.index')->with('success', 'Buku berhasil dihapus!');
    }
}

?>