<?php

namespace App\Http\Livewire;

use App\Models\Activity;
use App\Models\Student;
use Livewire\Component;
use Livewire\WithPagination;

class Activities extends Component
{
    use WithPagination;

    public $title;
    public $remark;
    public $class;
    public $classroom;
    public $points = 0;
    public $max = 0;
    public $end_date;
    public $classes = [];
    public $classrooms = [];
    public $modalFormVisible = false;
    // public $searchTerm;

    public function mount()
    {
        if (!auth()->user()->is_admin && !auth()->user()->school->use_points)
            abort('401', 'Unauthorized');
        $this->classes = Student::where('school-code', auth()->user()->school_code)->select('class')->distinct()->get()->toArray();
        array_unshift($this->classes, ['class' => '']);
        if (count($this->classes)) {
            $this->classrooms = Student::where('school-code', auth()->user()->school_code)->where('class', $this->classes[0])->select('classroom')->distinct()->get()->toArray();
            array_unshift($this->classrooms, ['classroom' => '']);
        }
        if (count($this->classes))
            $this->class = $this->classes[0]['class'];
        if (count($this->classrooms))
            $this->classroom = $this->classrooms[0]['classroom'];
    }
    public function updatedClass()
    {
        $this->classrooms = Student::where('school-code', auth()->user()->school_code)->where('class', $this->class)->select('classroom')->distinct()->get()->toArray();
        array_unshift($this->classrooms, ['classroom' => '']);
        $this->classroom = $this->classrooms[0]['classroom'];
    }
    public function read()
    {
        // $searchTerm = '%' . $this->searchTerm . '%';
        // if ($this->no_token)
        //     return User::where('device_key', NULL)->where('name', 'like', $searchTerm)->where('id', '<>', auth()->user()->id)->paginate(10);
        return Activity::where('school-code', auth()->user()->school_code)->paginate(10);
    }

    public function render()
    {
        return view('livewire.activities', [
            'data' => $this->read()
        ]);
    }

    public function rules()
    {
        return [
            'title' => 'required',
            'remark' => 'required',
            'max' => 'required',
            'points' => 'required',
            'class' => 'sometimes',
            'classroom' => 'sometimes',
            'end_date' => 'required',
        ];
    }

    // public function freeze($id)
    // {
    //     $current = User::find($id);
    //     $current->freezed = !$current->freezed;
    //     $current->save();
    //     $current->tokens()->delete();
    // }

    public function createShowModal()
    {
        $this->modalFormVisible = true;
    }

    // public function passwordShowModal($id)
    // {
    //     $this->userToChange = User::find($id);
    //     $this->passwordFormVisible = true;
    // }

    public function create()
    {
        $validated_data = $this->validate();
        Activity::create([
            'title' => $validated_data['title'],
            'remark' => $validated_data['remark'],
            'max' => $validated_data['max'],
            'points' => $validated_data['points'],
            'class' => $validated_data['class'],
            'classroom' => $validated_data['classroom'],
            'end_date' => $validated_data['end_date'],
            'count' => 0,
            'school-code' => auth()->user()->school_code,
        ]);
        $this->modalFormVisible = false;
        $this->text = NULL;
        $this->remark = NULL;
        $this->max = 0;
        $this->points = 0;
        $this->class = NULL;
        $this->classroom = NULL;
        $this->end_date = NULL;
    }
    public function showStudents($id)
    {
        return redirect()->to('./' . 'activity/' . $id . '/students');
    }
    // public function changePassword()
    // {
    //     $validated_data = $this->validate([
    //         'change_password' => 'required',
    //         'change_password_confirmation' => 'required|same:change_password'
    //     ]);
    //     $this->userToChange->password = bcrypt($validated_data['change_password']);
    //     $this->userToChange->save();
    //     $this->passwordFormVisible = false;
    //     $this->change_password = NULL;
    //     $this->change_password_confirmation = NULL;
    // }
}
