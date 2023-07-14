<?php

use App\Models\Role;
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
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('role_id')->unique();
            $table->string('role_name')->unique();
            $table->string('description')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        $data =  array(
            [
                'role_id' => '1',
                'role_name' => 'admin',
            ],
            [
                'role_id' => '2',
                'role_name' => 'user',
            ],  
        );
        foreach ($data as $datum){
            $role = new Role();
            $role->role_id = $datum['role_id'];
            $role->role_name = $datum['role_name'];
            $role->save();
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('roles');
    }
};
