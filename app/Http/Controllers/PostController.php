<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoringPostRequest;
//use Illuminate\Http\Request;
use App\Services\WorkerService\StoringPostService;
use App\Models\Post;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;
use Illuminate\Database\Eloquent\Builder;
use App\filters\PostFilter;
use DB;


class PostController extends Controller
{
    public function storepost(StoringPostRequest $request)
    {
        return (new StoringPostService())->store($request);
    }

    public function approved()
    {
        $posts = QueryBuilder::for(Post::class)
    ->allowedFilters((new PostFilter())->filter())
    ->with('worker:id,name')
    ->where('status', 'approved')
    ->get();
        return response()->json([
            "message" => $posts
        ], 200);
    }

    public function allpost()
    {
        $post = Post::all();
        return response()->json([
            "message" => $post
        ], 200);
    }

    public function getpost($id)
    {
        $post = Post::find($id)->get()->makeHidden('status');
        return response()->json($post, 200);
    }
}
