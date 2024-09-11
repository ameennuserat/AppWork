<?php

namespace app\Services\WorkerService;

use App\Notifications\AdminPost;
use App\Models\Post;
use App\Models\Admin;
use App\Models\Post_photos;
use App\Trait\ImageTrait;
use DB;
use Illuminate\Support\Facades\Notification;
use Illuminate\Services\WorkerService\Exception;
use Illuminate\Database\Console\Migrations\RollbackCommand;
use Validator;

class StoringPostService
{
    use ImageTrait;
    public function __construct()
    {

    }

    public function validate($request)
    {
        $validate = Validator::make($request->all(), $request->rules());
        if($validate->fails()) {
            return response()->json($validate->errors, 422);
        }
        return $validate;
    }

    public function discount($price)
    {
        $priceafterdiscount = $price - ($price * 0.05);
        return $priceafterdiscount;
    }

    public function storepost($request)
    {
        $post = Post::create([
            'content' => $request->content,
            'price' => $this->discount($request->price),
            'worker_id' => auth()->guard('worker')->id()
        ]);
        return $post;
    }

    public function storephoto($request, $post_id)
    {

        foreach($request->file('photos') as $photo) {
            $photo_name = $this->storimage($photo, 'posts');
            Post_photos::create([
                'photo' => $photo_name,
                'post_id' => $post_id
            ]);
        }
    }

    public function notification($worker, $post)
    {
        $admins = Admin::get();
        Notification::send($admins, new AdminPost($worker, $post));
    }

    public function store($request)
    {
        try {
            DB::beginTransaction();
            $this->validate($request);
            $post = $this->storepost($request);
            if($request->hasfile('photos')) {
                $this->storephoto($request, $post->id);
            }
            $this->notification(auth()->guard('worker')->user(), $post);
            DB::commit();
            return response()->json(["message" => "your post has been created , your price after discount {$post->price}"]);

        } catch(Exception $e) {
            DB::rollback();
            // return $e->getmessage();
        }
    }

}
