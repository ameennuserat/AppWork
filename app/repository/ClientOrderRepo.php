<?php

namespace App\repository;

use App\Interfaces\CurdRepoInterface;
use App\Models\ClientOrder;

class ClientOrderRepo implements CurdRepoInterface
{
    public function store($request)
    {
        $client_id = auth()->guard('client')->id();
        if(ClientOrder::where('client_id', $client_id)->where('post_id', $request->post_id)->exists()) {
            return response()->json([
                "message" => "duplicate order",
            ], 406);
        }
        $data = $request->all();
        $data['client_id'] = $client_id;
        $order = ClientOrder::create($data);
        return response()->json([
            "message" => "success",
        ]);
    }

}
