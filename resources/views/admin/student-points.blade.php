<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Points For Student: ') }} {{ $student->name }} | {{ __('Total Points: ') }} {{ $student->points->sum('points') ?? 0 }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                @livewire('student-points', ['id' => $student->id])
            </div>
        </div>
    </div>
</x-app-layout>
