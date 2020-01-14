<?php

namespace App\Articles;

use App\Article;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;

class EloquentRepository implements ArticlesRepository
{
    public function __construct()
    {
        Log::info('EloquentRepository');
    }

    public function search(string $query = ''): Collection
    {
        return Article::query()
            ->where('body', 'like', "%{$query}%")
            ->orWhere('title', 'like', "%{$query}%")
            ->get();
    }
}
