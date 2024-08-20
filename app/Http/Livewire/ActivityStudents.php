<?php

namespace App\Http\Livewire;

use App\Models\Student;
use App\Models\Activity;
use App\Models\StudentPoints;
use Livewire\Component;
use Livewire\WithPagination;

class ActivityStudents extends Component
{
    use WithPagination;
    public $activity;
    public $searchTerm;

    public function mount($id)
    {
        $this->activity = Activity::find($id);
    }

    public function read()
    {
        $searchTerm = '%' . $this->searchTerm . '%';
        $std_ids = StudentPoints::where('activity_id', $this->activity->id)->pluck('student_id');
        return Student::where('name', 'like', $searchTerm)->whereIn('id', $std_ids)->paginate(10);
    }

    public function back()
    {
        return redirect()->route('activities');
    }

    public function render()
    {
        return view('livewire.activity-students', [
            'data' => $this->read()
        ]);
    }
}
