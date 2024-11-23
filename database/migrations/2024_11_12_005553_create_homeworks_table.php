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
        Schema::create('homeworks', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->unsignedBigInteger('student_id');
            $table->string('school-code', 255);
            $table->string('student-code', 255);
            $table->string('kh_guid')->unique();
            $table->string('description');
            $table->string('responses')->nullable();
            $table->boolean('can_response')->default(false);
            $table->string('file-path')->nullable()->default(NULL);
            $table->boolean('is-image')->default(false);
            $table->boolean('is-sent');
            $table->boolean('is-read');
            $table->boolean('is-sent-firebase');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('homeworks');
    }
};
