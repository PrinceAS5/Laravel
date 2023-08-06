<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use App\Models\EmployeeUpdate;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class UpdateController extends Controller
{

    public function index()
    {
        return view('update');
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'textUpdate' => 'required_without_all:imageUpdate,pdfUpdate|nullable',
            'imageUpdate' => 'required_without_all:textUpdate,pdfUpdate|nullable',
            'pdfUpdate' => 'required_without_all:textUpdate,imageUpdate|nullable'
        ]);

        if ($validator->fails()) {
            // Validation failed, redirect back with errors
            return redirect()->back()->with([
                'status' => 'At least one of the fields of update is required.',
                'class' => 'danger',
            ]);
        }

        try {

            $dept = $request->input('department');
            $textUpdate = $request->input('textUpdate');
            $imageUpdate = $request->file('imageUpdate');
            $pdfUpdate = $request->file('pdfUpdate');

            $authenticatedUser = Auth::user();

            // Save the files to the storage (e.g., public/uploads) and get their paths
            $imageName = time() . $imageUpdate->getClientOriginalName();
            $imagePath = $imageUpdate ? $imageUpdate->storeAs('images', $imageName, 'public') : null;
            $pdfName = time() . $pdfUpdate->getClientOriginalName();
            $pdfPath = $pdfUpdate ? $pdfUpdate->storeAs('pdf',  $pdfName, 'public') : null;
            // Create a new update record in the database using the EmployeeUpdate model
            $update = EmployeeUpdate::create([
                'name' => $authenticatedUser->name,
                'textUpdate' => $textUpdate,
                'imageUpdate' => $imagePath,
                'pdfUpdate' => $pdfPath,
                'department' => $dept,
            ]);

            if ($update) :
                return redirect()->back()->with([
                    'status' => 'updated Successfully',
                    'class' => 'success',
                ]);
            else :
                return redirect()->back()->with([
                    'status' => 'something went wrong...!',
                    'class' => 'danger',
                ]);
            endif;
        } catch (\Exception $e) {
            // Handle any errors that occurred during database insertion
            return redirect()->back()->with([
                'status' => 'Error: Could not update',
                'class' => 'danger',
            ]);
        }
    }

    public function edit($id)
    {
        $emp = EmployeeUpdate::find($id);
        return view('edit', compact('emp'));
    }
    public function add($id)
    {
        return view('add', compact('id'));
    }
    public function editUpdate(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'textUpdate' => 'required_without_all:imageUpdate,pdfUpdate|nullable',
            'imageUpdate' => 'required_without_all:textUpdate,pdfUpdate|nullable',
            'pdfUpdate' => 'required_without_all:textUpdate,imageUpdate|nullable'
        ]);

        if ($validator->fails()) {
            // Validation failed, redirect back with errors
            return redirect()->back()->with([
                'status' => 'At least one of the fields of update is required.',
                'class' => 'danger',
            ]);
        }

        try {

            $dept = $request->input('department');
            $textUpdate = $request->input('textUpdate');
            $imageUpdate = $request->file('imageUpdate');
            $pdfUpdate = $request->file('pdfUpdate');

            $authenticatedUser = Auth::user();

            // Save the files to the storage (e.g., public/uploads) and get their paths
            if ($imageUpdate)
                $imageName = time() . $imageUpdate->getClientOriginalName();
            $imagePath = $imageUpdate ? $imageUpdate->storeAs('images', $imageName, 'public') : null;
            if ($pdfUpdate)
                $pdfName = time() . $pdfUpdate->getClientOriginalName();
            $pdfPath = $pdfUpdate ? $pdfUpdate->storeAs('pdf',  $pdfName, 'public') : null;
            // Create a new update record in the database using the EmployeeUpdate model
            $employeeUpdate = EmployeeUpdate::where('id', $id)->first();
            if (!$employeeUpdate) {
                return redirect()->back()->with([
                    'status' => 'Employee update not found.',
                    'class' => 'danger',
                ]);
            }
            $update = $employeeUpdate->update([
                'name' => $authenticatedUser->name,
                'textUpdate' => $textUpdate,
                'imageUpdate' => $imagePath,
                'pdfUpdate' => $pdfPath,
                'department' => $dept,
            ]);

            if ($update) :
                $deprt = match ($dept) {
                    'qa' => 'Quality Assurance Department',
                    'glp' => 'Good Laboratory Practice Department',
                    'finance' => 'Finance and Accounting Department',
                    default => ''
                };
                $employees = EmployeeUpdate::where('department', $dept)->get();
                $today = Carbon::today()->toDateString();
                $current = $employees->where('updated_at', '>=', $today . ' 00:00:00')
                    ->where('updated_at', '<=', $today . ' 23:59:59')
                    ->count();
                return view('admin.index', compact('employees', 'current', 'deprt'));
            else :
                return redirect()->back()->with([
                    'status' => 'something went wrong...!',
                    'class' => 'danger',
                ]);
            endif;
        } catch (\Exception $e) {
            // Handle any errors that occurred during database insertion
            return redirect()->back()->with([
                'status' => 'Error: Could not update',
                'class' => 'danger',
            ]);
        }
    }

    public function delete($id)
    {

        try {
            // Find the EmployeeUpdate record by ID
            $employeeUpdate = EmployeeUpdate::find($id);
            if (Storage::exists('public/' . $employeeUpdate->imageUpdate))
                Storage::delete('public/' . $employeeUpdate->imageUpdate);
            if (Storage::exists('public/' . $employeeUpdate->pdfUpdate))
                Storage::delete('public/' . $employeeUpdate->pdfUpdate);

            $employeeUpdate->delete();
            $employees = EmployeeUpdate::where('department', $employeeUpdate->department)->get();
            $deprt = $employeeUpdate->department;
            $today = Carbon::today()->toDateString();
            $current = $employees->where('updated_at', '>=', $today . ' 00:00:00')
                ->where('updated_at', '<=', $today . ' 23:59:59')
                ->count();
            return view('admin.index', compact('employees', 'current', 'deprt'));
        } catch (\Exception $e) {
            // Handle any errors that occurred during database insertion
            return redirect()->back()->with([
                'status' => 'Error: Could not delete file',
                'class' => 'danger',
            ]);
        }
    }
}
