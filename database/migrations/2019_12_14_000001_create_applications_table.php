<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('applications', function (Blueprint $table) {
    $table->increments('application_id');

    $table->unsignedInteger('applicant_id');
    $table->unsignedInteger('scholarship_id');

    $table->date('date_applied');
    $table->string('status', 20);
    $table->string('remarks', 255)->nullable();

    $table->dateTime('created_at');
    $table->dateTime('updated_at')->nullable();

    // Foreign Keys
    $table->foreign('applicant_id')
          ->references('student_id')
          ->on('students')
          ->onDelete('cascade');

    $table->foreign('scholarship_id')
          ->references('scholarship_id')
          ->on('scholarships')
          ->onDelete('cascade');
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('personal_access_tokens');
    }
};
