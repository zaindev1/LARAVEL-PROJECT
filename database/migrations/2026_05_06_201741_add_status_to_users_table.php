<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations (Jab hum 'php artisan migrate' chalate hain)
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Email ke baad 'status' ka column add kar do
            $table->string('status')->default('Online')->after('email');
        });
    }

    /**
     * Reverse the migrations (Jab hum 'php artisan migrate:rollback' karte hain)
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Agar wapas jana ho to 'status' ka column khatam kar do
            $table->dropColumn('status');
        });
    }
};