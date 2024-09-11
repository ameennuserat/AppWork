<?php

namespace App\Http\Controllers\AdminDashboard;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
use App\Http\Requests\PostStatusRequest;
use Illuminate\Support\Facades\Notification;
use App\Notifications\AdminPost;

class PostStatusController extends Controller
{
    public function changestatus(PostStatusRequest $request)
    {

        $post = Post::find($request->post_id);
        $post->status = $request->status;
        $post->rejected_reason = $request->rejected_reason;
        $post->save();
        Notification::send($post->worker, new AdminPost($post->worker,$post));
        return response()->json(["message" => "successfuly"]);
    }
}
