<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Http\Request;

class PlanController extends Controller
{
    public function index ()
    {
        return Inertia::render('Plan/Index');
    }
}
