<?php

namespace App\Http\Controllers;

use App\Models\User;
use Inertia\Inertia;
use App\Repositories\ArticleRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ArticleController extends Controller
{
    protected $articleRepository;

    public function __construct(ArticleRepository $articleRepository)
    {
        $this->articleRepository = $articleRepository;
    }

    public function index()
    {
        $topAuthors = User::select('users.id', 'users.name', DB::raw('COUNT(articles.id) as article_count'))
            ->leftJoin('articles', 'users.id', '=', 'articles.author_id')
            ->groupBy('users.id', 'users.name')
            ->orderByDesc('article_count')
            ->limit(5)
            ->get();

        $premiumArticles = $this->articleRepository->getPremiumArticles();
        $basicArticles = $this->articleRepository->getBasicArticles();

        return Inertia::render('Article/Index', [
            'topAuthors' => $topAuthors,
            'premiumArticles' => $premiumArticles,
            'basicArticles' => $basicArticles
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
