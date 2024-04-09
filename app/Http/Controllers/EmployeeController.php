<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\PayUService\Exception;

class EmployeeController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
        $this->middleware('role:Employee');
    }

    public function index()
    {
        try {
            return view('employee.home');
        } catch (\Exception $e) {
            return $e->getMessage();
        }
        
    }
}
