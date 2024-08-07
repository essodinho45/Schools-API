<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Student;
use Livewire\WithPagination;
use Illuminate\Pagination\LengthAwarePaginator;

class RemarksPercent extends Component
{
    use WithPagination;
    // public $school;
    public $name;
    public $currentPage;
    // public $class;
    // public $classroom;

    // public function mount()
    // {
    //     $this->classes = Student::where('school-code', auth()->user()->school_code)->select('class')->distinct()->get()->toArray();
    //     if (count($this->classes)){
    //         $this->classrooms = Student::where('school-code', auth()->user()->school_code)->where('class', $this->classes[0])->select('classroom')->distinct()->get()->toArray();
    //         array_unshift($this->classrooms, ['classroom'=>'']);
    //     }
    //     $this->remark_categories = RemarksCategory::all();
    //     if (count($this->classes))
    //         $this->class = $this->classes[0]['class'];
    //     if (count($this->classrooms))
    //         $this->classroom = $this->classrooms[0]['classroom'];
    //     if (count($this->remark_categories))
    //         $this->remark_category = $this->remark_categories[0]['id'];
    // }

    public function read()
    {
        $students = Student::where('freezed', false);
        // if ($this->school)
        //     $remarks->where('school-code', $this->school);
        // if ($this->class)
        // {
        //     $class = '%'.$this->class.'%';
        //     $remarks->whereHas('student', function($q) use ($class){
        //         $q->where('class', 'like', $class);
        //     });
        // }
        // if ($this->classroom)
        // {
        //     $classroom = '%'.$this->classroom.'%';
        //     $remarks->whereHas('student', function($q) use ($classroom){
        //         $q->where('classroom', 'like', $classroom);
        //     });
        // }
        $this->currentPage = LengthAwarePaginator::resolveCurrentPage();

        if ($this->name)
        {
            $name = '%'.$this->name.'%';
            $students->where('name', 'like', $name);
        }
        if (auth()->user()->school_code)
            $students->where('school-code', auth()->user()->school_code);
        $items = array();
        $students = $students->get()->sortByDesc('total_count')->sortByDesc('percentage')
        ->filter(function ($student) {
            return $student->not_read_count > 0;
        });;
        foreach($students as $student)
            array_push($items, $student);
        $perPage = 10;

        $currentItems = array_slice($items, $perPage * ($this->currentPage - 1), $perPage);

        $paginator = new LengthAwarePaginator($currentItems, count($items), $perPage, $this->currentPage);
        $results = $paginator;
        return $results;

    }

    public function updatedName()
    {
        $this->currentPage = 1;
    }

    public function render()
    {
        return view('livewire.remarks-percent',[
            'data' => $this->read()
        ]);
    }
}
