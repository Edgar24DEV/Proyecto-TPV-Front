<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('empresas', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('rols', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('categorias', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('restaurantes', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('productos', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('clientes', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('empleados', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('ubicaciones', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('mesas', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('pedidos', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('pagos', function (Blueprint $table) {
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('empresas', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('rols', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('categorias', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('restaurantes', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('productos', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('clientes', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('empleados', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('ubicaciones', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('mesas', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('pedidos', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('pagos', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
};
