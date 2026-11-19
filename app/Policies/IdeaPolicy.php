<?php

declare(strict_types=1);

namespace App\Policies;

use Amp\Http\Client\Response as ClientResponse;
use App\Models\Idea;
use App\Models\User;
use Illuminate\Auth\Access\Response as AccessResponse;
use Illuminate\Http\Response;

class IdeaPolicy
{
    public function workWith(User $user, Idea $idea)
    {
        return $idea->user->is($user);
    }
}
