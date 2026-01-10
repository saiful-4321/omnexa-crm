<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('company_settings', function (Blueprint $table) {
            $table->string('logo_height')->nullable()->after('logo_dark_small');
            $table->string('logo_width')->nullable()->after('logo_height');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('company_settings', function (Blueprint $table) {
            $table->dropColumn(['logo_height', 'logo_width']);
        });
    }
};
