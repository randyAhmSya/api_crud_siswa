<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Siswa;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class SiswaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            return Siswa::all();
        } catch (\Exception $e) {
            return response()->json(['error' => 'Gagal mengambil datasiswa.'], 500);
        }
    }

    /**
     * Show the form for creating a new resource.
     */

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => ['required', 'string', 'max:255', 'regex:/^[a-zA-Z\s]+$/'],
            'kelas' => ['required', 'string', 'max:10', 'regex:/^(X|XI|XII)\s(IPA|IPS)\s[1-9]$/'],
            'umur' => 'required|integer|min:6|max:18'
        ], [
            'nama.regex' => 'Nama hanya boleh mengandung huruf dan spasi',
            'kelas.regex' => 'Format kelas harus seperti "XII IPA 1"',
            'umur.min' => 'Umur minimal adalah 6 tahun',
            'umur.max' => 'Umur maksimal adalah 18 tahun'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $siswa = Siswa::create($request->all());
            return response()->json([
                'status' => 'success',
                'data' => $siswa
            ], 201);
        } catch (\Exception $e) {
            Log::error('Error creating student: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menyimpan data siswa.'
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $siswa = Siswa::findOrFail($id);
            return response()->json([
                'status' => 'success',
                'data' => $siswa
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching student: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Siswa tidak ditemukan.'
            ], 404);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'nama' => ['sometimes', 'required', 'string', 'max:255', 'regex:/^[a-zA-Z\s]+$/'],
            'kelas' => ['sometimes', 'required', 'string', 'max:10', 'regex:/^(X|XI|XII)\s(IPA|IPS)\s[1-9]$/'],
            'umur' => 'sometimes|required|integer|min:6|max:18'
        ], [
            'nama.regex' => 'Nama hanya boleh mengandung huruf dan spasi',
            'kelas.regex' => 'Format kelas harus seperti "XII IPA 1"',
            'umur.min' => 'Umur minimal adalah 6 tahun',
            'umur.max' => 'Umur maksimal adalah 18 tahun'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $siswa = Siswa::findOrFail($id);
            $siswa->update($request->all());
            return response()->json([
                'status' => 'success',
                'data' => $siswa
            ]);
        } catch (\Exception $e) {
            Log::error('Error updating student: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal memperbarui data siswa.'
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $siswa = Siswa::findOrFail($id);
            $siswa->delete();
            return response()->json([
                'status' => 'success',
                'message' => 'Data siswa berhasil dihapus'
            ], 200);
        } catch (\Exception $e) {
            Log::error('Error deleting student: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menghapus data siswa.'
            ], 500);
        }
    }
}
