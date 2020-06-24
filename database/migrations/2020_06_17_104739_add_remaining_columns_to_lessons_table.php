<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRemainingColumnsToLessonsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('lessons', function (Blueprint $table) {
            $table->unsignedInteger('profile_id')->nullable();
            $table->string('status')->nullable();
            $table->text('teacher_log')->nullable();
            $table->text('material_taught')->nullable();
            $table->text('student_report')->nullable();
            $table->date('completed_on')->nullable();
            $table->string('actual_start')->nullable();
            $table->string('actual_end')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('lessons', function (Blueprint $table) {
            //
        });
    }
}
