<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRelationshipsMigration extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table("transactions", function(Blueprint $table) {

            $table->foreign("transaction_plan_id")
                ->references("id")
                ->on("transaction_plans")
                ->onDelete("cascade")
                ->onUpdate("cascade");

            $table->foreign("transaction_type_id")
                ->references("id")
                ->on("transaction_types")
                ->onDelete("cascade")
                ->onUpdate("cascade");
        });

        Schema::table("crypto_accounts", function(Blueprint $table) {

            $table->foreign("user_id")
                ->references("id")
                ->on("users")
                ->onDelete("cascade")
                ->onUpdate("cascade");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
