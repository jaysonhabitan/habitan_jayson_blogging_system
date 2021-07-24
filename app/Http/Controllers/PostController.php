<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\UnauthorizedException;

class PostController extends Controller
{
    /**
     * Instantiate a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['role:Super Admin|Admin|Editor|Author'], ['except' => ['index', 'update','create','show', 'store', 'destroy']]); 
        $this->middleware(['permission:manage-post'], ['except' => ['create', 'store', 'show', 'update', 'destroy']]); 
        $this->middleware(['permission:create-post'], ['only' => ['create,', 'store']]); 
        $this->middleware(['permission:update-post'], ['only' => ['update', 'show']]); 
        $this->middleware(['permission:delete-post'], ['only' => ['destroy']]); 
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $posts = Post::when($request->filled('search'), function ($query) use ($request) {
                return $query
                    ->where('title', 'like', "%{$request->query('search')}%");
            })
            ->latest()
            ->with('publisher')
            ->paginate(10);
        
        return view('pages.post.list', [
            'posts' => $posts,
            'searchQuery' => $request->query('search') ?? '',
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::all();

        return view('pages.post.add', [
                'categories' => $categories ?? []
            ]
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = $request->user();

        $request->validate([
            'data' => 'required',
            'data.attributes' => 'required',
            'data.attributes.category_id' => 'required|exists:categories,id',
            'data.attributes.title' => 'required|string',
            'data.attributes.body' => 'required|string',
            'data.attributes.slug' => [
                'required',
                'string',
                Rule::unique('posts', 'slug'),
            ],
            'data.attributes.is_visible' => 'sometimes|boolean',
        ]);
        
        return DB::transaction(function () use ($request, $user) {
            $user->posts()->create($request->input('data.attributes'));
            
            return $this->okResponse();
        });
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, int $id)
    {
        $post = Post::find($id);
        $categories = Category::all();
        
        if (!$post) {
            throw (new ModelNotFoundException)->setModel(Post::class);
        }

        return view('pages.post.add', [
            'post' => $post,
            'categories' => $categories,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, int $id)
    {
        $post = Post::findOrFail($id);

        $this->isAuthorizeToPerformAction($post);
        
        $request->validate([
            'data' => 'required',
            'data.attributes' => 'required',
            'data.attributes.category_id' => 'required|exists:categories,id',
            'data.attributes.title' => 'required|string',
            'data.attributes.body' => 'sometimes|nullable|string',
            'data.attributes.slug' => 'required|string',
            'data.attributes.is_visible' => 'sometimes|boolean',
        ]);
        
        return DB::transaction(function () use ($request, $post) {
            $post->update($request->input('data.attributes'));
            
            return $this->okResponse();
        });
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Post $post)
    {
        $this->isAuthorizeToPerformAction($post);
        
        if (!optional($post)->delete()) {
            throw (new ModelNotFoundException)->setModel(Post::class);
        }

        if ($request->filled('isFromBlogPage')) {
            return response()->json([], 204);
        }

        //The redirect route if request is from manage/admin page.
        return redirect(route('posts.index'));
    }

    /**
     * Check wether the user is authorize to perform the action.
     * 
     * @param  \App\Models\Post  $post
     * @return void
     */
    protected function isAuthorizeToPerformAction($post)
    {
        if (request()->user()->isContributor()) {
            if (Auth::user()->id !== $post->user_id) {
                throw new UnauthorizedException;
            }
        }
    }
}
