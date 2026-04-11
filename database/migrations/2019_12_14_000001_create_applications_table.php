<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('applications', function (Blueprint $table) {
            $table->increments('application_id');

            $table->unsignedInteger('applicant_id');   // FK to students
            $table->unsignedInteger('scholarship_id'); // FK to scholarships

            $table->date('date_applied')->default(DB::raw('CURRENT_DATE'));
            $table->string('status', 20)->default('pending');
            $table->string('remarks', 255)->nullable();

            $table->timestamps();


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


    public function down(): void
    {
        Schema::dropIfExists('applications');
    }
};