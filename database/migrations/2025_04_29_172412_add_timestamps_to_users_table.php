<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTimestampsToUsersTable extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // Check if columns don't exist before adding them
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
        Schema::table('users', function (Blueprint $table) {
            // Only drop columns if they exist
            if (Schema::hasColumn('users', 'created_at')) {
                $table->dropColumn('created_at');
            }
            if (Schema::hasColumn('users', 'updated_at')) {
                $table->dropColumn('updated_at');
            }
        });
    }
}