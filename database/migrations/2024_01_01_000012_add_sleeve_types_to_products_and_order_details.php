<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->text('sleeve_types')->nullable()->after('sablon_types');
        });

        Schema::table('order_details', function (Blueprint $table) {
            $table->string('sleeve_type')->nullable()->after('size');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('sleeve_types');
        });

        Schema::table('order_details', function (Blueprint $table) {
            $table->dropColumn('sleeve_type');
        });
    }
};
