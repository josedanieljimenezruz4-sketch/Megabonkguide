<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTierSuggestionsTableAndDropOld extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('item_user_votes');
        Schema::dropIfExists('user_rank_votes');

        Schema::create('tier_suggestions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('item_id'); // Referencia al string ID de la tabla items (ej. pj-001)
            $table->enum('suggested_tier', ['S', 'A', 'B', 'C', 'D', 'E', 'F']);
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->timestamps();

            // Clave foránea manual para item_id ya que no es bigInteger
            $table->foreign('item_id')->references('id')->on('items')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tier_suggestions');
        
        // Recrear tablas antiguas en caso de rollback
        Schema::create('item_user_votes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('item_id');
            $table->foreign('item_id')->references('id')->on('items')->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('user_rank_votes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('item_id');
            $table->foreign('item_id')->references('id')->on('items')->onDelete('cascade');
            $table->enum('suggested_rank', ['S', 'A', 'B', 'C', 'D', 'E', 'F']);
            $table->timestamps();
        });
    }
}
