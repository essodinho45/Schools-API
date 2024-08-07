<?php

namespace App\Http\Livewire;

use App\Models\User;
use App\Models\School;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithPagination;

class Users extends Component
{
    use WithPagination;

    public $name;
    public $email;
    public $password;
    public $password_confirmation;
    public $change_password;
    public $change_password_confirmation;
    public $is_admin = false;
    public $no_token = false;
    public $school;
    public $modalFormVisible = false;
    public $passwordFormVisible = false;
    public $userToChange;
    public $searchTerm;

    public function read()
    {
        $searchTerm = '%'.$this->searchTerm.'%';
        if($this->no_token)
            Return User::where('device_key', NULL)->where('name', 'like', $searchTerm)->where('id', '<>', auth()->user()->id)->paginate(10);
        Return User::where('name', 'like', $searchTerm)->where('id', '<>', auth()->user()->id)->paginate(10);
    }

    public function render()
    {
        return view('livewire.users', [
            'data' => $this->read()
        ])->withSchools(School::all());
    }

    public function rules()
    {
            return[
                'name' => ['required', Rule::unique('users', 'name')],
                'email' => 'required|email|unique:users',
                'password' => 'required|min:8',
                'password_confirmation' => 'required|same:password'
            ];
    }

    public function freeze($id)
    {
        $current = User::find($id);
        $current->freezed = !$current->freezed;
        $current->save();
        $current->tokens()->delete();
    }

    public function createShowModal()
    {
        $this->modalFormVisible = true;
    }

    public function passwordShowModal($id)
    {
        $this->userToChange = User::find($id);
        $this->passwordFormVisible = true;
    }

    public function create()
    {
        $validated_data = $this->validate();
        User::create([
            'name' => $validated_data['name'],
            'email' => $validated_data['email'],
            'password' => bcrypt($validated_data['password']),
            'is_admin' => $this->is_admin,
            'school_code' => $this->school,
        ]);
        $this->modalFormVisible = false;
        $this->email = NULL;
        $this->name = NULL;
        $this->password = NULL;
        $this->password_confirmation = NULL;
        $this->is_admin = false;
        $this->school_code = NULL;
    }

    public function changePassword(){
        $validated_data = $this->validate([
            'change_password' => 'required',
            'change_password_confirmation' => 'required|same:change_password']);
            $this->userToChange->password = bcrypt($validated_data['change_password']);
            $this->userToChange->save();
            $this->passwordFormVisible = false;
            $this->change_password = NULL;
            $this->change_password_confirmation = NULL;
    }
}
