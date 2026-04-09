<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('students', function (Blueprint $table) {
    $table->increments('student_id');

    $table->unsignedInteger('user_id');

    $table->string('first_name', 50);
    $table->string('middle_name', 50);
    $table->string('last_name', 50);

    $table->string('course', 50);
    $table->tinyInteger('year_level');

    $table->string('contact_no', 20)->nullable();
    $table->string('address', 255)->nullable();

    $table->dateTime('created_at');
    $table->dateTime('updated_at')->nullable();


    $table->foreign('user_id')
          ->references('user_id')
          ->on('users')
          ->onDelete('cascade');
});
    }


    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
