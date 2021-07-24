<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BlogController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $slug = null)
    {
        $routeName = explode(".",$request->route()->getName())[2] ?? null;
        $categories = Category::where('is_visible', true)
            ->latest()
            ->get();
        $category = Category::firstWhere('slug', $slug);

        if ($routeName === 'me') {
            $posts = Post::where('user_id', Auth::user()->id)
                ->with('publisher', 'author', 'comments.commenter')
                ->latest()
                ->get();
        } else {
            $posts = Post::where('is_visible', true)
                ->whereNotNull('published_at')
                ->with('author', 'publisher', 'comments.commenter')
                ->when($routeName === 'post', function($query) use ($slug) {
                    return $query
                        ->where('slug', $slug);
                })
                ->when($routeName === 'category', function($query) use ($slug) {
                    return $query->whereHas('category', function ($query) use ($slug) {
                        return $query->where('slug', $slug);
                    });
                })
                ->when($request->filled('search'), function ($query) use ($request) {
                    return $query
                        ->where('title', 'like', "%{$request->query('search')}%");
                })
                ->latest()
                ->get();
        }

        return view('pages.blog', [
            'categories' => $categories,
            'posts' => $posts, 
            'routeName' => $routeName,
            'searchQuery' => $request->query('search'),
            'selectedCategory' => $category->name ?? '',
        ]);
    }
}
