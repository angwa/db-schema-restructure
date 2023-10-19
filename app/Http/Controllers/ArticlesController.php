<?php

namespace App\Http\Controllers;

use App\Http\Resources\ArticlesResources;
use App\Models\Article;
use App\Models\ArticleProvider;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ArticlesController extends Controller
{
    public function index()
    {
        $articles = Article::with('articleProvider')->paginate(10);
        $data = ArticlesResources::collection($articles);

        return $this->jsonResponse(HTTP_SUCCESS, 'Articles returned successfully.', $data);
    }

    public function store(Request $request)
    {
        $request->validate([
            'provider_id' => 'required|numeric|exists:providers,id',
            'article_no' => 'required|numeric',
            'article' => 'required|string|max:200',
            'price' => 'required|numeric',
        ]);

        $article_no = $request->article_no;

        //Lets prevent duplicate record
        $article_providers = ArticleProvider::where('provider_id', $request->provider_id)
            ->with(['article' => function ($query) use ($article_no) {
                $query->where('article_no', $article_no);
            }])->whereHas('article', function ($query) use ($article_no) {
                $query->where('article_no', $article_no);
            })->first();

        abort_if($article_providers, HTTP_BAD_REQUEST, 'This article with same provider has been previously recorded');

        try {
            $article = null;
            DB::transaction(function () use ($request, &$article) {
                $article = Article::create([
                    'article_no' => $request->article_no,
                    'article' => $request->article
                ]);

                $article->articleProvider()->create([
                    'provider_id' => $request->provider_id,
                    'price' => $request->price
                ]);
            });

            return $this->jsonResponse(HTTP_CREATED, 'Article created successfully.',  new ArticlesResources($article));
        } catch (Exception $e) {
            Log::error($e);
            abort(HTTP_SERVER_ERROR, 'Something went wrong. Please try again');
        }
    }

    public function update(Request $request, Article $article)
    {
        $request->validate([
            'article_name' => 'nullable|string|max:200',
            'price' => 'nullable|numeric',
        ]);

        try {
            DB::transaction(function () use ($request, $article) {
                $article->articleProvider()->update([
                    'price' => $request->price ?? $article->articleProvider->price
                ]);

                $article->update([
                    'article' => $request->article_name ?? $article->article
                ]);
            });

            return $this->jsonResponse(HTTP_SUCCESS, 'Article updated successfully.',  new ArticlesResources($article));
        } catch (Exception $e) {
            Log::error($e);
            abort(HTTP_SERVER_ERROR, 'Something went wrong. Please try again');
        }
    }

    public function delete(Article $article)
    {
        try {
            DB::transaction(function () use ($article) {
                $article->articleProvider()->delete();
                $article->delete();
            });

            return $this->jsonResponse(HTTP_SUCCESS, 'Article along with provider deleted successfully.');
        } catch (Exception $e) {
            Log::error($e);
            abort(HTTP_SERVER_ERROR, 'Something went wrong. Please try again');
        }
    }
}
