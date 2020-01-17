<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProfilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('profiles', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('user_id')->unsigned();
            $table->string('name');
            $table->string('slug')->unique();
            $table->json('bio')->nullable();
            $table->string('nationality')->nullable();
            $table->integer('teaching_since')->nullable();
            $table->integer('chinese_ability')->nullable();
            $table->string('qualifications')->nullable();
            $table->string('teaching_specialties')->nullable();
            $table->boolean('is_public')->default(0);
            $table->json('spoken_languages')->nullable();
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
        Schema::dropIfExists('profiles');
    }
}
