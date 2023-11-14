<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("documents", function (Blueprint $table) {
            $table->id();
            $table->string('path', 1000);
            $table->string('mime');
            $table->boolean('public')->default(false);
        });

        Schema::create('product_images', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('document_id')->nullable();
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('uploaded_by');
            $table->datetime('uploaded_at');
            $table->datetime('deleted_at')->nullable();

            $table->foreign('document_id')
                ->references('id')
                ->on('documents');

            $table->foreign('product_id')
                ->references('id')
                ->on('products');

            $table->foreign('uploaded_by')
                ->references('id')
                ->on('users');
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_images');
        Schema::dropIfExists('documents');
    }
};
