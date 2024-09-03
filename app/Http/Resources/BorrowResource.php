<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BorrowResource extends JsonResource
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
            'user' => new UserResource($this->whenLoaded('user')),
            'book' => new BookResource($this->whenLoaded('book')),
            'borrowed_at' => $this->borrowed_at->toDateTimeString(),
            'due_date' => $this->due_date->toDateTimeString(),
            'returned_at' => $this->returned_at ? $this->returned_at->toDateTimeString() : null,
        ];
    }
}
