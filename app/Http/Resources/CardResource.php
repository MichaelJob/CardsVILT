<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\URL;

class CardResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     * Resource for single contact shown in API
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
            return [
                'id' => $this->id,
                'main_subject' => $this->main_subject,
                'subject' => $this->subject,
                'front' => $this->front,
                'back' => $this->back,
                'front_photo' => $this->front_photo_path ? URL::route('image', ['path' => $this->front_photo_path, 'h' => 240]) : null,
                'back_photo' => $this->back_photo_path ? URL::route('image', ['path' => $this->back_photo_path, 'h' => 240]) : null,
            ];
    }
}
