<?php

namespace App\Modules\Service\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ServiceResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'specialty' => $this->whenLoaded('specialty', function () {
                return [
                    'id' => $this->specialty?->id,
                    'name' => $this->specialty?->name,
                ];
            }),
            'specialty_id' => $this->specialty_id,
            'price' => $this->price,
            'created_at' => optional($this->created_at)->format('Y-m-d H:i'),
            'updated_at' => optional($this->updated_at)->format('Y-m-d H:i'),
        ];
    }
}
