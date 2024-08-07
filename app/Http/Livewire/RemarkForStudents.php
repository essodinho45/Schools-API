<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Student;
use App\Models\RemarksCategory;
use App\Models\Remark;
use App\Models\School;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class RemarkForStudents extends Component
{
    use WithPagination;
    public $class;
    public $classes = [];
    public $classroom;
    public $classrooms = [];
    public $remark_category;
    public $remark_categories = [];
    public $date;
    public $searchTerm;
    public $remark_text;
    public $selected = [];
    public $selectAll;
    public function mount()
    {
        $this->selectAll = false;
        $this->selected = [];
        $this->classes = Student::where('school-code', auth()->user()->school_code)->select('class')->distinct()->get()->toArray();
        if (count($this->classes)){
            $this->classrooms = Student::where('school-code', auth()->user()->school_code)->where('class', $this->classes[0])->select('classroom')->distinct()->get()->toArray();
            array_unshift($this->classrooms, ['classroom'=>'']);
        }
        $this->remark_categories = RemarksCategory::all();
        if (count($this->classes))
            $this->class = $this->classes[0]['class'];
        if (count($this->classrooms))
            $this->classroom = $this->classrooms[0]['classroom'];
        if (count($this->remark_categories))
            $this->remark_category = $this->remark_categories[0]['id'];
    }
    public function updatedClass()
    {
        $this->selected = [];
        $this->classrooms = Student::where('school-code', auth()->user()->school_code)->where('class', $this->class)->select('classroom')->distinct()->get()->toArray();
        array_unshift($this->classrooms, ['classroom'=>'']);
        $this->classroom = $this->classrooms[0]['classroom'];
    }

    public function updatedClassroom()
    {
        $this->selected = [];
    }
    public function addStudentName()
    {
        $this->remark_text = $this->remark_text . "@name";
    }
    public function selectAll()
    {
        $searchTerm = '%' . $this->searchTerm . '%';
        if (!$this->selectAll){
            $to_select = Student::where('name', 'like', $searchTerm)->where('school-code', auth()->user()->school_code)
            ->where('class', $this->class);
        if(!empty($this->classroom))
            $to_select = $to_select->where('classroom', $this->classroom);
        $this->selected = $to_select->pluck('id')->toArray();
        }
        else
            $this->selected = [];
        $this->selectAll = !$this->selectAll;
    }
    public function changeSelected($student_id)
    {
        $pos = array_search($student_id, $this->selected);
        if ($pos !== false)
            unset($this->selected[$pos]);
        else
            array_push($this->selected, $student_id);
    }
    public function sendRemark()
    {
        try{
            Log::info('start');
            foreach ($this->selected as $student_id) {
                $student = Student::find($student_id);
                $category = RemarksCategory::find($this->remark_category);
                $school_code = $student->{"school-code"};
                $school = School::where('code', $school_code)->first();
                $category_code = str_replace(' ', '%20', $category->code);
                $code = str_replace(' ', '%20', $student->code);
                $school_code = str_replace(' ', '%20', $school_code);
                $this->remark_text = str_replace('@name', $student->name, $this->remark_text);
                $this->remark_text = str_replace(' ', '%20', $this->remark_text);
                $this->remark_text = str_replace(PHP_EOL, '%0A', $this->remark_text);
                $this->remark_text = str_replace("\n", '%0A', $this->remark_text);
                $text = $this->remark_text;
                $title = str_replace(' ', '%20', $school->name);
                $res = Http::get(config('app.api_base_url') . 'addRemark?student_code=' . $code . '&school_code=' . $school_code . '&category_code=' . $category_code . '&title=' . $title . '&text=' . $text);
                if($res->failed())
                    Log::info($res->body());
            }            
        }
        catch(\Exception $e){
            Log::info($e->getMessage());
        }
        return redirect()->route('students');
    }
    public function read()
    {
        $searchTerm = '%' . $this->searchTerm . '%';
        $students = Student::where('name', 'like', $searchTerm)->where('school-code', auth()->user()->school_code)
            ->where('class', $this->class)
            ->where('freezed', 0);
        if(!empty($this->classroom))
            $students = $students->where('classroom', $this->classroom);
        return $students->paginate(10);
    }
    public function render()
    {
        return view('livewire.remark-for-students', [
            'data' => $this->read()
        ]);
    }
}
