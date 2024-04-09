<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\PayUService\Exception;

class TeamLeaderController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
        $this->middleware('role:Team Lead');
    }

    public function index() {
        try {
            return view('teamLead.home');
        } catch (\Exception $e) {
            return $e->getMessage();
        }
        
    }
}
