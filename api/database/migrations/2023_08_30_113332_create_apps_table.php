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
        Schema::create('apps', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('client_id')->constrained();
            $table->integer('application')->default(0);
            $table->string('path')->unique();
            $table->string('subdomain')->unique();
            $table->integer('grpc_port')->unique();
            $table->boolean('use_domain')->default(false);
            $table->string('domain')->nullable();
            $table->boolean('use_s3')->default(false);
            $table->string('aws_region')->nullable();
            $table->string('aws_bucket')->nullable();
            $table->bigInteger('installed_at')->unsigned()->nullable();
            $table->bigInteger('started_at')->unsigned()->nullable();
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
        Schema::dropIfExists('apps');
    }
};
