<?php

namespace App\Http\Controllers;

use App\Models\EmployeeUpdate;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function index()
    {
        return view('profile.login');
    }

    public function registerPage()
    {
        return view('profile.register');
    }

    public function register(Request $request)
    {

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:8',
        ]);
        // Check if a user with the given email already exists
        $existingUser = User::where('email', $validatedData['email'])->first();

        if ($existingUser) {
            // User with the provided email already exists, redirect back with a message
            return redirect()->route('home')->with([
                'status' => 'Email is already registered. Please login now.',
                'class' => 'info',
            ]);
        }

        // Create and save the new user
        $user = new User();
        $user->name = $validatedData['name'];
        $user->email = $validatedData['email'];
        $user->password = Hash::make($validatedData['password']);
        $user->save();

        // Redirect to the login page with a success message
        return redirect()->route('home')->with([
            'status' => 'Registration successful. Please login now.',
            'class' => 'success',
        ]);
    }

    public function login(Request $request)
    {
        $credetials = [
            'email' => $request->email,
            'password' => $request->password,
        ];

        if (Auth::attempt($credetials)) {
            $userType = User::where('email', $request->email)->first();
            if ($userType->user_type == 'emp') :
                return redirect('/update-page')->with('success', 'Login Success');
            else :
                $deprt = match ($userType->user_type) {
                    'qa' => 'Quality Assurance Department',
                    'glp' => 'Good Laboratory Practice Department',
                    'finance' => 'Finance and Accounting Department',
                    default => ''
                };
                $employees = EmployeeUpdate::where('department', $userType->user_type)->get();
                $today = Carbon::today()->toDateString();
                $current = $employees->where('updated_at', '>=', $today . ' 00:00:00')
                    ->where('updated_at', '<=', $today . ' 23:59:59')
                    ->count();
                return view('admin.index', compact('employees', 'current', 'deprt'));
            endif;
        }

        // return back()->with('error', 'Error Email or Password');
        return redirect()->route('home')->with([
            'status' => 'Error Email or Password',
            'class' => 'danger',
        ]);
    }

    public function logout()
    {
        Auth::logout();

        return view('profile.login');
    }
}
