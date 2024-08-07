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
        Schema::create('activities', function (Blueprint $table) {
            $table->id();
            $table->string('title', 255);
            $table->string('remark', 255);
            $table->string('class', 255);
            $table->string('classroom', 255);
            $table->integer('points')->default(0);
            $table->integer('max')->default(0);
            $table->integer('count')->default(0);
            $table->string('school-code', 255);
            $table->date('end_date');
            $table->timestamps();
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
        Schema::dropIfExists('activities');
    }
};
