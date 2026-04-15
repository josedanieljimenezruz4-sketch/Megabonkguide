<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyBuildsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('build_item');
        Schema::dropIfExists('builds');

        Schema::create('builds', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('character_id');
            // Quitamos la foránea por incompatibilidad de collation en entorno local
            // $table->foreign('character_id')->references('id')->on('items')->cascadeOnDelete();
            
            $table->text('description')->nullable();
            $table->integer('rating')->default(1);
            $table->string('type')->nullable(); // Ej: DPS, Tanque, Healer

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('builds');
    }
}
