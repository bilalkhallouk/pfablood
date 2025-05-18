<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class FixUsersTableColumns extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'created_at')) {
                $table->timestamp('created_at')->nullable();
            }
            if (!Schema::hasColumn('users', 'updated_at')) {
                $table->timestamp('updated_at')->nullable();
            }
        });
    }

    public function down()
    {
        // No need to drop columns in this case
    }
}