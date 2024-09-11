<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Worker;
use App\Models\Post;
use DB;
use App\Models\WorkerReview;
use App\Services\WorkerService\UpdatingProfileServiceService;
use App\Http\Requests\UpdateWorkerProfile;

class WorkerProfileController extends Controller
{
    public function userProfile()
    {
        $workerid = auth()->guard('worker')->id();
        $worker = Worker::with('posts.review')->find($workerid)->makeHidden('status', 'created_at', 'updated_at', 'verification_token', 'verified_at');
        $reviews = WorkerReview::wherein("post_id", $worker->posts->pluck('id'))->get();
        $rate = round($reviews->sum('rate') / $reviews->count(), 1);
        return response()->json([
            "data" => ["worker" => $worker,"rate" => $rate]
        ]);
    }

    public function edit()
    {
        return response()->json(Worker::find(auth()->guard('worker')->id())->makeHidden('status', 'verification_token', 'verified_at'), 200);
    }

    public function update(UpdateWorkerProfile $request)
    {
        return (new UpdatingProfileServiceService())->update($request);
    }

    public function deletepost($id)
    {
        $post = DB::table('posts')
        ->where('id', $id)
        ->where('worker_id', auth()->guard('worker')->id())
        ->delete();
        if(!$post) {

            return response()->json(["message" => "not found post"], 400);
        }

        return response()->json(["message" => "deleted"], 400);
    }

    public function deleteAllPost()
    {
        $post = Post::where('worker_id', auth()->guard('worker')->id())->delete();
        if(!$post) {
            return response()->json(["message" => "not found post"], 400);
        }

        return response()->json(["message" => "deleted"], 400);

    }
}
