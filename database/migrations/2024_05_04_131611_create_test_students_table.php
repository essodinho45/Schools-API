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
        Schema::create('test_students', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('test_id');
            $table->string('full_name', 255);
            $table->string('father_name', 255);
            $table->string('mother_name', 255);
            $table->string('previous_school', 255);
            $table->string('phone', 10);
            $table->string('whatsapp_mobile', 10);
            $table->timestamps();
            $table->foreign('test_id')->references('id')->on('tests');
            $table->unique(['test_id', 'full_name', 'father_name', 'mother_name'], 'tst_stu_unq');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('test_students');
    }
};
