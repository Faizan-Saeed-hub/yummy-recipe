<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CustoemrHomeController extends Controller
{
    public function index(){
        return view('Customer.Layout.index');
    }
}
