<x-layout>
    <div class="py-8 max-w-4xl mx-auto">
        <div class="flex justify-between mb-4">
            <a href="{{ route('idea.index') }}"
                class="flex items-center gap-x-2 text-sm font-medium"><x-icons.arrow-back />
                Back to ideas
            </a>
            <div x-data class="gap-x-3 flex items-center">
                <button class="btn btn-outlined" @click="$dispatch('open-modal' , 'edit-idea')" data-test="edit-idea"><x-icons.external />Edit idea</button>
                <form method="POST" action="{{ route('idea.destroy', $idea) }}">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-outlined text-red-500">Delete</button>
                </form>
            </div>
        </div>
        <h1 class="font-bold text-4xl">{{ $idea->title }}</h1>
        <div class="gap-x-3 flex items-center mt-2">
            <x-idea.status-label :status="$idea->status->value ">
                {{ $idea->status->label() }}
            </x-idea.status-label>
            <div class=" text-sm mt-2 text-muted-foreground">{{ $idea->created_at->diffForHumans() }}</div>
        </div>
        @if($idea->description)
        <x-card class="mt-6">
            <div class="text-foreground max-w-none cursor-pointer">{{ $idea->description }}</div>
        </x-card>
        @endif
        <div class="mt-8 space-y-6">
                @if($idea->image_path)
                <div class="rounded-lg overflow-hidden">
                <img src="{{ asset('storage/' . $idea->image_path) }}" alt="" class="w-full h-auto object-cover">
                </div>
                @endif
        </div>

        @if ($idea->steps->count())
            <div>
                <h3 class="font-bold text-xl mt-6">Actionable Steps</h3>
                <div class="mt-4 space-y-2">
                    @foreach ($idea->steps as $step)
                        <x-card>
                            <div class="flex items-cetner gap-x-3">
                                <form method="POST" action="{{ route('step.update', $step) }}">
                                    @csrf
                                    @method('PATCH')
                                    <button
                                        class="size-5 flex items-cetner justify-center rounded-lg text-primary-foreground
                            {{ $step->completed ? 'bg-primary' : 'border border-primary' }}">&check;</button>
                                </form>
                                <span
                                    class="{{ $step->completed ? 'line-through text-muted-foreground' : '' }}">{{ $step->description }}</span>
                            </div>
                        </x-card>
                    @endforeach
                </div>
            </div>
        @endif
        @if ($idea->links->count())
            <div>
                <h3 class="font-bold text-xl mt-6">Links</h3>
                <div class="mt-4 space-y-2">
                    @foreach ($idea->links as $link)
                        <x-card target="_blank" :href="$link">
                            <p class="text-primary">{{ $link }}</p>
                        </x-card>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
    <x-idea.idea-modal :idea="$idea" />
</x-layout>
