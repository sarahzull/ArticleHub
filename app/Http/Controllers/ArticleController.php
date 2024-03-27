<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    public function index()
    {
        return Inertia::render('Articles/Index');
    }
    
    public function hotPicks()
    {
        return Inertia::render('Articles/HotPicks');
    }

    public function create()
    {
        return Inertia::render('Articles/Create');
    }
}
