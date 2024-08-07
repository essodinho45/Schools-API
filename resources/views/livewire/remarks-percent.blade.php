<div class="p-6">
    <div class="flex">
        {{-- <div class="my-1 mx-1 w-1/4">
            <x-jet-label for="school" value="{{ __('School') }}:" />
            <select name="school" wire:model="school" wire:change="$refresh"
                class="border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm mt-1 block w-full">
                @foreach ($schools as $school_val)
                    <option value="{{ $school_val->code }}" @if ($school_val->code == $school) selected @endif>
                        {{ $school_val->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <x-jet-input id="class" type="text" class="my-1 mx-1 w-1/4" placeholder="{{ __('Class') }}"
            wire:model="class" />
        <x-jet-input id="classroom" type="text" class="my-1 mx-1 w-1/4" placeholder="{{ __('Classroom') }}"
            wire:model="classroom" /> --}}
        <x-jet-input id="name" type="text" class="my-1 mx-1 w-1/4" placeholder="{{ __('Student') }}"
            wire:model="name" />
    </div>
    <table class="w-full divide-y divide-gray-200">
        <thead class="bg-white border-b">
            <tr>
                <th
                    class="px-6 py-3 bg-gray-50 @if (\App::isLocale('ar')) text-right @else text-left @endif text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                    {{ __('Student') }}</th>
                <th
                    class="px-6 py-3 bg-gray-50 @if (\App::isLocale('ar')) text-right @else text-left @endif text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                    {{ __('School') }}</th>
                <th
                    class="px-6 py-3 bg-gray-50 @if (\App::isLocale('ar')) text-right @else text-left @endif text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                    {{ __('Class') }}</th>
                <th
                    class="px-6 py-3 bg-gray-50 @if (\App::isLocale('ar')) text-right @else text-left @endif text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                    {{ __('Classroom') }}</th>
                <th
                    class="px-6 py-3 bg-gray-50 @if (\App::isLocale('ar')) text-right @else text-left @endif text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                    {{ __('Total Count') }}</th>
                <th
                    class="px-6 py-3 bg-gray-50 @if (\App::isLocale('ar')) text-right @else text-left @endif text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                    {{ __('Not Read Count') }}</th>
                <th
                    class="px-6 py-3 bg-gray-50 @if (\App::isLocale('ar')) text-right @else text-left @endif text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                    {{ __('Percentage') }}</th>
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
                        <td class="px-6 py-4 text-sm whitespace-no-wrap">{{ $student->total_count }}</td>
                        <td class="px-6 py-4 text-sm whitespace-no-wrap">{{ $student->not_read_count }}</td>
                        <td class="px-6 py-4 text-sm whitespace-no-wrap">
                            {{ number_format($student->percentage * 100, 2) }}&nbsp;%</td>
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
