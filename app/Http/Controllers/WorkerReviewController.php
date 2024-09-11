<?php

namespace App\Http\Controllers;

use App\Http\Requests\WorkerReviewRequest;
use Illuminate\Http\Request;
use App\Models\WorkerReview;
use App\Http\Resources\WorkerReviewResource;

class WorkerReviewController extends Controller
{
    public function store(WorkerReviewRequest $request)
    {
        $data = $request->all();
        $data['client_id'] = auth()->guard('client')->id();
        $re = WorkerReview::create($data);
        return response()->json([
            "message" => "success"
        ]);

    }

    public function postrate($id)
    {
        $reviews = WorkerReview::wherePostId($id);
        $avareg = $reviews->sum('rate') / $reviews->count();
        return response()->json([
            "total_rate" => round($avareg, 1),
            "data" => WorkerReviewResource::collection($reviews->get())
        ]);
    }
}
