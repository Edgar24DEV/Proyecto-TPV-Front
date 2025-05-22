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
        // Migración para la tabla empresas
        Schema::create('empresas', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('direccion_fiscal');
            $table->string('CIF');
            $table->string('razon_social');
            $table->string('telefono');
            $table->string('correo');
            $table->string('contrasenya');
            $table->timestamps();
        });

        // Migración para la tabla roles
        Schema::create('rols', function (Blueprint $table) {
            $table->id();
            $table->string('rol');
            $table->boolean('productos');
            $table->boolean('categorias');
            $table->boolean('tpv');
            $table->boolean('usuarios');
            $table->boolean('mesas');
            $table->boolean('restaurante');
            $table->boolean('clientes');
            $table->boolean('empresa');
            $table->boolean('pago');
            $table->foreignId('id_empresa')->constrained('empresas')->onDelete('cascade');
            $table->timestamps();
        });

        // Migración para la tabla categorias
        Schema::create('categorias', function (Blueprint $table) {
            $table->id();
            $table->string('categoria');
            $table->boolean('activo');
            $table->foreignId('id_empresa')->constrained('empresas')->onDelete('cascade');
            $table->timestamps();
        });

        // Migración para la tabla restaurantes
        Schema::create('restaurantes', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('direccion');
            $table->string('telefono', 9);
            $table->string('contrasenya');
            $table->string('direccion_fiscal');
            $table->string('CIF');
            $table->string('razon_social');
            $table->foreignId('id_empresa')->constrained('empresas')->onDelete('cascade');
            $table->timestamps();
        });

        // Migración para la tabla productos
        Schema::create('productos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->double('precio');
            $table->string('imagen');
            $table->boolean('activo');
            $table->double('iva');
            $table->foreignId('id_categoria')->constrained('categorias')->onDelete('cascade');
            $table->foreignId('id_empresa')->constrained('empresas')->onDelete('cascade');
            $table->timestamps();
        });

        // Migración para la tabla clientes
        Schema::create('clientes', function (Blueprint $table) {
            $table->id();
            $table->string('razon_social');
            $table->string('cif');
            $table->string('direccion_fiscal');
            $table->string('correo');
            $table->foreignId('id_empresa')->constrained('empresas')->onDelete('cascade');
            $table->timestamps();
        });

        // Migración para la tabla empleados
        Schema::create('empleados', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->integer('pin');
            $table->foreignId('id_empresa')->constrained('empresas')->onDelete('cascade');
            $table->foreignId('id_rol')->constrained('rols')->onDelete('cascade');
            $table->timestamps();
        });

        // Migración para la tabla emp_rest
        Schema::create('empleado_restaurante', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_empleado')->constrained('empleados')->onDelete('cascade');
            $table->foreignId('id_restaurante')->constrained('restaurantes')->onDelete('cascade');
            $table->timestamps();
        });

        // Migración para la tabla ubicaciones
        Schema::create('ubicaciones', function (Blueprint $table) {
            $table->id();
            $table->string('ubicacion');
            $table->boolean('activo');
            $table->foreignId('id_restaurante')->constrained('restaurantes')->onDelete('cascade');
            $table->timestamps();
        });

        // Migración para la tabla mesas
        Schema::create('mesas', function (Blueprint $table) {
            $table->id();
            $table->string('mesa');
            $table->boolean('activo');
            $table->foreignId('id_ubicacion')->constrained('ubicaciones')->onDelete('cascade');
            $table->timestamps();
        });

        // Migración para la tabla pedidos
        Schema::create('pedidos', function (Blueprint $table) {
            $table->id();
            $table->string('estado');
            $table->dateTime('fecha_inicio');
            $table->dateTime('fecha_fin');
            $table->integer('comensales');
            $table->foreignId('id_mesa')->constrained('mesas')->onDelete('cascade');
            $table->timestamps();
        });

        // Migración para la tabla lineas_pedido
        Schema::create('lineas_pedido', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_pedido')->constrained('pedidos')->onDelete('cascade');
            $table->foreignId('id_producto')->constrained('productos')->onDelete('cascade');
            $table->integer('cantidad');
            $table->double('precio');
            $table->string('nombre');
            $table->string('observaciones');
            $table->string('estado');
            $table->timestamps();
        });

        // Migración para la tabla rest_prod
        Schema::create('restaurante_producto', function (Blueprint $table) {
            $table->id();
            $table->boolean('activo');
            $table->foreignId('id_producto')->constrained('productos')->onDelete('cascade');
            $table->foreignId('id_restaurante')->constrained('restaurantes')->onDelete('cascade');
            $table->timestamps();
        });

        // Migración para la tabla pagos
        Schema::create('pagos', function (Blueprint $table) {
            $table->id();
            $table->string('tipo');
            $table->double('cantidad');
            $table->date('fecha');
            $table->foreignId('id_pedido')->constrained('pedidos');
            $table->foreignId('id_cliente')->constrained('clientes');
            $table->string('razon_social');
            $table->string('CIF');
            $table->string('n_factura');
            $table->string('correo');
            $table->string('direccion_fiscal');
            $table->timestamps();
        });


    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rols');
        Schema::dropIfExists('empresas');
        Schema::dropIfExists('categorias');
        Schema::dropIfExists('restaurantes');
        Schema::dropIfExists('productos');
        Schema::dropIfExists('clientes');
        Schema::dropIfExists('empleados');
        Schema::dropIfExists('emp_rest');
        Schema::dropIfExists('mesas');
        Schema::dropIfExists('pedidos');
        Schema::dropIfExists('lineas_pedido');
        Schema::dropIfExists('rest_prod');
        Schema::dropIfExists('pagos');
        Schema::dropIfExists('ubicaciones');
    }
};
