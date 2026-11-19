<?php

namespace App\Http\Actions;

use App\Models\User;
use Illuminate\Container\Attributes\CurrentUser;
use Illuminate\Support\Facades\DB;

class CreateIdea
{
    public function __construct(#[CurrentUser] protected ?User $user = null) {}

    public function handle(array $attributes): void
    {
        $data = collect($attributes)->only('title', 'description', 'links', 'status')->toArray();
        if ($attributes['image'] ?? false) {
            $data['image_path'] = $attributes['image']->store('ideas', 'public');
        }

        DB::transaction(function () use ($data, $attributes) {
            $idea = $this->user->ideas()->create($data);
            $idea->steps()->createMany($attributes['steps'] ?? []);
        });
    }
}
