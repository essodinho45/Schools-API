<div class="p-6">

    <div class="flex items-center justify-between px-4 py-3 text-right sm:px-6">
        <label for="no_token" class="flex items-center">
            <x-jet-checkbox id="no_token" name="no_token" wire:model="no_token" />
            <span
                class="@if (App::isLocale('ar')) mr-2 @else ml-2 @endif text-sm text-gray-600">{{ __('Show only users without login token.') }}</span>
        </label>

        <x-jet-button wire:click="createShowModal">
            {{ __('Create') }}
        </x-jet-button>
    </div>
    <x-jet-input id="search" type="text" class="mt-1 block w-full" placeholder="{{ __('Search') }}"
        wire:model="searchTerm" />
    <table class="w-full divide-y divide-gray-200">
        <thead class="bg-white border-b">
            <tr>
                <th
                    class="px-6 py-3 bg-gray-50 @if (\App::isLocale('ar')) text-right @else text-left @endif text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                    #</th>
                <th
                    class="px-6 py-3 bg-gray-50 @if (\App::isLocale('ar')) text-right @else text-left @endif text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                    {{ __('Name') }}</th>
                <th
                    class="px-6 py-3 bg-gray-50 @if (\App::isLocale('ar')) text-right @else text-left @endif text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                    {{ __('Email') }}</th>
                <th
                    class="px-6 py-3 bg-gray-50 @if (\App::isLocale('ar')) text-right @else text-left @endif text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                    {{ __('Type') }}</th>

                <th
                    class="px-6 py-3 bg-gray-50 @if (\App::isLocale('ar')) text-left @else text-right @endif text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                </th>
            </tr>
        </thead>
        <tbody class="devide-y devide-gray-200">
            @if ($data->count())
                @foreach ($data as $user)
                    <tr
                        @if ($user->freezed) class="bg-gray-200"
                        @elseif ($user->is_admin) class="bg-red-200"
                        @elseif ($user->school_code) class="bg-teal-200"
                        @else class="bg-white" @endif>
                        <td class="px-6 py-4 text-sm whitespace-no-wrap">{{ $user->id }}</td>
                        <td class="px-6 py-4 text-sm whitespace-no-wrap">{{ $user->name }}</td>
                        <td class="px-6 py-4 text-sm whitespace-no-wrap">{{ $user->email }}</td>
                        <td class="px-6 py-4 text-sm whitespace-no-wrap">
                            @if ($user->is_admin)
                                {{ __('Admin') }}
                            @elseif ($user->school_code)
                                {{ __('School') }}
                            @else
                                {{ __('Mobile') }}
                            @endif
                            @if ($user->freezed)
                                - {{ __('freezed') }}
                            @endif
                        </td>
                        <td class="px-6 py-4 text-sm text-right">
                            <button class="btn btn-blue" wire:click="freeze({{ $user->id }})">
                                @if (!$user->freezed)
                                    {{ __('Freeze') }}
                                @else
                                    {{ __('Unfreeze') }}
                                @endif
                            </button>
                            <x-jet-button wire:click="passwordShowModal({{ $user->id }})">
                                {{ __('Change Password') }}
                            </x-jet-button>
                        </td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td class="px-6 py-4 text-sm text-center" colspan="6">
                        {{ __('No data to show.') }}
                    </td>
                </tr>
            @endif
        </tbody>
    </table>
    {{ $data->links('vendor.livewire.tailwind') }}
    <x-jet-dialog-modal wire:model="modalFormVisible">
        <x-slot name="title">
            {{ __('Save User') }}
        </x-slot>

        <x-slot name="content">
            <div class="mt-4">
                <x-jet-label for="name" value="{{ __('Name') }}" />
                <x-jet-input id="name" type="text" class="mt-1 block w-full"
                    wire:model.debounce.800ms="name" />
                @error('name')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>
            <div class="mt-4">
                <x-jet-label for="email" value="{{ __('Email') }}" />
                <x-jet-input id="email" type="email" class="mt-1 block w-full"
                    wire:model.debounce.800ms="email" />
                @error('email')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>
            <div class="mt-4">
                <x-jet-label for="password" value="{{ __('Password') }}" />
                <x-jet-input id="password" type="password" class="mt-1 block w-full"
                    wire:model.debounce.800ms="password" />
                @error('password')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>
            <div class="mt-4">
                <x-jet-label for="password_confirmation" value="{{ __('Password Confirmation') }}" />
                <x-jet-input id="password_confirmation" type="password" class="mt-1 block w-full"
                    wire:model.debounce.800ms="password_confirmation" />
                @error('password_confirmation')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>
            <div class="block mt-4">
                <label for="is_admin" class="inline-flex items-center">
                    <input id="is_admin" type="checkbox"
                        class="rounded border-gray-300 text-indigo-800 shadow-sm focus:ring-indigo-800" name="is_admin"
                        wire:model="is_admin">
                    <span
                        class="@if (App::isLocale('ar')) mr-2 @else ml-2 @endif text-sm text-gray-600">{{ __('Admin') }}</span>
                </label>
            </div>
            <div class="mt-4">
                <x-jet-label for="school" value="{{ __('School') }}" />
                <select name="school" wire:model="school"
                    class="rounded border-gray-300 shadow-sm p-2 bg-white w-full focus:ring-indigo-800 focus:border-indigo-800"
                    @if ($is_admin) disabled @endif>
                    <option value=''>{{ __('Choose a school') }}</option>
                    @foreach ($schools as $school)
                        <option value={{ $school->code }}>{{ $school->name }}</option>
                    @endforeach
                </select>
            </div>
        </x-slot>

        <x-slot name="footer">
            <x-jet-secondary-button wire:click="$toggle('modalFormVisible')" wire:loading.attr="disabled">
                {{ __('Cancel') }}
            </x-jet-secondary-button>

            <x-jet-button class="@if (\App::isLocale('ar')) mr-3 @else ml-3 @endif" wire:click="create"
                wire:loading.attr="disabled">
                {{ __('Save') }}
            </x-jet-button>
        </x-slot>
    </x-jet-dialog-modal>

    <x-jet-dialog-modal wire:model="passwordFormVisible">
        <x-slot name="title">
            {{ __('Change Password') }}
        </x-slot>

        <x-slot name="content">
            <div class="mt-4">
                <x-jet-label for="change_password" value="{{ __('Password') }}" />
                <x-jet-input id="change_password" type="password" class="mt-1 block w-full"
                    wire:model.debounce.800ms="change_password" />
                @error('change_password')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>
            <div class="mt-4">
                <x-jet-label for="change_password_confirmation" value="{{ __('Password Confirmation') }}" />
                <x-jet-input id="change_password_confirmation" type="password" class="mt-1 block w-full"
                    wire:model.debounce.800ms="change_password_confirmation" />
                @error('change_password_confirmation')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>
        </x-slot>

        <x-slot name="footer">
            <x-jet-secondary-button wire:click="$toggle('passwordFormVisible')" wire:loading.attr="disabled">
                {{ __('Cancel') }}
            </x-jet-secondary-button>

            <x-jet-button class="@if (\App::isLocale('ar')) mr-3 @else ml-3 @endif"
                wire:click="changePassword" wire:loading.attr="disabled">
                {{ __('Save') }}
            </x-jet-button>
        </x-slot>
    </x-jet-dialog-modal>
</div>
