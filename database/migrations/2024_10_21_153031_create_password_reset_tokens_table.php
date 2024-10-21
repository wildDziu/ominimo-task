<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email');   // Define email column
            $table->string('token');
            $table->timestamp('created_at')->nullable();

            // Explicitly define email as the primary key
            $table->primary('email');
        });
    }

    public function down()
    {
        Schema::dropIfExists('password_reset_tokens');
    }
};
