<x-layout>
    <x-form title="Register an account" description="Start tracking your ideas today.">
        <form action="{{ route('register.store') }}" method="POST" enctype="multipart/form-data" class="mt-10 space-y-4">
            @csrf

            <x-form.field label="Name" name="name" />
            <x-form.field label="Email" name="email" type="email" />
            <x-form.field label="Password" name="password" type="password" />
            <x-form.field label="Confirm Password" name="password_confirmation" type="password" />

            <div>
                <label for="image" class="label mb-2">Profile Image</label>
                <input type="file" name="image" id="image" accept="image/*" class="cursor-pointer" >
                <x-form.error name="image" />
            </div>

            <button data-test="submit" type="submit" class="btn mt-2 h-10 w-full">
                Create Account
            </button>
        </form>
    </x-form>
</x-layout>
