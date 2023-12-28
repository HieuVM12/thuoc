<?php

namespace App\Http\Resources;

use App\Models\HashTag as ModelsHashTag;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class Hashtag extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        $nameHashtag = ModelsHashTag::findOrFail($this['id_tag'])->name;
        return [
            'key' => 'hashtag',
            'value' => $this['id_tag'],
            'name' => $nameHashtag??null,
        ];
    }
}
