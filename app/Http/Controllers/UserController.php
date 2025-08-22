<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
class UserController extends Controller
{
    public function index()
    {
        // dd(Auth::user());
        return view('user.index');
    }

    public function getUserList()
    {
        $users = User::latest()->paginate(2);

        return response()->json([
            'success'=>true,
            'data' => $users->items(),
            'current_page' => $users->currentPage(),
            'total_pages' => $users->lastPage(),
        ]);
    }

    

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'email' => 'required|unique:users|max:255|email',
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['name']), // or generate/send a password
        ]);

        return response()->json(['success'=> 'Data saved successfully']);
    }

    public function search(Request $request)
    {
        $validated = $request->validate([
            'user_search' => 'required|string',
        ]);
        
        $users =  User::where(function($query) use ($request) {
          $query->where('name', 'like', "%{$request->user_search}%")
                ->orWhere('email', 'like', "%{$request->user_search}%");  
        })->paginate(2);

        $users = User::where(function($query) use ($request) {
            $query->where('name', 'like', "%{$request->user_search}%")
                  ->orWhere('email', 'like', "%{$request->user_search}%");
        })->paginate(2);

        return response()->json([
            'success'=>true,
            'data' => $users->items(),
            'current_page' => $users->currentPage(),
            'total_pages' => $users->lastPage(),
        ]);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $id,
        ]);

        User::where('id', $id)->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
        ]);

        return response()->json(['success' => 'Data updated successfully']);
    }

    public function delete(Request $request, $id)
    {
        // Manually validate the route parameter
        $request->merge(['id' => $id]);

        $validated = $request->validate([
            'id' => 'required|exists:users,id'
        ]);

        User::findOrFail($id)->delete();

        return response()->json(['success' => 'Data deleted successfully']);
    }

    public function showLoginForm(Request $request)
    {
        return view('user.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('/dashboard');
        }

        return back()->withErrors([
            'email' => 'invalid credentials',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login'); // redirect to login or home
    }


    public function showResetForm(Request $request)
    {
        return view('user.reset_password');
    }

    public function sendResetPasswordOtp(Request $request)
    {
        dd($request->all());
    }


}
