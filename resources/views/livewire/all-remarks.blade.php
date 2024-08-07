<div class="p-6">
    <div class="flex">
        <x-jet-input id="search" type="text" class="my-1 mx-1 w-1/3" placeholder="{{ __('Search') }}"
            wire:model="searchTerm" />
        <x-jet-input id="date_from" type="text" class="my-1 mx-1 w-1/3" placeholder="{{ __('Date From') }}"
            wire:model="date_from" onfocus="(this.type='date')" onblur="(this.type='text')" />
        <x-jet-input id="date_to" type="text" class="my-1 mx-1 w-1/3" placeholder="{{ __('Date To') }}"
            wire:model="date_to" onfocus="(this.type='date')" onblur="(this.type='text')" />
    </div>
    <div class="flex">
        <x-jet-input id="name" type="text" class="my-1 mx-1 w-1/3" placeholder="{{ __('Student') }}"
            wire:model="name" />
        <x-jet-input id="class" type="text" class="my-1 mx-1 w-1/3" placeholder="{{ __('Class') }}"
            wire:model="class" />
        <x-jet-input id="classroom" type="text" class="my-1 mx-1 w-1/3" placeholder="{{ __('Classroom') }}"
            wire:model="classroom" />
    </div>
    <table class="w-full divide-y divide-gray-200">
        <thead class="bg-white border-b">
            <tr>
                <th
                    class="px-6 py-3 bg-gray-50 @if (\App::isLocale('ar')) text-right @else text-left @endif text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                    #</th>
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
                    {{ __('Title') }}</th>
                <th
                    class="px-6 py-3 bg-gray-50 @if (\App::isLocale('ar')) text-right @else text-left @endif text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                    {{ __('Text') }}</th>
                <th
                    class="px-6 py-3 bg-gray-50 @if (\App::isLocale('ar')) text-right @else text-left @endif text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                    {{ __('Date') }}</th>
                <th
                    class="px-6 py-3 bg-gray-50 @if (\App::isLocale('ar')) text-right @else text-left @endif text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                    {{ __('Category') }}</th>
                <th
                    class="px-6 py-3 bg-gray-50 @if (\App::isLocale('ar')) text-right @else text-left @endif text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                    {{ __('Notified') }}</th>
                <th
                    class="px-6 py-3 bg-gray-50 @if (\App::isLocale('ar')) text-right @else text-left @endif text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                    {{ __('Read') }}</th>
            </tr>
        </thead>
        <tbody class="bg-white devide-y devide-gray-200">
            @if ($data->count())
                @foreach ($data as $remark)
                    <tr>
                        <td class="px-6 py-4 text-sm whitespace-no-wrap">{{ $remark->id }}</td>
                        <td class="px-6 py-4 text-sm whitespace-no-wrap">{{ $remark->student->name }}</td>
                        <td class="px-6 py-4 text-sm whitespace-no-wrap">{{ $remark->school->name }}</td>
                        <td class="px-6 py-4 text-sm whitespace-no-wrap">{{ $remark->student->class }}</td>
                        <td class="px-6 py-4 text-sm whitespace-no-wrap">{{ $remark->student->classroom }}</td>
                        <td class="px-6 py-4 text-sm whitespace-no-wrap">{{ $remark->title }}</td>
                        <td class="px-6 py-4 text-sm whitespace-no-wrap">{{ $remark->text }}</td>
                        <td class="px-6 py-4 text-sm whitespace-no-wrap">{{ $remark->date }}</td>
                        <td class="px-6 py-4 text-sm whitespace-no-wrap">{{ $remark->category->name }}</td>
                        <td class="px-6 py-4 text-sm whitespace-no-wrap">
                            <input type="checkbox"
                                class="rounded border-gray-300 text-indigo-800 shadow-sm focus:ring-indigo-800" disabled
                                @if ($remark->{'is-sent-firebase'}) checked @endif>
                        </td>
                        <td class="px-6 py-4 text-sm whitespace-no-wrap">
                            <input type="checkbox"
                                class="rounded border-gray-300 text-indigo-800 shadow-sm focus:ring-indigo-800" disabled
                                @if ($remark->{'is-read'}) checked @endif>
                        </td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td class="px-6 py-4 text-sm text-center" colspan="11">
                        {{ __('No data to show.') }}
                    </td>
                </tr>
            @endif
        </tbody>
    </table>
    {{ $data->links('vendor.livewire.tailwind') }}

</div>
