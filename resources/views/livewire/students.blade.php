<div class="p-6">
    <x-jet-input id="search" type="text" class="mt-1 block w-full" placeholder="{{ __('Search') }}"
        wire:model="searchTerm" />
    <table class="w-full divide-y divide-gray-200">
        <thead class="bg-white border-b">
            <tr>
                <th
                    class="px-6 py-3 bg-gray-50 @if (\App::isLocale('ar')) text-right @else text-left @endif text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                    {{ __('Code') }}</th>
                <th
                    class="px-6 py-3 bg-gray-50 @if (\App::isLocale('ar')) text-right @else text-left @endif text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                    {{ __('Name') }}</th>
                <th
                    class="px-6 py-3 bg-gray-50 @if (\App::isLocale('ar')) text-right @else text-left @endif text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                    {{ __('Gender') }}</th>
                <th
                    class="px-6 py-3 bg-gray-50 @if (\App::isLocale('ar')) text-right @else text-left @endif text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                    {{ __('Class') }}
                </th>
                <th
                    class="px-6 py-3 bg-gray-50 @if (\App::isLocale('ar')) text-right @else text-left @endif text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                    {{ __('Classroom') }}
                </th>
                <th
                    class="px-6 py-3 bg-gray-50 @if (\App::isLocale('ar')) text-left @else text-right @endif text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                </th>
            </tr>
        </thead>
        <tbody class="devide-y devide-gray-200">
            @if ($data->count())
                @foreach ($data as $student)
                    <tr @if ($student->freezed) class="bg-gray-200" @else class="bg-white" @endif>
                        <td class="px-6 py-4 text-sm whitespace-no-wrap">{{ $student->code }}</td>
                        <td class="px-6 py-4 text-sm whitespace-no-wrap">{{ $student->name }}</td>
                        <td class="px-6 py-4 text-sm whitespace-no-wrap">{{ $student->gender }}</td>
                        <td class="px-6 py-4 text-sm whitespace-no-wrap">{{ $student->class }}</td>
                        <td class="px-6 py-4 text-sm whitespace-no-wrap">{{ $student->classroom }}</td>
                        <td class="px-6 py-4 text-sm text-right">
                            <button class="btn btn-teal" wire:click="showRemarks({{ $student->id }})">
                                {{ __('Show Remarks') }}
                            </button>
                            @if($student->school->use_points)
                                <button class="btn btn-teal" wire:click="showPoints({{ $student->id }})">
                                    {{ __('Show Points') }}
                                </button>
                            @endif
                            <button class="btn btn-blue" wire:click="freeze({{ $student->id }})">
                                @if (!$student->freezed)
                                    {{ __('Freeze') }}
                                @else
                                    {{ __('Unfreeze') }}
                                @endif
                            </button>
                            <x-jet-button class="my-1" wire:click="passwordShowModal({{ $student->user_id }})">
                                {{ __('Change Password') }}
                            </x-jet-button>
                            {{-- @if (auth()->user()->school_code != null)
                                <x-jet-button class="my-1" wire:click="testShowModal({{ $student->id }})">
                                    {{ __('Add Test Results') }}
                                </x-jet-button>
                            @endif --}}
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

            <x-jet-button class="@if (\App::isLocale('ar')) mr-3 @else ml-3 @endif" wire:click="changePassword"
                wire:loading.attr="disabled">
                {{ __('Save') }}
            </x-jet-button>
        </x-slot>
    </x-jet-dialog-modal>
    {{-- <x-jet-dialog-modal wire:model="testFormVisible">
        <x-slot name="title">
            {{ __('Add Test Results') }}
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

            <x-jet-button class="@if (\App::isLocale('ar')) mr-3 @else ml-3 @endif" wire:click="changePassword"
                wire:loading.attr="disabled">
                {{ __('Save') }}
            </x-jet-button>
        </x-slot>
    </x-jet-dialog-modal> --}}
</div>
