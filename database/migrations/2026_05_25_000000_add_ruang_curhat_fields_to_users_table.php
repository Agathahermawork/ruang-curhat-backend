<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table): void {
            $table->string('pangkat')->nullable()->after('password');
            $table->string('nrp')->nullable()->unique()->after('pangkat');
            $table->string('jabatan')->nullable()->after('nrp');
            $table->string('kesatuan')->nullable()->after('jabatan');
            $table->string('telegram')->nullable()->after('kesatuan');
            $table->string('role')->default('user')->after('telegram');
            $table->string('api_token', 80)->nullable()->unique()->after('role');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table): void {
            $table->dropUnique(['nrp']);
            $table->dropUnique(['api_token']);
            $table->dropColumn([
                'pangkat',
                'nrp',
                'jabatan',
                'kesatuan',
                'telegram',
                'role',
                'api_token',
            ]);
        });
    }
};
