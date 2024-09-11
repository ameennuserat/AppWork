<?php

namespace App\Services\WorkerService;

use App\Models\Worker;
use App\Models\Post;
use App\Trait\ImageTrait;
use Illuminate\Http\UploadedFile;

class UpdatingProfileServiceService
{
    use ImageTrait;
    protected $model;
    public function __construct()
    {
        $this->model = Worker::find(auth()->guard('worker')->id());

    }

    public function updatepassowrd($data)
    {
        if(request()->has('password')) {
            $data['password'] = bcrypt(request()->password);
            return $data;
        }
        return $data;
    }

    function updatephoto($data){
        if(request()->file('photo')){
            $image = $this->storimage(request()->photo,'workers');
            $data['photo'] = $image;
       }
       return $data;
    }

    public function update($request)
    {
        $data = $request->all();
        $data = $this->updatepassowrd($data);
        $data = $this->updatephoto($data);
        $this->model->update($data);
        return response()->json(["message" => "updated"], 200);
    }


}
