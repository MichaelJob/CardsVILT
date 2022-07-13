<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class CardCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     * Resource for collection of cards shown in API
     * each card uses CardResource
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        //$acc = $request->user()->account_id;
        return [
            'data' => $this->collection->filter(
                function ($value) { //use ($acc) 
                    return $value->deleted_at == null;
                }
            )->values()
        ];
    }
}
