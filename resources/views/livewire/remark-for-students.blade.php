<div class="p-6" x-data="{ showContextMenu: false }">
    <div class="flex">
        <div class="@if (\App::isLocale('ar')) pl-6 @else pr-6 @endif w-1/3">
            <div class="block mt-2">
                <x-jet-label for="class" value="{{ __('Class') }}:" />
                <select name="class" wire:model="class" wire:change="$refresh"
                    class="border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm mt-1 block w-full">
                    @foreach ($classes as $class_val)
                        <option value="{{ $class_val['class'] }}" @if ($class_val['class'] == $class) selected @endif>
                            {{ $class_val['class'] }}</option>
                    @endforeach
                </select>
            </div>
            <div class="block mt-2">
                <x-jet-label for="classroom" value="{{ __('Classroom') }}:" />
                <select name="classroom" wire:model="classroom" wire:change="$refresh"
                    class="border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm mt-1 block w-full">
                    @foreach ($classrooms as $classroom_val)
                        <option value="{{ $classroom_val['classroom'] }}"
                            @if ($classroom_val['classroom'] == $classroom) selected @endif>
                            {{ $classroom_val['classroom'] ? $classroom_val['classroom'] : 'جميع الشعب' }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="block mt-2">
                <x-jet-label for="remark_category" value="{{ __('Remark Category') }}:" />
                <select name="remark_category" wire:model="remark_category"
                    class="border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm mt-1 block w-full">
                    @foreach ($remark_categories as $remark_category_val)
                        <option value={{ $remark_category_val->id }}>{{ $remark_category_val->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mt-2 hidden">
                <x-jet-label for="date" value="{{ __('Date') }}:" />
                <x-jet-input id="date" type="date" class="mt-1 block w-full disabled"
                    wire:model.debounce.800ms="date" value="{{ Carbon\Carbon::now()->format('Y-m-d') }}" disabled />
            </div>
            <div class="relative" @click.away="showContextMenu=false">
                <div class="block mt-2" x-on:contextmenu="$event.preventDefault();showContextMenu=true"
                    @click.prevent="showContextMenu=false">
                    <x-jet-label for="remark_text" value="{{ __('Remark Text') }}:" />
                    <textarea wire:model="remark_text" id="remark_text" name="remark_text" rows="4"
                        class="border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm mt-1 block w-full"></textarea>
                </div>
                <div class="absolute mt-12 top-0 left-1 w-1/3 w-48 z-30" style="display:none;" x-show="showContextMenu"
                    x-transition:enter="transition ease duration-100 transform"
                    x-transition:enter-start="opacity-0 scale-90 translate-y-1"
                    x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                    x-transition:leave="transition ease duration-100 transform"
                    x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                    x-transition:leave-end="opacity-0 scale-90 translate-y-1">
                    <span
                        class="absolute top-0 left-0 w-2 h-2 bg-white transform rotate-45 -mt-1 ml-3 border-gray-300 border-l border-t z-20"></span>
                    <div
                        class="bg-white overflow-auto rounded-lg shadow-md w-full relative z-10 py-2 border border-gray-300 text-gray-800 text-xs">
                        <ul class="list-reset">
                            <li>
                                <a href="#"
                                    class="px-4 py-1 flex hover:bg-gray-100 no-underline hover:no-underline transition-colors duration-100"
                                    @click.prevent="showContextMenu=false"
                                    wire:click="addStudentName">{{ __('Student Name') }}</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="w-2/3">
            <div class="block mt-2">
                <x-jet-label for="search" value="{{ __('Filter') }}" />
                <x-jet-input id="search" type="text" class="mt-1 block w-full" placeholder="{{ __('Search') }}"
                    wire:model="searchTerm" />
            </div>
            <table class="w-full divide-y divide-gray-200 mt-2">
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
                            <a href="#" wire:click="selectAll">{{ __('Selected') }}</a>
                        </th>
                    </tr>
                </thead>
                <tbody class="devide-y devide-gray-200">
                    @if ($data->count())
                        @foreach ($data as $student)
                            <tr @if ($student->freezed) class="bg-gray-200" @else class="bg-white" @endif>
                                <td class="px-6 py-4 text-sm whitespace-no-wrap">{{ $student->code }}</td>
                                <td class="px-6 py-4 text-sm whitespace-no-wrap">{{ $student->name }}</td>
                                <td
                                    class="px-6 py-4 text-sm @if (\App::isLocale('ar')) text-left @else text-right @endif">
                                    <input type="checkbox" @if (in_array($student->id, $selected)) checked @endif
                                        class="rounded border-gray-300 text-indigo-800 shadow-sm focus:ring-indigo-800"
                                        name="selected_{{ $student->id }}"
                                        wire:click="changeSelected({{ $student->id }})">
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
        </div>
    </div>
    <div class="flex flex-row py-4">
        <x-jet-button class="@if (\App::isLocale('ar')) mr-3 @else ml-3 @endif" wire:loading.attr="disabled"
            wire:click="sendRemark">
            {{ __('Send') }}
        </x-jet-button>
        {{-- wire:click="sendRemark" --}}
    </div>
</div>
