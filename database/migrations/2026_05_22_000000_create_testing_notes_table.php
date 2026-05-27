<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('testing_notes')) {
            return;
        }

        Schema::create('testing_notes', function (Blueprint $table): void {
            $table->id();
            $table->string('name');
            $table->text('message');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('testing_notes');
    }
};
