<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DevisionsController extends Controller
{
    public function index()
    {
        return response()->json([
            'status' => 'success',
        ]);
    }
}
