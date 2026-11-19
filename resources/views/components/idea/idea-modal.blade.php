@props([
    'idea' => new App\Models\Idea(),
])

<x-modal name="{{ $idea->exists ? 'edit-idea' : 'create-idea' }}" title="{{ $idea->exists ? 'Edit idea' : 'New idea' }}"
    class="max-w-3xl w-full">
    <form x-data="{
        status: @js(old('status', $idea->status->value)),
        newLink: '',
        links: @js(old('links', $idea->links ?? [])),
        newStep: '',
        steps: @js(old('steps', $idea->steps->map->only(['id' , 'description' , 'completed'])))
    }" action="{{ $idea->exists ? route('idea.update', $idea) : route('idea.store') }}"
        method="POST" enctype="multipart/form-data" class="max-h-[80vh] overflow-y-auto pr-2">
        @csrf
        @if ($idea->exists)
            @method('PATCH')
        @endif
        <div class="space-y-3 mt-4">
            <x-form.field label="Title" name="title" placeholder='Enter you idea' :value="$idea->title ?? ''" autofocus />
            <div class="space-y-3">
                <label for="status" name="status" class='label'>Status</label>
                <div class="flex gap-x-3">
                    @foreach (App\IdeaStatus::cases() as $status)
                        <button type="button" @click="status = @js($status->value)" class='btn flex-1 h-10'
                            data-test="button-status-{{ $status->value }}"
                            :class="{ 'btn-outlined': status !== @js($status->value) }">{{ $status->label() }}
                        </button>
                    @endforeach
                    <input type="hidden" name="status" :value="status">
                    <x-form.error name="status" />
                </div>
            </div>
            <x-form.field label="Description" name="description" type='textarea' :value="$idea->description"
                placeholder='Your Description.' />
        </div>

        <div class="space-y-2 mb-2">
            <label for="image" class="label">Image</label>
            @if ($idea->image_path)
                <div class="rounded-lg overflow-hidden space-y-2">
                    <img src="{{ asset('storage/' . $idea->image_path) }}" alt=""
                        class="w-full h-auto object-cover">
                    <button form="remove-image" class="btn btn-outlined w-full">Remove Image</button>
                </div>
            @endif
            <input type="file" accept="image/*" name="image" class="cursor-pointer">
            <x-form.error name='image' />
        </div>

        <div>
            <fieldset class="space-y-3">
                <legend class="label">Steps</legend>
                <template x-for="(step , index) in steps" >
                    <div class="flex gap-x-2 items-center">
                        <input :name="`steps[${index}][description]`" x-model="step.description" class="input">
                        <input type="hidden" :name="`steps[${index}][completed]`" x-model="step.completed ? '1' : '0' " class="input">
                        <button type='button' aria-label="Remove Step"
                            @click="steps.splice(index , 1)" class="form-muted-icon">
                            <x-icons.close />
                        </button>
                    </div>
                </template>
                <div class="flex gap-x-2 items-center space-y-3">
                    <input x-model="newStep" data-test="new-step" type="text" id="new-step" class = "input flex-1">
                    <button type="button" data-test="button-create-step"
                        @click="steps.push({ description: newStep.trim() , completed: false }); newStep='' " :disabled="newStep.trim().length === 0"
                        aria-label="Add a new step">
                        <x-icons.plus class="form-muted-icon" />
                    </button>
                </div>
            </fieldset>
        </div>

        <div>
            <fieldset class="space-y-3">
                <legend class="label">Links</legend>
                <template x-for="(link , index) in links">
                    <div class="flex gap-x-2 items-center">
                        <input name="links[]" x-model="link" class="input">
                        <button type='button' aria-label="Remove link" @click="links.splice(index , 1)"
                            class="form-muted-icon">
                            <x-icons.close />
                        </button>
                    </div>
                </template>
                <div class="flex gap-x-2 items-center space-y-3">
                    <input x-model="newLink" data-test="new-link" type="url" id="new-link"
                        placeholder="http:/example.com" autocomplete = 'url' class = "input flex-1"
                        spellcheck = "false">
                    <button type="button" data-test="button-create-link"
                        @click="links.push(newLink.trim()); newLink='' " :disabled="newLink.trim().length === 0"
                        aria-label="Add a new link">
                        <x-icons.plus class="form-muted-icon" />
                    </button>
                </div>
            </fieldset>
        </div>
        <div class="flex justify-end mt-4 space-x-3">
            <button type="button" @click="$dispatch('close-modal')">Cancel</button>
            <button type="submit" class='btn'
                data-test="submit-idea">{{ $idea->exists ? 'Update' : 'Create' }}</button>
        </div>
    </form>
    @if ($idea->image_path)
        <form action="{{ route('image.destroy', $idea) }}" id='remove-image' method="POST">
            @csrf
            @method('DELETE')
        </form>
    @endif
</x-modal>
