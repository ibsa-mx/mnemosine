<?php

namespace Mnemosine\Http\Controllers;

use Illuminate\Http\Request;
use Mnemosine\Piece;
use Mnemosine\Research;
use Mnemosine\Restoration;
use Mnemosine\Movement;
use Mnemosine\Report;
use Mnemosine\User;
use Mnemosine\Role;

class HomeController extends Controller
{

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $pieces = Piece::all()->count();
        $appraisal = Piece::all()->sum('appraisal');
        $researchs = Research::all()->count();
        $restorations = Restoration::all()->count();
        $movements = Movement::all()->count();
        $reports = Report::all()->count();
        $users = User::all()->count();
        $roles = Role::all()->count();
        return view('home', compact('pieces', 'researchs', 'restorations', 'movements', 'reports', 'users', 'roles', 'appraisal'));
    }

}
