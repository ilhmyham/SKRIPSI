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
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('roles_role_id')->nullable()->after('id')->constrained('roles', 'role_id')->onDelete('set null');
            $table->renameColumn('password', 'password_2');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->renameColumn('password_2', 'password');
            $table->dropForeign(['roles_role_id']);
            $table->dropColumn('roles_role_id');
        });
    }
};
