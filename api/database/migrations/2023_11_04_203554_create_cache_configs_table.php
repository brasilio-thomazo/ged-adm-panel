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
        Schema::create('cache_configs', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('app_id')->constrained();
            $table->boolean('custom')->default(false);
            $table->enum('driver', ['redis', 'memcached', 'file'])->default('file');
            $table->string('host')->nullable();
            $table->integer('port')->nullable();
            $table->bigInteger('created_at')->unsigned();
            $table->bigInteger('updated_at')->unsigned();
            $table->bigInteger('deleted_at')->unsigned()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cache_configs');
    }
};
