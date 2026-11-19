<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Actions\CreateIdea;
use App\Http\Actions\UpdateIdea;
use App\Http\Requests\StoreIdeaRequest;
use App\Http\Requests\UpdateIdeaRequest;
use App\IdeaStatus;
use App\Models\Idea;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class IdeaController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->status;
        if (! in_array($status, IdeaStatus::values())) {
            $status = null;
        }
        $ideas = Auth::user()->ideas()
            ->when($status, fn ($query, $status) => $query->where('status', $status))
            ->latest()
            ->get();

        return view('idea.index', [
            'ideas' => $ideas,
            'statusCount' => Idea::statusCount(Auth::user()),
        ]);
    }


    public function store(StoreIdeaRequest $request, CreateIdea $action)
    {
        $action->handle($request->safe()->all());

        return to_route('idea.index')->with('success', 'idea created');
    }

    public function show(Idea $idea)
    {
        Gate::authorize('workWith', $idea);

        return view('idea.show', [
            'idea' => $idea,
        ]);
    }

    public function edit(Idea $idea): void
    {
        Gate::authorize('workWith', $idea);
    }

    public function update(UpdateIdeaRequest $request, Idea $idea, UpdateIdea $action)
    {
        Gate::authorize('workWith', $idea);
        $action->handle($idea, $request->safe()->all());

        return back()->with('success', 'idea updated successful');
    }

    public function destroy(Idea $idea)
    {
        Gate::authorize('workWith', $idea);
        $idea->delete();

        return to_route('idea.index');
    }
}
