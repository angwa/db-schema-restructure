<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArticleProvider extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function article()
    {
        return $this->belongsTo(Article::class);
    }

    public function provider()
    {
        return $this->belongsTo(Provider::class);
    }
}
