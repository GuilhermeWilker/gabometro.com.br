<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('schools', function (Blueprint $table) {
            $table->string('legal_name')->nullable()->after('name');
            $table->string('cnpj', 18)->nullable()->unique()->after('legal_name');
            $table->string('email')->nullable()->after('cnpj');
            $table->string('phone', 20)->nullable()->after('email');
            $table->string('responsible_name')->nullable()->after('phone');
            $table->string('responsible_role')->nullable()->after('responsible_name');
            $table->string('zip_code', 9)->nullable()->after('responsible_role');
            $table->string('street')->nullable()->after('zip_code');
            $table->string('number', 20)->nullable()->after('street');
            $table->string('complement')->nullable()->after('number');
            $table->string('district')->nullable()->after('complement');
            $table->string('city')->nullable()->after('district');
            $table->string('state', 2)->nullable()->after('city');
            $table->string('institution_type')->nullable()->after('state');
            $table->string('logo_path')->nullable()->after('institution_type');
            $table->boolean('is_active')->default(true)->after('logo_path');
        });
    }

    public function down(): void
    {
        Schema::table('schools', function (Blueprint $table) {
            $table->dropColumn([
                'legal_name',
                'cnpj',
                'email',
                'phone',
                'responsible_name',
                'responsible_role',
                'zip_code',
                'street',
                'number',
                'complement',
                'district',
                'city',
                'state',
                'institution_type',
                'logo_path',
                'is_active',
            ]);
        });
    }
};
