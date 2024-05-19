<?php

namespace App\Repositories;

use App\Models\Article;

class ArticleRepository
{
  public function getBasicArticles($limit = 10)
  {
    return Article::with(['author', 'category'])
      ->where('is_premium', 0)
      ->limit($limit)
      ->get();
  }

  public function getPremiumArticles($limit = 10)
  {
    return Article::with(['author', 'category'])
      ->where('is_premium', 1)
      ->limit($limit)
      ->get();
  }

  public function getTopArticles($limit = 10)
  {
    return Article::with(['author', 'category'])
      ->orderBy('views', 'desc')
      ->limit($limit)
      ->get();
  }
}
