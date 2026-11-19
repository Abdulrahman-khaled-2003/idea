<x-layout>
    <x-form title="Update Profile">
        <form action="{{ route('profile.update') }}" method="POST" class="mt-10 space-y-4" enctype="multipart/form-data">
            @csrf
            @method('PATCH')
            <x-form.field label="Name" name="name" type="name" :value="$user->name" />
            <x-form.field label="Email" name="email" type="email" :value="$user->email" />
            <x-form.field label="New Password" name="new_password" type="password" />
            <div class="space-y-3">
                <label for="image" class="label mb-2">Profile Image</label>
                <img src="{{ asset('storage/'.$user->image_user_path) }}" class="rounded-xl">
                <input type="file" name="image" id="image" accept="image/*" class="cursor-pointer">
                <x-form.error name="image" />
            </div>
            <x-form.field label="Current Password" name="current_password" type="password" />
            <button data-test='update-profile' type='submit' class='btn mt-2 h-10 w-full'>Update Profile</button>
        </form>
    </x-form>
</x-layout>
