<?php

namespace App\Services\WorkerService\WorkerLoginService;

use Illuminate\Http\Requests\LoginRequest;
use Validator;

class WorkerLoginService
{
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

    public function checkdata($data)
    {
        if (! $token = auth()->guard('worker')->attempt($data->validated())) {
            return response()->json(['error' => 'Invalid Data'], 401);
        }
        return $token;
    }

    public function getstatus($email)
    {
        $worker = $this->model->whereEmail($email)->first();
        return $worker->status;
    }

    protected function createNewToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 120,
            'user' => auth()->guard('worker')->user()
        ]);
    }

    public function verified($email)
    {
        $worker = $this->model->whereEmail($email)->first();
        $verifited = $worker->verified_at;
        return $verifited;
    }

    public function login($request)
    {
        $data = $this->validation($request);
        $token = $this->checkdata($data);
        $status = $this->getstatus($request->email);
        $verifited = $this->verified($request->email);
        if($verifited == null){
            return response()->json(["message"=>"your account is not verfited"]);
        }else if($status == 0){
            return response()->json(["message" => "your account is pending"], 422);
        }
        return $this->createNewToken($token);
    }
}
