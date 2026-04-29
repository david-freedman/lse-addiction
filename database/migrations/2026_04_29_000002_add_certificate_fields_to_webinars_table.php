<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('webinars', function (Blueprint $table) {
            $table->string('cert_company_name')->default('Медична академія')->after('sync_to_wp');
            $table->string('cert_signature')->nullable()->after('cert_company_name');
            $table->string('cert_stamp')->nullable()->after('cert_signature');
            $table->unsignedSmallInteger('cert_bpr_hours')->nullable()->after('cert_stamp');
            $table->string('cert_specialties')->nullable()->after('cert_bpr_hours');
            $table->string('cert_participant_type', 20)->nullable()->after('cert_specialties');
        });
    }

    public function down(): void
    {
        Schema::table('webinars', function (Blueprint $table) {
            $table->dropColumn([
                'cert_company_name',
                'cert_signature',
                'cert_stamp',
                'cert_bpr_hours',
                'cert_specialties',
                'cert_participant_type',
            ]);
        });
    }
};
