<?php

namespace App\Http\Livewire;

use App\Models\School;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithPagination;

class Schools extends Component
{
    use WithPagination;
    public $searchTerm;
    public function read()
    {
        $searchTerm = '%' . $this->searchTerm . '%';
        return School::where('name', 'like', $searchTerm)->paginate(10);
    }

    public function render()
    {
        return view('livewire.schools', [
            'data' => $this->read()
        ]);
    }


    public function freeze($id)
    {
        $current = School::find($id);
        $current->freezed = !$current->freezed;
        $current->save();
    }
    public function usePoints($id)
    {
        $current = School::find($id);
        $current->use_points = !$current->use_points;
        $current->save();
    }
}
