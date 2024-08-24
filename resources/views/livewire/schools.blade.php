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
                    class="px-6 py-3 bg-gray-50 @if (\App::isLocale('ar')) text-left @else text-right @endif text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                </th>
            </tr>
        </thead>
        <tbody class="devide-y devide-gray-200">
            @if ($data->count())
                @foreach ($data as $school)
                    <tr @if ($school->freezed) class="bg-gray-200" @else class="bg-white" @endif>
                        <td class="px-6 py-4 text-sm whitespace-no-wrap">{{ $school->code }}</td>
                        <td class="px-6 py-4 text-sm whitespace-no-wrap">{{ $school->name }}</td>
                        <td class="px-6 py-4 text-sm text-right">
                            <button class="btn btn-blue" wire:click="freeze({{ $school->id }})">
                                @if (!$school->freezed)
                                    {{ __('Freeze') }}
                                @else
                                    {{ __('Unfreeze') }}
                                @endif
                            </button>
                            <button class="btn btn-blue" wire:click="usePoints({{ $school->id }})">
                                @if (!$school->use_points)
                                    {{ __('Use Points') }}
                                @else
                                    {{ __('Stop Points') }}
                                @endif
                            </button>
                        </td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td class="px-6 py-4 text-sm text-center" colspan="3">
                        {{ __('No data to show.') }}
                    </td>
                </tr>
            @endif
        </tbody>
    </table>
    {{ $data->links('vendor.livewire.tailwind') }}
</div>
