<?php

namespace App\Http\Controllers\Post;

use App\Exceptions\BadRequestException;
use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\PostComment;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CommentController extends Controller
{
    /**
     * Instantiate a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['permission:update-comment'], ['only' => ['update']]); 
        $this->middleware(['permission:create-comment'], ['only' => ['store']]); 
        $this->middleware(['permission:delete-comment'], ['only' => ['destroy']]); 
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Post $post)
    {
        $request->validate([
            'data' => 'required',
            'data.attributes' => 'required',

            'data.attributes.content' => 'required|string',
        ]);

        return DB::transaction(function () use ($request, $post) {
            $comment = PostComment::make($request->input('data.attributes'));
            $comment->post_id = $post->id;
            $comment->user_id = $request->user()->id;
            $comment->save();
            
            return response()->json([
                'data' => [
                    $comment->load('commenter')
                ],
            ], 200);
        });
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Post  $post
     * @param  int  $comment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Post $post, $comment)
    {
        $request->validate([
            'data' => 'required',
            'data.attributes' => 'required',

            'data.attributes.content' => 'required|string',
        ]);

        $comment = $post->comments()->findOrFail($comment);

        return DB::transaction(function () use ($request, $comment) {
            $comment->update($request->input('data.attributes'));
            
            return response()->json([
                'data' => [
                    $comment->load('commenter')
                ],
            ], 200);
        });
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Post $post, $comment)
    {
        $comment = $post->comments->find($comment);

        if (!optional($comment)->delete()) {
            throw (new ModelNotFoundException)->setModel(PostComment::class);
        }

        return response()->json([], 204);
    }
}
