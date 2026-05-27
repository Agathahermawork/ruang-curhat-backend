<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('counselors', function (Blueprint $table): void {
            $table->id();
            $table->string('name');
            $table->string('pangkat');
            $table->string('nrp')->unique();
            $table->string('jabatan')->nullable();
            $table->string('kesatuan')->nullable();
            $table->string('telegram');
            $table->string('religion');
            $table->string('emoji')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('counselors');
    }
};
