<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up()
    {
        Schema::table("recursive_tree_seat_info_articles", function (Blueprint $table){
            $table->integer("order")->unsigned();
            $table->index("order");
        });

        // keep the old order
        DB::table("recursive_tree_seat_info_articles")->update(['order'=>DB::raw("id")]);
    }


    public function down()
    {
        Schema::table("recursive_tree_seat_info_articles", function (Blueprint $table){
            $table->dropIndex("recursive_tree_seat_info_articles_order_index");
            $table->dropColumn("order");
        });
    }
};

