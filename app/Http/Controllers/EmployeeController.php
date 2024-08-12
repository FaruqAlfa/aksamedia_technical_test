<?php

namespace App\Http\Controllers;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class EmployeeController extends Controller
{
    public function index(Request $request)
    {
        $query = Employee::with('division');

        // memfilter employee berdasarkan name
        if ($request->has('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        // memfilter employee berdasarkan divisi
        if ($request->has('division_id')) {
            $query->where('division_id', $request->division_id);
        }

        // mendapatkan data dengan pagination
        $employees = $query->paginate(10);

        return response()->json([
            'status' => 'success',
            'message' => 'Employees retrieved successfully',
            'data' => [
                'employees' => $employees->items(),
            ],
            'pagination' => [
                'current_page' => $employees->currentPage(),
                'last_page' => $employees->lastPage(),
                'per_page' => $employees->perPage(),
                'total' => $employees->total(),
            ],
        ]);
    }

    public function store(Request $request)
    {
        //request validation data
        $validator = Validator::make($request->all(),[
            'name' => 'required|string',
            'phone' => 'required',
            'position' => 'required',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif',
            'division_id' => 'required|exists:divisions,id',
        ]);

        //check if validation fails
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()->first(),
            ], 400);
        }

        //upload image
        $imagePath = $request->file('image')->store('employee_images', 'public');

        //create new employee
        $employee = Employee::create([
            'name' => $request->name,
            'phone' => $request->phone,
            'position' => $request->position,
            'image' => $imagePath,
            'division_id' => $request->division_id,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Employee created successfully',
            'data' => [
                'employee' => $employee,
            ],
        ]);
    }

    public function update(Request $request, $uuid)
    {
         // request validation
         $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:15',
            'division' => 'required|exists:divisions,id',
            'position' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif', // Gambar opsional
        ]);

        // Cek apakah validasi gagal
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()->first(),
            ], 400);
        }

        // Temukan karyawan berdasarkan UUID
        $employee = Employee::where('id', $uuid)->first();

        if (!$employee) {
            return response()->json([
                'status' => 'error',
                'message' => 'Employee not found.',
            ], 404);
        }

        // Update data karyawan
        $employee->name = $request->name;
        $employee->phone = $request->phone;
        $employee->division_id = $request->division;
        $employee->position = $request->position;

        // Cek jika file gambar ada di request
        if ($request->hasFile('image')) {
            // Hapus gambar lama jika ada
            if ($employee->image) {
                Storage::disk('public')->delete($employee->image);
            }

            // Simpan gambar baru
            $imagePath = $request->file('image')->store('employee_images', 'public');
            $employee->image = $imagePath;
        }

        // Simpan perubahan
        $employee->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Employee updated successfully',
        ], 200);
    
    }

    public function destroy($uuid)
    {
        // gett employee by uuid
        $employee = Employee::where('id', $uuid)->first();

        if (!$employee) {
            return response()->json([
                'status' => 'error',
                'message' => 'Employee not found.',
            ], 404);
        }  

        //if karyawan uuid get Hapus karyawan
        $employee->delete();
        
        return response()->json([
            'status' => 'success',
            'message' => 'Employee deleted successfully',
        ], 200);
    }
}