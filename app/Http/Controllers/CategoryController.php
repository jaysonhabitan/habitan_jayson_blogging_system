<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
{
    /**
     * Instantiate a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['role:Super Admin|Admin|Editor'], ['except' => ['index', 'update','create','show', 'store', 'destroy']]); 
        $this->middleware(['permission:manage-category']); 
        $this->middleware(['permission:create-category'], ['only' => ['create', 'store']]); 
        $this->middleware(['permission:update-category'], ['only' => ['show', 'update']]); 
        $this->middleware(['permission:delete-category'], ['only' => ['destroy']]); 
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $categories = Category::when($request->filled('search'), function ($query) use ($request) {
                return $query
                    ->where('name', 'like', "%{$request->query('search')}%");
            })
            ->latest()
            ->paginate(10);
        
        return view('pages.category.list', [
            'categories' => $categories,
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
        return view('pages.category.add', ['category' => null]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'data' => 'required',
            'data.attributes' => 'required',
            'data.attributes.name' => 'required|string',
            'data.attributes.slug' => [
                'required',
                'string',
                Rule::unique('categories', 'slug'),
            ],
            'data.attributes.description' => 'nullable|string|max:255',
            'data.attributes.is_visible' => 'sometimes|boolean',
        ]);
        
        return DB::transaction(function () use ($request) {
            Category::create($request->input('data.attributes'));
            
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
        $category = Category::find($id);

        if (!$category) {
            throw (new ModelNotFoundException)->setModel(Category::class);
        }

        return view('pages.category.add', [
            'category' => $category
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
        $category = Category::findOrFail($id);

        $request->validate([
            'data' => 'required',
            'data.attributes' => 'required',
            'data.attributes.name' => 'required|string',
            'data.attributes.slug' => 'required|string',
            'data.attributes.description' => 'sometimes|nullable|string|max:255',
            'data.attributes.is_visible' => 'sometimes|boolean',
        ]);
        
        return DB::transaction(function () use ($request, $category) {
            $category->update($request->input('data.attributes'));
            
            return $this->okResponse();
        });
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Category $category)
    {
        if (!optional($category)->delete()) {
            throw (new ModelNotFoundException)->setModel(Category::class);
        }

        return redirect(route('categories.index'));
    }
}
