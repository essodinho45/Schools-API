<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('test_student_subjects', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('test_student_id');
            $table->unsignedBigInteger('subject_id');
            $table->float('mark');
            $table->float('full_mark');
            $table->timestamps();
            $table->foreign('test_student_id')->references('id')->on('test_students');
            $table->foreign('subject_id')->references('id')->on('subjects');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('test_student_subjects');
    }
};
