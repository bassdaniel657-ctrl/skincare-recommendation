<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
  public function index()
  {
    // Add any necessary data or logic here, like fetching statistics or user information
    return view('user.dashboard'); // Returns the admin dashboard view
  }
}
