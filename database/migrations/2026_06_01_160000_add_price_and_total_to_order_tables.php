<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->decimal('total', 10, 2)->nullable()->after('customer_id');
        });

        Schema::table('order_details', function (Blueprint $table) {
            $table->decimal('price', 10, 2)->nullable()->after('product_id');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('total');
        });

        Schema::table('order_details', function (Blueprint $table) {
            $table->dropColumn('price');
        });
    }
};
