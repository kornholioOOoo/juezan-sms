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
        Schema::create('scholarships', function (Blueprint $table) {
    $table->increments('scholarship_id');

    $table->string('scholarship_name', 100);
    $table->string('provider', 100);
    $table->text('description')->nullable();
    $table->decimal('amount', 10, 2);
    $table->integer('slots')->nullable();
    $table->string('status', 20);

    $table->dateTime('created_at');
    $table->dateTime('updated_at')->nullable();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('password_reset_tokens');
    }
};
