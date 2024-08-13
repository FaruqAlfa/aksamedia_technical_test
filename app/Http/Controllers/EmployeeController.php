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
        try {
            $query = Employee::with('division');

            // Memfilter employee berdasarkan name
            if ($request->has('name')) {
                $query->where('name', 'like', '%' . $request->name . '%');
            }

            // Memfilter employee berdasarkan divisi
            if ($request->has('division_id')) {
                $query->where('division_id', $request->division_id);
            }

            // Mendapatkan data dengan pagination
            $employees = $query->paginate(10);

            return response()->json([
                'status' => 'success',
                'message' => 'Data employees retrieved successfully',
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
            // Jika terjadi kesalahan mengambil data, kembalikan respons error
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error retrieving employees',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            // Reques data validasi
            $validator = Validator::make($request->all(), [
                'name' => 'required|string',
                'phone' => 'required',
                'position' => 'required',
                'image' => 'required|image|mimes:jpeg,png,jpg,gif',
                'division_id' => 'required|exists:divisions,id',
            ]);

            // Check if validation fails
            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => $validator->errors()->first(),
                ], 400);
            }

            // Upload image
            $imagePath = $request->file('image')->store('employee_images', 'public');

            // Create new employee
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

            // Jika terjadi kesalahan membuat karyawan, kembalikan respons error
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error creating employee',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    
    public function update(Request $request, $uuid)
    {
        try {
            // Request data validasi
            $validator = Validator::make($request->all(), [
                'name' => 'required|string',
                'phone' => 'required|string',
                'division_id' => 'required|exists:divisions,id',
                'position' => 'required|string|max:255',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
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

            // Update data karyawan
            $employee->name = $request->input('name');
            $employee->phone = $request->input('phone');
            $employee->division_id = $request->input('division_id');
            $employee->position = $request->input('position');
            $employee->save();

            return response()->json([
                'status' => 'success',
                'message' => 'Employee updated successfully',
            ], 200);

            // Jika terjadi kesalahan saat memperbarui karyawan, kembalikan respons error
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error while updating employee',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function destroy($uuid)
    {
        try {
            // Mendapatkan employee by UUID
            $employee = Employee::where('id', $uuid)->first();

            if (!$employee) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Employee not found.',
                ], 404);
            }

            // Hapus employee
            $employee->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Employee deleted successfully',
            ], 200);

            // Jika terjadi kesalahan saat menghapus karyawan, kembalikan respons error
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error while deleting employee',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}