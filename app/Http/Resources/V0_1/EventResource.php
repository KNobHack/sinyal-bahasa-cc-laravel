<?php

namespace App\Http\Resources\V0_1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EventResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'host' => new UserResource($this->whenLoaded('host')),
            'participant' => UserResource::collection($this->whenLoaded('participants')),

            'name' => $this->name,
            'thumbnail_url' => $this->thumbnail_url,
            'description' => $this->description,
            'date' => $this->date,
            'start_time' => $this->start_time,
            'end_time' => $this->end_time,
            'lat' => $this->lat,
            'lon' => $this->lon,
            'max_participant' => $this->max_participant,
        ];
    }
}
