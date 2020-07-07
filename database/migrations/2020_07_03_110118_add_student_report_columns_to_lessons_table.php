<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStudentReportColumnsToLessonsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('lessons', function (Blueprint $table) {
            $table->string('student_interaction')->nullable();
        });

        Schema::table('lessons', function (Blueprint $table) {
            $table->string('student_comprehension')->nullable();
        });

        Schema::table('lessons', function (Blueprint $table) {
            $table->string('student_confidence')->nullable();
        });

        Schema::table('lessons', function (Blueprint $table) {
            $table->string('student_output')->nullable();
        });

        Schema::table('lessons', function (Blueprint $table) {
            $table->dropColumn(['student_report']);
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
            $table->text('student_report')->nullable();
            $table->dropColumn([
                'student_interaction',
                'student_comprehension',
                'student_confidence',
                'student_output',
            ]);
        });
    }
}
