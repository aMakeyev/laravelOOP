<?php

use Illuminate\Database\Migrations\Migration;

class BaseMigration extends Migration
{
    protected $table;

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists($this->table);
    }
}
