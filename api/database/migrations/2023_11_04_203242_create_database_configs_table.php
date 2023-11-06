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
        Schema::create('database_configs', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('app_id')->constrained();
            $table->boolean('custom')->default(false);
            $table->enum('driver', ['mysql', 'pgsql', 'sqlite', 'sqlsrv', 'mongodb']);
            $table->string('writer_host');
            $table->string('writer_port');
            $table->string('reader_host');
            $table->string('reader_port');
            $table->string('database');
            $table->bigInteger('created_at')->unsigned();
            $table->bigInteger('updated_at')->unsigned();
            $table->bigInteger('deleted_at')->unsigned()->nullable();
            $table->unique(['driver', 'writer_host', 'writer_port', 'reader_host', 'reader_port', 'database']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('database_configs');
    }
};
