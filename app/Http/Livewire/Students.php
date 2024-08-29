<?php

namespace App\Http\Livewire;

use App\Models\Student;
use App\Models\User;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithPagination;

class Students extends Component
{
    use WithPagination;
    // 'user_id',
    // 'name',
    // 'gender',
    // 'code',
    // 'school-code',
    // 'class',
    // 'classroom',
    // 'bus-line',
    // 'freezed',

    // public $modalFormVisible = false;
    // public $user;
    // public $name;
    // public $gender;
    // public $code;
    // public $school;
    // public $class;
    // public $classroom;
    // public $bus_line;
    // public $freezed;

    // public function rules(){
    //     return[
    //         'user_id' => 'required',
    //         'name' => ['required', Rule::unique('students', 'name')],
    //         'gender' => 'required',
    //         'code' => 'required',
    //         'school-code' => 'required',
    //         'class' => 'required',
    //         'classroom' => 'required'
    //     ];
    // }

    // public function createShowModal()
    // {
    //     $this->modalFormVisible = true;
    // }

    // public function create()
    // {
    //     $this->validate();
    //     $this->modalFormVisible = false;
    //     $this->user = NULL;
    //     $this->name = NULL;
    //     $this->gender = NULL;
    //     $this->code = NULL;
    //     $this->school = NULL;
    //     $this->class = NULL;
    //     $this->classroom = NULL;
    //     $this->bus_line = NULL;
    //     $this->freezed = NULL;
    // }
    public $searchTerm;
    public $change_password;
    public $change_password_confirmation;
    public $userToChange;
    public $passwordFormVisible = false;

    public function read()
    {
        $searchTerm = '%' . $this->searchTerm . '%';
        if (auth()->user()->is_admin)
            return Student::where('name', 'like', $searchTerm)->paginate(10);
        return Student::where('name', 'like', $searchTerm)->where('school-code', auth()->user()->school_code)->paginate(10);
    }

    public function passwordShowModal($id)
    {
        $this->userToChange = User::find($id);
        $this->passwordFormVisible = true;
    }

    public function changePassword()
    {
        $validated_data = $this->validate([
            'change_password' => 'required',
            'change_password_confirmation' => 'required|same:change_password'
        ]);
        $this->userToChange->password = bcrypt($validated_data['change_password']);
        $this->userToChange->save();
        $this->passwordFormVisible = false;
        $this->change_password = NULL;
        $this->change_password_confirmation = NULL;
    }

    public function render()
    {
        return view('livewire.students', [
            'data' => $this->read()
        ]);
    }

    public function showRemarks($id)
    {
        return redirect()->to('/' . $id . '/remarks');
    }
    public function showPoints($id)
    {
        return redirect()->to('/' . $id . '/points');
    }

    public function freeze($id)
    {
        $current = Student::find($id);
        $current->freezed = !$current->freezed;
        $current->save();
    }
}
