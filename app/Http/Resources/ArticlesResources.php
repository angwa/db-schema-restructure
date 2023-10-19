<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ArticlesResources extends JsonResource
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
            "article_no" => $this->article_no,
            "article" => $this->article,
            "price" => $this->articleProvider->price,
            "provider_info" => new ProvidersResource($this->articleProvider->provider),
            "created_at" => $this->created_at,
        ];
    }
}
