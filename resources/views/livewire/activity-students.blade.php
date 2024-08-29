<div class="p-6">
    <x-jet-input id="search" type="text" class="mt-1 block w-full" placeholder="{{ __('Search') }}"
        wire:model="searchTerm" />
    <table class="w-full divide-y divide-gray-200">
        <thead class="bg-white border-b">
            <tr>
                <th
                    class="px-6 py-3 bg-gray-50 @if (\App::isLocale('ar')) text-right @else text-left @endif text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                    {{ __('Name') }}</th>
                <th
                    class="px-6 py-3 bg-gray-50 @if (\App::isLocale('ar')) text-right @else text-left @endif text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                    {{ __('School') }}</th>
                <th
                    class="px-6 py-3 bg-gray-50 @if (\App::isLocale('ar')) text-right @else text-left @endif text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                    {{ __('Class') }}</th>
                <th
                    class="px-6 py-3 bg-gray-50 @if (\App::isLocale('ar')) text-right @else text-left @endif text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                    {{ __('Classroom') }}</th>
            </tr>
        </thead>
        <tbody class="bg-white devide-y devide-gray-200">
            @if ($data->count())
                @foreach ($data as $student)
                    <tr>
                        <td class="px-6 py-4 text-sm whitespace-no-wrap">{{ $student->name }}</td>
                        <td class="px-6 py-4 text-sm whitespace-no-wrap">{{ $student->school->name }}</td>
                        <td class="px-6 py-4 text-sm whitespace-no-wrap">{{ $student->class }}</td>
                        <td class="px-6 py-4 text-sm whitespace-no-wrap">{{ $student->classroom }}</td>
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

    <div class="flex items-center justify-end px-4 py-3 text-right sm:px-6">
        <x-jet-button wire:click="back">
            {{ __('Back') }}
        </x-jet-button>
    </div>
</div>
