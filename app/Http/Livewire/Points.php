<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\StudentPoints;
use Livewire\WithPagination;

class Points extends Component
{
    use WithPagination;
    public $student;
    public $searchTerm;
    public $name;
    public $class;
    public $classroom;
    public $date_from;
    public $date_to;

    public function mount()
    {
        if (!auth()->user()->is_admin && !auth()->user()->school->use_points)
            abort('401', 'Unauthorized');
    }
    public function read()
    {
        $searchTerm = '%' . $this->searchTerm . '%';
        $points = StudentPoints::where('remark', 'like', $searchTerm);
        if ($this->date_from)
            $points->whereDate('date', '>=', $this->date_from);
        if ($this->date_to)
            $points->whereDate('date', '<=', $this->date_to);
        if ($this->name) {
            $name = '%' . $this->name . '%';
            $points->whereHas('student', function ($q) use ($name) {
                $q->where('name', 'like', $name);
            });
        }
        if ($this->class) {
            $class = '%' . $this->class . '%';
            $points->whereHas('student', function ($q) use ($class) {
                $q->where('class', 'like', $class);
            });
        }
        if ($this->classroom) {
            $classroom = '%' . $this->classroom . '%';
            $points->whereHas('student', function ($q) use ($classroom) {
                $q->where('classroom', 'like', $classroom);
            });
        }
        if (auth()->user()->school_code)
            $points->where('school-code', auth()->user()->school_code);
        return $points->orderBy('date', 'desc')->paginate(10);
    }

    public function render()
    {
        return view('livewire.points', [
            'data' => $this->read()
        ]);
    }
}
