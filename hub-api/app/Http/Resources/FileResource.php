<?php

namespace App\Http\Resources;
 
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FileResource extends JsonResource
{ 

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "filename" => $this->filename,
            "user_id" => $this->user_id,
            "category_id" => $this->category_id,
            "path"=> $this->path

        ];
     //   return parent::toArray($request);
    }
}