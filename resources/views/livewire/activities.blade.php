<div class="p-6">

    <div class="flex items-center justify-between px-4 py-3 text-right sm:px-6">

        <x-jet-button wire:click="createShowModal">
            {{ __('Create') }}
        </x-jet-button>
    </div>
    {{-- <x-jet-input id="search" type="text" class="mt-1 block w-full" placeholder="{{ __('Search') }}"
        wire:model="searchTerm" /> --}}
    <table class="w-full divide-y divide-gray-200">
        <thead class="bg-white border-b">
            <tr>
                <th
                    class="px-6 py-3 bg-gray-50 @if (\App::isLocale('ar')) text-right @else text-left @endif text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                    #</th>
                <th
                    class="px-6 py-3 bg-gray-50 @if (\App::isLocale('ar')) text-right @else text-left @endif text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                    {{ __('Title') }}</th>
                <th
                    class="px-6 py-3 bg-gray-50 @if (\App::isLocale('ar')) text-right @else text-left @endif text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                    {{ __('Remark') }}</th>
                <th
                    class="px-6 py-3 bg-gray-50 @if (\App::isLocale('ar')) text-right @else text-left @endif text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                    {{ __('Class') }}</th>
                <th
                    class="px-6 py-3 bg-gray-50 @if (\App::isLocale('ar')) text-right @else text-left @endif text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                    {{ __('Classroom') }}</th>
                <th
                    class="px-6 py-3 bg-gray-50 @if (\App::isLocale('ar')) text-right @else text-left @endif text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                    {{ __('Points') }}</th>
                <th
                    class="px-6 py-3 bg-gray-50 @if (\App::isLocale('ar')) text-right @else text-left @endif text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                    {{ __('Max Students') }}</th>
                <th
                    class="px-6 py-3 bg-gray-50 @if (\App::isLocale('ar')) text-right @else text-left @endif text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                    {{ __('Active Students') }}</th>
                <th
                    class="px-6 py-3 bg-gray-50 @if (\App::isLocale('ar')) text-right @else text-left @endif text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                    {{ __('End Date') }}</th>
                <th
                    class="px-6 py-3 bg-gray-50 @if (\App::isLocale('ar')) text-left @else text-right @endif text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                </th>
            </tr>
        </thead>
        <tbody class="devide-y devide-gray-200">
            @if ($data->count())
                @foreach ($data as $activity)
                    <tr>
                        <td class="px-6 py-4 text-sm whitespace-no-wrap">{{ $activity->id }}</td>
                        <td class="px-6 py-4 text-sm whitespace-no-wrap">{{ $activity->title }}</td>
                        <td class="px-6 py-4 text-sm whitespace-no-wrap">{{ $activity->remark }}</td>
                        <td class="px-6 py-4 text-sm whitespace-no-wrap">{{ $activity->class ?: 'جميع الصفوف' }}</td>
                        <td class="px-6 py-4 text-sm whitespace-no-wrap">{{ $activity->classroom ?: 'جميع الشعب' }}</td>
                        <td class="px-6 py-4 text-sm whitespace-no-wrap">{{ $activity->points }}</td>
                        <td class="px-6 py-4 text-sm whitespace-no-wrap">{{ $activity->max }}</td>
                        <td class="px-6 py-4 text-sm whitespace-no-wrap">{{ $activity->count }}</td>
                        <td class="px-6 py-4 text-sm whitespace-no-wrap">{{ $activity->end_date }}</td>
                        <td class="px-6 py-4 text-sm text-right">
                            <button class="btn btn-teal" wire:click="showStudents({{ $activity->id }})">
                                {{ __('Show Students') }}
                            </button>
                        </td>

                    </tr>
                @endforeach
            @else
                <tr>
                    <td class="px-6 py-4 text-sm text-center" colspan="9">
                        {{ __('No data to show.') }}
                    </td>
                </tr>
            @endif
        </tbody>
    </table>
    {{ $data->links('vendor.livewire.tailwind') }}
    <x-jet-dialog-modal wire:model="modalFormVisible">
        <x-slot name="title">
            {{ __('Save Activity') }}
        </x-slot>

        <x-slot name="content">
            <div class="mt-4">
                <x-jet-label for="end_date" value="{{ __('End Date') }}" />
                <x-jet-input id="end_date" type="text" class="my-1 mx-1 w-1/3"
                wire:model="end_date" onfocus="(this.type='date')" onblur="(this.type='text')" />
                @error('end_date')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>
            <div class="mt-4">
                <x-jet-label for="title" value="{{ __('Title') }}" />
                <x-jet-input id="title" type="text" class="mt-1 block w-full"
                    wire:model.debounce.800ms="title" />
                @error('title')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>
            <div class="mt-4">
                <x-jet-label for="remark" value="{{ __('Remark') }}" />
                <textarea wire:model="remark" id="remark" name="remark" rows="4"
                    class="border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm mt-1 block w-full"></textarea>
            @error('remark')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>
            <div class="mt-4">
                <x-jet-label for="class" value="{{ __('Class') }}:" />
                <select name="class" wire:model="class" wire:change="$refresh"
                    class="border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm mt-1 block w-full">
                    @foreach ($classes as $class_val)
                        <option value="{{ $class_val['class'] }}" @if ($class_val['class'] == $class) selected @endif>
                            {{ $class_val['class'] ? $class_val['class'] : 'جميع الصفوف' }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mt-4">
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
            <div class="mt-4">
                <x-jet-label for="points" value="{{ __('Points') }}" />
                <x-jet-input id="points" type="number" class="mt-1 block w-full" min="0"
                    wire:model.debounce.800ms="points" />
                @error('points')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>
            <div class="mt-4">
                <x-jet-label for="max" value="{{ __('Max Students') }}" />
                <x-jet-input id="max" type="number" class="mt-1 block w-full" min="0"
                    wire:model.debounce.800ms="max" />
                @error('max')
                    <span class="error">{{ $message }}</span>
                @enderror
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

    {{-- <x-jet-dialog-modal wire:model="passwordFormVisible">
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
    </x-jet-dialog-modal> --}}
</div>
