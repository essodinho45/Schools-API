<?php

namespace App\Http\Livewire;

use App\Models\School;
use App\Models\Test;
use Livewire\Component;
use Livewire\WithPagination;

class Dashboard extends Component
{
    use WithPagination;
    public $test_school_id;
    public $test;
    public $subject;
    public $subjects;
    public $active;
    public $date;
    public $min_mark;
    public $testFormVisible = false;
    public function read()
    {
        if (auth()->user()->school_code)
            return School::where("code", auth()->user()->school_code)->paginate(10);
        return School::paginate(10);
    }
    public function testShowModal($id)
    {
        $this->test_school_id = $id;
        $this->test = Test::where('school_id', $id)->with('subjects')->first();
        $this->testFormVisible = true;
    }
    public function render()
    {
        return view('livewire.dashboard', [
            'data' => $this->read()
        ]);
    }
}
