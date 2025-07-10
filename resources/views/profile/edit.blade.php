<x-app-layout>
    <div class="py-6 flex justify-center mt-14">
        <div class="w-full max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-4">
            <div class="p-3 sm:p-6 bg-white dark:bg-gray-500 shadow sm:rounded-lg">
                <div class="max-w-lg mx-auto">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <!-- @if(auth()->user()->role =='admin')
            <div class="p-3 sm:p-6 bg-white dark:bg-gray-500 shadow sm:rounded-lg">
                <div class="max-w-lg mx-auto">
                    @include('profile.partials.update-profile-image')
                </div>
            </div>
            @endif -->

            <div class="p-3 sm:p-6 bg-white dark:bg-gray-500 shadow sm:rounded-lg">
                <div class="max-w-lg mx-auto">
                    @include('profile.partials.update-email-form')
                </div>
            </div>

            <div class="p-3 sm:p-6 bg-white dark:bg-gray-500 shadow sm:rounded-lg">
                <div class="max-w-lg mx-auto">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <div class="p-3 sm:p-6 bg-white dark:bg-gray-500 shadow sm:rounded-lg">
                <div class="max-w-lg mx-auto">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

{{-- <x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit Profile') }}
</h2>
</x-slot>

<div class="py-12">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg">
            <div class="p-6 space-y-6">
                <form id="profile-form" method="post" action="{{ route('profile.update') }}" class="space-y-6">
                    @csrf

                    <!-- Profile Information -->
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                            {{ __('Profile Information') }}</h3>
                        <div class="mt-4 grid grid-cols-1 gap-y-6 sm:grid-cols-2 sm:gap-x-4">
                            <div>
                                <x-input-label for="first_name" :value="__('First Name')" />
                                <x-text-input id="first_name" name="first_name" type="text" class="mt-1 block w-full"
                                    :value="old('first_name', $user->first_name)" required autofocus />
                                <x-input-error :messages="$errors->get('first_name')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="last_name" :value="__('Last Name')" />
                                <x-text-input id="last_name" name="last_name" type="text" class="mt-1 block w-full"
                                    :value="old('last_name', $user->last_name)" required />
                                <x-input-error :messages="$errors->get('last_name')" class="mt-2" />
                            </div>
                        </div>
                    </div>

                    <!-- Update Password -->
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">{{ __('Update Password') }}
                        </h3>
                        <div class="mt-4 space-y-6">
                            <div>
                                <x-input-label for="current_password" :value="__('Current Password')" />
                                <x-text-input id="current_password" name="current_password" type="password"
                                    class="mt-1 block w-full" autocomplete="current-password" />
                                <x-input-error :messages="$errors->updatePassword->get('current_password')"
                                    class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="password" :value="__('New Password')" />
                                <x-text-input id="password" name="password" type="password" class="mt-1 block w-full"
                                    autocomplete="new-password" />
                                <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
                                <x-text-input id="password_confirmation" name="password_confirmation" type="password"
                                    class="mt-1 block w-full" autocomplete="new-password" />
                                <x-input-error :messages="$errors->updatePassword->get('password_confirmation')"
                                    class="mt-2" />
                            </div>
                        </div>
                    </div>

                    <!-- Update Email -->
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">{{ __('Update Email') }}
                        </h3>
                        <div class="mt-4 space-y-6">
                            <div>
                                <x-input-label for="email" :value="__('New Email')" />
                                <x-text-input id="email" name="email" type="email" class="mt-1 block w-full"
                                    :value="old('email', $user->email)" required />
                                <x-input-error :messages="$errors->get('email')" class="mt-2" />
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center justify-end mt-6">
                        <x-primary-button>{{ __('Save Changes') }}</x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.getElementById('profile-form').addEventListener('submit', function(e) {
    e.preventDefault();

    Swal.fire({
        title: 'Updating Profile',
        text: 'Please wait...',
        allowOutsideClick: false,
        showConfirmButton: false,
        willOpen: () => {
            Swal.showLoading();
        }
    });

    fetch(this.action, {
            method: 'POST',
            body: new FormData(this),
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                    'content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Profile Updated',
                    text: 'Your profile has been successfully updated.'
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Update Failed',
                    text: 'There was an error updating your profile. Please try again.'
                });
            }
        })
        .catch(error => {
            Swal.fire({
                icon: 'error',
                title: 'Update Failed',
                text: 'There was an error updating your profile. Please try again.'
            });
        });
});
</script>
@endpush
</x-app-layout> --}}