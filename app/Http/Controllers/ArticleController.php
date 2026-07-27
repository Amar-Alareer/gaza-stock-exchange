<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    public function index()
    {
        $articles = Article::all();

        return response()->json($articles);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        $article = Article::create($request->all());

        return response()->json($article, 201);
    }

    public function show($id)
    {
        $article = Article::findOrFail($id);

        return response()->json($article);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'content' => 'sometimes|required|string',
        ]);

        $article = Article::findOrFail($id);
        $article->update($request->all());

        return response()->json($article);
    }

    public function destroy($id)
    {
        Article::destroy($id);

        return response()->json(null, 204);
    }
}
