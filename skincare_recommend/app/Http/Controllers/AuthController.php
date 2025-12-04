<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
  /**
   * Show the login form
   */
  public function showLoginForm()
  {
    return view('login');
  }

  /**
   * Show the registration form
   */
  public function showRegisterForm()
  {
    return view('register');
  }

  /**
   * Handle login request
   */
  public function login(Request $request)
  {
    $credentials = $request->validate([
      'email' => 'required|string|email',
      'password' => 'required|string',
    ]);

    if (Auth::attempt($credentials)) {
      $request->session()->regenerate();

      // Check the user's role and redirect accordingly
      if (Auth::user()->role == 'user') {
        return redirect()->route('user.dashboard');
      } elseif (Auth::user()->role == 'admin') {
        return redirect()->route('admin.dashboard');
      }
    }

    return back()->withErrors([
      'error' => 'Email atau password salah',
    ])->onlyInput('email');
  }

  /**
   * Handle registration request
   */
  public function register(Request $request)
  {
    $validator = Validator::make($request->all(), [
      'name' => 'required|string|max:255',
      'email' => 'required|string|email|max:255|unique:users',
      'password' => 'required|string|min:8|confirmed',
    ]);

    if ($validator->fails()) {
      return back()
        ->withErrors($validator)
        ->withInput();
    }

    $user = User::create([
      'name' => $request->name,
      'email' => $request->email,
      'password' => Hash::make($request->password),
      'role' => 'user',
    ]);

    Auth::login($user);

    return redirect()->route('user.dashboard')->with('success', 'Registrasi berhasil!');
  }

  /**
   * Handle logout request
   */
  public function logout(Request $request)
  {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect('/login');
  }
}
