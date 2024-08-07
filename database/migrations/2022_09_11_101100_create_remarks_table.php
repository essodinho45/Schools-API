<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('remarks', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->unsignedBigInteger('remark_category_id');
            $table->unsignedBigInteger('student_id');
            $table->string('school-code', 255);
            $table->string('student-code', 255);
            $table->string('title', 255);
            $table->string('text', 512);
            $table->boolean('is-sent');
            $table->boolean('is-read');
            $table->timestamps();

            $table->foreign('remark_category_id')->references('id')->on('remarks_categories');
            $table->foreign('student_id')->references('id')->on('students');
            $table->foreign('school-code')->references('code')->on('schools');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('remarks');
    }
};
