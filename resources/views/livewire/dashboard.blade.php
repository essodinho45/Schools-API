<div>
    <table class="w-full divide-y divide-gray-200">
        <thead class="bg-white border-b">
            <tr>
                <th
                    class="px-6 py-3 bg-gray-50 @if (\App::isLocale('ar')) text-right @else text-left @endif text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                    {{ __('School') }}</th>
                <th
                    class="px-6 py-3 bg-gray-50 @if (\App::isLocale('ar')) text-right @else text-left @endif text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                    {{ __('Sent Count') }}</th>
                <th
                    class="px-6 py-3 bg-gray-50 @if (\App::isLocale('ar')) text-right @else text-left @endif text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                    {{ __('Not Sent Count') }}</th>
                <th
                    class="px-6 py-3 bg-gray-50 @if (\App::isLocale('ar')) text-right @else text-left @endif text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                    {{ __('Total Count') }}</th>
                <th
                    class="px-6 py-3 bg-gray-50 @if (\App::isLocale('ar')) text-right @else text-left @endif text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                    {{ __('Not Sent Percentage') }}</th>
                {{-- <th
                    class="px-6 py-3 bg-gray-50 @if (\App::isLocale('ar')) text-left @else text-right @endif text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                </th> --}}
            </tr>
        </thead>
        <tbody class="bg-white devide-y devide-gray-200">
            @if ($data->count())
                @foreach ($data as $school)
                    <tr>
                        <td class="px-6 py-4 text-sm whitespace-no-wrap">{{ $school->name }}</td>
                        <td class="px-6 py-4 text-sm whitespace-no-wrap">{{ $school->remarks_count['sent'] }}</td>
                        <td class="px-6 py-4 text-sm whitespace-no-wrap">{{ $school->remarks_count['not_sent'] }}</td>
                        <td class="px-6 py-4 text-sm whitespace-no-wrap">{{ $school->remarks_count['total'] }}</td>
                        <td class="px-6 py-4 text-sm whitespace-no-wrap">
                            {{ number_format(($school->remarks_count['total'] ? $school->remarks_count['not_sent'] / $school->remarks_count['total'] : 0) * 100, 2) }}&nbsp;%
                        </td>
                        {{-- <td class="px-6 py-4 text-sm text-right">
                            @if (auth()->user()->school_code != null || auth()->user()->is_admin)
                                <x-jet-button class="my-1" wire:click="testShowModal({{ $school->id }})">
                                    {{ __('Add Test Results') }}
                                </x-jet-button>
                            @endif
                        </td> --}}
                    </tr>
                @endforeach
            @else
                <tr>
                    <td class="px-6 py-4 text-sm text-center" colspan="7">
                        {{ __('No data to show.') }}
                    </td>
                </tr>
            @endif
        </tbody>
    </table>
    {{ $data->links('vendor.livewire.tailwind') }}
</div>
{{-- <x-jet-dialog-modal wire:model="testFormVisible">
    <x-slot name="title">
        {{ __('Add Test Results') }}
    </x-slot>

    <x-slot name="content">
        <div class="mt-4">
            <x-jet-label for="subject" value="{{ __('Subject') }}" />
            <x-jet-input id="subject" type="text" class="mt-1 block w-full" wire:model.debounce.800ms="subject" />
            @error('subject')
                <span class="error">{{ $message }}</span>
            @enderror
        </div>
        <div class="mt-4">
            <label for="active" class="inline-flex items-center">
                <input id="active" type="checkbox"
                    class="rounded border-gray-300 text-indigo-800 shadow-sm focus:ring-indigo-800" name="active"
                    wire:model="active">
                <span
                    class="@if (App::isLocale('ar')) mr-2 @else ml-2 @endif text-sm text-gray-600">{{ __('Active') }}</span>
            </label>
        </div>
        <hr>
        @if ($test)
            <table>
                <thead>
                    <tr>
                        <th>{{ __('Subject') }}</th>
                        <th>{{ __('Active') }}</th>
                        <th></th>
                    </tr>
                </thead>
                @foreach ($test->subjects as $subject)
                    <tr>
                        <td>{{ $subject->title }}</td>
                        <td>{{ $subject->is_active }}</td>
                        <td>
                            <x-jet-button wire:click="">
                                {{ __('Remove') }}
                            </x-jet-button>
                        </td>
                    </tr>
                @endforeach
            </table>
        @endif
        <hr>
        <div class="mt-4">
            <x-jet-label for="date" value="{{ __('Date') }}%" />
            <x-jet-input id="date" type="date" class="mt-1 block w-full" wire:model.debounce.800ms="date" />
            @error('date')
                <span class="error">{{ $message }}</span>
            @enderror
        </div>
        <div class="mt-4">
            <x-jet-label for="min_mark" value="{{ __('Min Mark') }}%" />
            <x-jet-input id="min_mark" type="number" class="mt-1 block w-full" wire:model.debounce.800ms="min_mark" />
            @error('min_mark')
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
