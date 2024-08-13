<?php

namespace App\Http\Controllers;

use App\Models\Divisions;
use Illuminate\Http\Request;

class DevisionsController extends Controller
{
    public function index(Request $request)
    {
        try {
            // Ambil parameter nama untuk filter
            $name = $request->input('name');

            // Ambil data devisi berdasarkan parameter name
            $divisions = Divisions::when($name, function ($query, $name) {
                $query->where('name', 'like', '%' . $name . '%');
            })->paginate(10);

            return response()->json([
                'status' => 'success',
                'message' => 'Data divisions retrieved successfully',
                'data' => [
                    'divisions' => $divisions->items(),
                ],
                'pagination' => $divisions->toArray(),
            ]);
            // Jika terjadi kesalahan mengambil data, kembalikan respons error
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error while retrieving divisions',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
