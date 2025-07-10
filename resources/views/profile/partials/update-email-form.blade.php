<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Update Email') }}
        </h2>
    </header>
    <form method="post" action="{{ route('profile.update.email') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <div>
            <x-input-label for="current_email" :value="__('Current Email')" />
            <x-text-input id="current_email" name="current_email" type="email" class="mt-1 block w-full" :value="old('current_email', $user->email)"
                required autocomplete="email" />
            <x-input-error class="mt-2" :messages="$errors->get('current_email')" />
        </div>

        <div>
            <x-input-label for="new_email" :value="__('New Email')" />
            <x-text-input id="new_email" name="new_email" type="email" class="mt-1 block w-full" :value="old('new_email')"
                required />
            <x-input-error class="mt-2" :messages="$errors->get('new_email')" />
        </div>

        <div>
            <x-input-label for="new_email_confirmation" :value="__('Confirm New Email')" />
            <x-text-input id="new_email_confirmation" name="new_email_confirmation" type="email"
                class="mt-1 block w-full" :value="old('new_email_confirmation')" required />
            <x-input-error class="mt-2" :messages="$errors->get('new_email_confirmation')" />
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>

            @if (session('status') === 'email-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600 dark:text-gray-400">{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</section>
