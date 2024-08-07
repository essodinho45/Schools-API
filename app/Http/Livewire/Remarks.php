<?php

namespace App\Http\Livewire;

use App\Models\Student;
use App\Models\Remark;
use Livewire\Component;
use Livewire\WithPagination;

class Remarks extends Component
{
    use WithPagination;
    public $student;
    public $searchTerm;

    public function mount($id)
    {
        $this->student = Student::find($id);
    }

    public function read()
    {
        $searchTerm = '%' . $this->searchTerm . '%';
        return Remark::where('text', 'like', $searchTerm)->where('student_id', $this->student->id)->orderBy('date', 'desc')->paginate(10);
    }

    public function back()
    {
        return redirect()->route('students');
    }

    public function render()
    {
        return view('livewire.remarks', [
            'data' => $this->read()
        ]);
    }
}
