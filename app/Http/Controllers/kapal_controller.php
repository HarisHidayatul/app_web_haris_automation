<?php

namespace App\Http\Controllers;

use App\Models\kapal;
use App\Models\pekerjaan;
use Illuminate\Http\Request;

class kapal_controller extends Controller
{
    //code untuk search pada kapal
    public function search_kapal(Request $request)
    {
        $query = $request->input('query');
        $kapal = Kapal::where('nama_kapal', 'LIKE', "%{$query}%")->get();

        return response()->json($kapal);
    }
    public function search_kapal_form(Request $request)
    {
        $query = $request->input('query');
        $kapal = Kapal::where('nama_kapal', 'LIKE', "%{$query}%")->get();

        return view('search_kapal.index', compact('kapal', 'query'));
    }

    public function show_kapal()
    {
        $kapal = kapal::all();
        return view('search_kapal.index', compact('kapal'));
    }

    public function show_kapal_detail($id)
    {
        $kapal = kapal::find($id);
        // @dd($kapal);
        return view('show_kapal_desc.index', compact('kapal'));
    }

    public function show_kapal_detail_api(Request $request)
    {
        $id = $request->id;
        $kapal = kapal::find($id);

        $nama_kapal = $kapal->nama_kapal;
        $id_kapal = $kapal->id;

        return response()->json([
            'nama_kapal' => $nama_kapal,
            'id_kapal' => $id_kapal
        ]);
    }

    public function create_kapal()
    {
        return view('create_kapal.index');
    }

    public function store_kapal(Request $request)
    {
        // Validasi input
        $request->validate([
            'nama_kapal' => 'required|string|max:255',
            // 'email' => 'required|string|email|max:255|unique:users',
            // 'password' => 'required|string|min:8|confirmed',
        ]);

        // Simpan data pengguna baru
        kapal::create([
            'nama_kapal' => $request->nama_kapal,
        ]);

        // return redirect()->route('user.create')->with('success', 'User created successfully.');
        return redirect()->route('create.kapal')->with('success', 'Kapal berhasil ditambahkan.');
    }

    public function store_kapal_api(Request $request)
    {
        // Validasi input
        $request->validate([
            'nama_kapal' => 'required|string|max:255',
            // 'email' => 'required|string|email|max:255|unique:users',
            // 'password' => 'required|string|min:8|confirmed',
        ]);

        // Simpan data pengguna baru
        kapal::create([
            'nama_kapal' => $request->nama_kapal,
        ]);
        return response()->noContent(201);
    }

    public function search_pekerjaan(Request $request)
    {
        $query = $request->input('search'); // Mengambil nilai parameter 'search'
        $pekerjaan = pekerjaan::where('nama_pekerjaan', 'LIKE', "%{$query}%")->get();

        return response()->json($pekerjaan);
    }
}
