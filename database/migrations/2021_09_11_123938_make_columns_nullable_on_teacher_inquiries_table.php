<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MakeColumnsNullableOnTeacherInquiriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('teacher_inquiries', function (Blueprint $table) {
            $table->string('years_in_taiwan')->nullable()->change();
            $table->string('available_hours_per_week')->nullable()->change();
            $table->string('teaching_experience')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('teacher_inquiries', function (Blueprint $table) {
            //
        });
    }
}
