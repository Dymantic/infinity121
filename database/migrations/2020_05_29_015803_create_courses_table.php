<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCoursesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('customer_id');
            $table->unsignedInteger('subject_id');
            $table->unsignedInteger('area_id')->nullable();
            $table->unsignedInteger('profile_id')->nullable();
            $table->tinyInteger('total_lessons');
            $table->json('students');
            $table->date('starts_from');
            $table->string('address')->nullable();
            $table->string('map_link')->nullable();
            $table->text('location_notes')->nullable();
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
        Schema::dropIfExists('courses');
    }
}
