<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
// use App\Helpers\LocationHelper;
use App\Models\System;
use App\Models\Agent;
use Spatie\Permission\Models\Role;

class SystemController extends Controller
{
    //use Validator::class;
    protected $validator = null;
    public $location_helper = null;
    private $view_root = "system/";
    protected $firm = null;
    protected $user = null;
    public function __construct()
    {
        //--Here I Created the Validator Object to Avail It to all Child Classes for Form Validation
        $this->validator = new Validator;
        // $this->location_helper = new LocationHelper();
    }
    protected function self_sys()
    {
        $this->user = $logged_in = Auth::guard(session('my_guard'))->user();
        switch (session('my_guard')) {
            case 'system':
                $this->firm = $logged_in;
                break;
            case 'operator':
                $this->user = System::find($logged_in->agent_parent_sys);
                break;
            default:
                break;
        }
    }
    public function index()
    {
        return view($this->view_root . "dashboard");
    }

    public function rolepanel()
    {
        $roles = Role::all();
        return view('system.roles.rolelist', compact('roles'));
    }
}
