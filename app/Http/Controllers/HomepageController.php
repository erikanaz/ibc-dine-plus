<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomepageController extends Controller
{
    public function index()
    {
        return view('customer.homepage'); // buat file resources/views/customer/homepage.blade.php
    }
}
