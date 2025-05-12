<?php

namespace App\Http\Resources\Tienda;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class HomeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return
            [
                "id" => $this->resource->id,
                "name_cottage" => $this->resource->name_cottage,
                "description" => $this->resource->description,
                "main_image" => $this->resource->main_image ? env("APP_URL") . "/storage/" . $this->resource->main_image : NULL,
                "gallery_images" => $this->gallery_images
                    ? array_map(fn($img) => env("APP_URL") . "/storage/" . $img, $this->gallery_images)
                    : [],

            ];
    }
}
