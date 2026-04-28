<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMetaStrategyVotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('meta_strategy_votes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('meta_strategy_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->boolean('is_meta')->default(true);
            $table->timestamps();
            
            $table->unique(['meta_strategy_id', 'user_id']); // One vote per user per strategy
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('meta_strategy_votes');
    }
}
