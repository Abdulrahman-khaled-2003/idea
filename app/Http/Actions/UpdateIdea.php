<?php

namespace App\Http\Actions;

use App\Models\Idea;
use Illuminate\Support\Facades\DB;

class UpdateIdea
{
    public function handle(Idea $idea , array $attributes): void
    {
        $data = collect($attributes)->only('title', 'description', 'links', 'status')->toArray();
        if ($attributes['image'] ?? false) {
            $data['image_path'] = $attributes['image']->store('ideas', 'public');
        }
        DB::transaction(function () use ($data , $attributes, $idea) {
                $idea->update($data);
                $idea->steps()->delete();
                $idea->steps()->createMany($attributes['steps'] ?? []);
        });
    }
}
