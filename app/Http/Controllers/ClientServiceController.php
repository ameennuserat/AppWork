<?php

namespace App\Http\Controllers;

use App\Http\Requests\ClientOrederRequest;
use App\interfaces\CurdRepoInterface;
use App\Models\ClientOrder;
use Illuminate\Http\Request;
use Validator;

class ClientServiceController extends Controller
{
    protected $crudrepo;
    public function __construct(CurdRepoInterface $crudrepo)
    {
        $this->crudrepo = $crudrepo;

    }
    public function addorder(ClientOrederRequest $request)
    {

        return $this->crudrepo->store($request);
    }

    public function orderpend()
    {
        $order = ClientOrder::with('client:id,name', 'post:id,content')->whereStatus('pending')->whereHas('post', function ($query) {
            $query->where('worker_id', auth()->guard('worker')->id());
        })->get();
        return response()->json([
            "orders" => $order
        ]);
    }

    public function update($id, Request $request)
    {
        Validator::make($request->all(), [
            'status' => 'required|string'
        ]);
        $order = ClientOrder::whereHas('post', function ($query) {
            $query->where('worker_id', auth()->guard('worker')->id());
        })->findOrFail($id);
        // if(!$order) {
        //     return response()->json([
        //         "message" => "sorry this order is not affiliated with you "
        //     ]);
        // }
        $order->setAttribute('status', $request->status)->save();
        return response()->json([
            "message" => "success"
        ]);
    }
}
