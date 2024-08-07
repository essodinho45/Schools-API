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
        Schema::table('student_points', function (Blueprint $table) {
            $table->string('kh_guid')->nullable()->default(NULL)->unique()->after('id');
            $table->boolean('d1')->default(false)->after('points');
            $table->boolean('d2')->default(false)->after('d1');
            $table->boolean('is_sent')->default(false)->after('d2');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('student_points', function (Blueprint $table) {
            //
        });
    }
};
