<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Remark;
use Livewire\WithPagination;

class AllRemarks extends Component
{
    use WithPagination;
    public $student;
    public $searchTerm;
    public $name;
    public $class;
    public $classroom;
    public $date_from;
    public $date_to;

    public function read()
    {
        $searchTerm = '%' . $this->searchTerm . '%';
        $remarks = Remark::where('text', 'like', $searchTerm);
        if ($this->date_from)
            $remarks->whereDate('date', '>=', $this->date_from);
        if ($this->date_to)
            $remarks->whereDate('date', '<=', $this->date_to);
        if ($this->name)
        {
            $name = '%'.$this->name.'%';
            $remarks->whereHas('student', function($q) use ($name){
                $q->where('name', 'like', $name);
            });
        }
        if ($this->class)
        {
            $class = '%'.$this->class.'%';
            $remarks->whereHas('student', function($q) use ($class){
                $q->where('class', 'like', $class);
            });
        }
        if ($this->classroom)
        {
            $classroom = '%'.$this->classroom.'%';
            $remarks->whereHas('student', function($q) use ($classroom){
                $q->where('classroom', 'like', $classroom);
            });
        }
        if (auth()->user()->school_code)
            $remarks->where('school-code', auth()->user()->school_code);
        return $remarks->orderBy('date', 'desc')->paginate(10);
    }

    public function render()
    {
        return view('livewire.all-remarks', [
            'data' => $this->read()
        ]);
    }
}
