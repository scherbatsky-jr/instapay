<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRolesAndUserRolesTables extends Migration
{
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_roles');
        Schema::dropIfExists('roles');
    }

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('role');
            $table->string('name');
        });

        DB::table('roles')->insert([
            [
                'role' => 'ROLE_ADMIN',
                'name' => 'Admin',
            ],
            [
                'role' => 'ROLE_SUPER_ADMIN',
                'name' => 'Super Admin',
            ],
            [
                'role' => 'ROLE_OPERATOR',
                'name' => 'Operator',
            ],
        ]);

        Schema::create('user_roles', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('role_id');
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();

            $table->foreign('user_id')
                ->references('id')->on('users');

            $table->foreign('role_id')
                ->references('id')->on('roles');
        });
    }
}
