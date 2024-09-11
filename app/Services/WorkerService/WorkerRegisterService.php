<?php

namespace App\Services\WorkerService;

use Validator;
use App\Trait\ImageTrait;
use make;
use Hash;
use Mail;
use Illuminate\Support\Facades\DB;
use App\Mail\VerificationEmail;
use Illuminate\Services\WorkerService\Exception;

class WorkerRegisterService
{
    use ImageTrait;
    protected $model;

    public function __construct($model)
    {
        $this->model = $model;
    }

    public function validation($request)
    {
        $validator =  Validator::make($request->all(), $request->rules());
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        return $validator;
    }

    public function store_data($request)
    {
        $photo_name = $this->storimage($request->photo, 'workers');
        $worker = $this->model->create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'photo' => $photo_name,
            'location' => $request->location,
        ]);
        return $worker;
    }

    public function generatetoken($request)
    {
        $token = substr(md5(rand(0, 9).$request->email.time()), 0, 32);
        $worker = $this->model->whereEmail($request->email)->first();
        $worker->verification_token = $token;
        $worker->save();
        return $worker;

    }
    public function sendemail($worker)
    {
       // $order = Worker::whereVerification($request->orde);

        // Ship the order...

        Mail::to($worker->email)->send(new VerificationEmail($worker));


    }

    public function getstatus($email)
    {
        $worker = $this->model->whereEmail($email)->first();
        return $worker->status;
    }

    public function verified($email)
    {
        $worker = $this->model->whereEmail($email)->first();
        $verifited = $worker->verified;
        return $verifited;
    }

    public function register($request)
    {
        try {
            DB::beginTransaction();
            $data = $this->validation($request);
            $worker = $this->store_data($request);
            $token = $this->generatetoken($request);
           // $this->sendemail($worker);
            DB::commit();
            return response()->json(["message" => "account has been created check your email"]);
        } catch(Exception $e) {
            DB::rollBack();
            return $e->getmessage();
        }

    }
}
