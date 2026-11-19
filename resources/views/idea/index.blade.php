<x-layout>
    <div>
        <header class="py-8 md:py-12">
            <h1 class="text-3xl font-bold">Ideas</h1>

            <p class="mt-2 text-sm text-muted-foreground">
                Capture your thoughts. Make a plan.
            </p>
            <x-card data-test="create-idea" x-data @click="$dispatch('open-modal', 'create-idea')" tag="button"
                class="w-full text-left h-21 mt-10 cursor-pointer">
                <div class="flex space-x-2 items-center">
                    <x-icons.idea />
                    <span>What's the idea?</span>
                </div>
            </x-card>
        </header>
        <div>
            <a href="/ideas" class="btn {{ request('status') ? 'btn-outlined' : '' }}">All<span
                    class="text-xs pl-3">{{ $statusCount->get('all') }}</span>
            </a>

            @foreach (App\IdeaStatus::cases() as $status)
                <a href="?status={{ $status->value }}"
                    class="btn {{ request('status') === $status->value ? '' : 'btn-outlined' }}">
                    {{ $status->label() }}
                    <span class="text-xs pl-3">{{ $statusCount->get($status->value) }}</span>
                </a>
            @endforeach
        </div>
        <div class="mt-10 text-muted-foreground">
            <div class="grid gap-6 md:grid-cols-2">
                @forelse ($ideas as $idea)
                    <x-card href="{{ route('idea.show', $idea) }}">
                        @if ($idea->image_path)
                            <div class="mb-4 -mx-4 -mt-4 rounded-t-lg overflow-hidden">
                                <img src="{{ asset('storage/' . $idea->image_path) }}" alt=""
                                    class="w-full h-48 object-cover">
                            </div>
                        @endif
                        <h3 class="text-foreground text-lg">{{ $idea->title }}</h3>
                        <x-idea.status-label class="inline-block rounded-full border px-2 py-1 text-xs font-medium"
                            status="{{ $idea->status->value }}">
                            {{ $idea->status->label() }}
                        </x-idea.status-label>
                        <div class="mt-5 line-clamp-3">{{ $idea->description }}</div>
                        <div class="mt-5">{{ $idea->created_at->diffForHumans() }}</div>
                    </x-card>
                @empty
                    <x-card>
                        <p> No ideas at this time. </p>
                    </x-card>
                @endforelse
            </div>
        </div>
    </div>
    <x-idea.idea-modal />
</x-layout>
