<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    <div>
        <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
            @if (Laravel\Fortify\Features::canUpdateProfileInformation())
                @livewire('profile.update-profile-information-form')

                <x-jet-section-border />
            @endif

            @if (Laravel\Fortify\Features::enabled(Laravel\Fortify\Features::updatePasswords()))
                <div class="mt-10 sm:mt-0">
                    @livewire('profile.update-password-form')
                </div>

                <x-jet-section-border />
            @endif

            @if (Laravel\Fortify\Features::canManageTwoFactorAuthentication())
                <div class="mt-10 sm:mt-0">
                    @livewire('profile.two-factor-authentication-form')
                </div>

                <x-jet-section-border />
            @endif

            {{-- <div class="mt-10 sm:mt-0">
                @livewire('profile.logout-other-browser-sessions-form')
            </div> --}}

            @if (Laravel\Jetstream\Jetstream::hasAccountDeletionFeatures())
                <x-jet-section-border />

                <div class="mt-10 sm:mt-0">
                    @livewire('profile.delete-user-form')
                </div>
            @endif

            <div class="mt-10 sm:mt-0">
                <x-jet-action-section>
                    <x-slot name="title">
                        {{ __('Select Language') }}
                    </x-slot>

                    <x-slot name="description">
                        {{ __('Select website language.') }}
                    </x-slot>

                    <x-slot name="content">
                        <div class="max-w-xl text-sm text-gray-600">
                            {{ __('Choose your preffered language to use our dashboard website.') }}
                        </div>
                        <div class="col-span-6 sm:col-span-4">
                            <x-jet-label for="lang" value="{{ __('Language') }}" />
                            <select name="lang" id="lang"
                                class="rounded border-gray-300 shadow-sm p-2 bg-white w-full focus:ring-indigo-800 focus:border-indigo-800">
                                <option value="ar">العربية</option>
                                <option value="en">English</option>
                            </select>
                        </div>
                        <div class="mt-5">
                            <x-jet-button
                                onclick="
                                var e = document.getElementById('lang');
                                var value = e.value;
                                window.location.href = '/lang/'+value;"
                                wire:loading.attr="disabled">
                                {{ __('Save') }}
                            </x-jet-button>
                        </div>
                    </x-slot>
                </x-jet-action-section>
            </div>
        </div>
    </div>
</x-app-layout>
