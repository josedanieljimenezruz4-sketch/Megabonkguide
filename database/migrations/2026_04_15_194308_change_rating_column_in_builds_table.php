<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // En MySQL directo para evitar requerir doctrine/dbal
        DB::statement('ALTER TABLE builds MODIFY rating DECIMAL(3,1) DEFAULT 1.0');
    }

    public function down()
    {
        DB::statement('ALTER TABLE builds MODIFY rating INT DEFAULT 1');
    }
};
