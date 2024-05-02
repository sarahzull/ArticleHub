<?php

namespace App\Http\Controllers;

use App\Models\User;
use Inertia\Inertia;
use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ArticleController extends Controller
{
    public function index()
    {
        //get top authors
        // $topAuthors = User::select('users.*', DB::raw('COUNT(articles.id) as article_count'))
        // ->join('articles', 'users.id', '=', 'articles.author_id')
        // ->groupBy('users.id')
        // ->orderByDesc('article_count')
        // ->limit(5)
        // ->get();

        $topAuthors = User::select('users.id', 'users.name', DB::raw('COUNT(articles.id) as article_count'))
        ->leftJoin('articles', 'users.id', '=', 'articles.author_id')
        ->groupBy('users.id', 'users.name')
        ->orderByDesc('article_count')
        ->limit(5)
        ->get();

        //get is_premium articles
        $premiumArticles = Article::with(['author', 'category'])->where('is_premium', 1)
        ->limit(10)
        ->get();

        return Inertia::render('Article/Index', [
            'topAuthors' => $topAuthors,
            'premiumArticles' => $premiumArticles
        ]);
    }
    
    public function hotPicks()
    {
        return Inertia::render('Article/HotPicks');
    }

    public function create()
    {
        return Inertia::render('Article/Create');
    }
}
