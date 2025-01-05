<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use RecursiveTree\Seat\InfoPlugin\Model\ArticleAccessRole;

return new class extends Migration
{
    public function up()
    {
        Schema::table("recursive_tree_seat_info_articles_acl_roles", function (Blueprint $table){
            $table->index("article");
        });
    }


    public function down()
    {
        Schema::table("recursive_tree_seat_info_articles_acl_roles", function (Blueprint $table){
            $table->dropIndex("recursive_tree_seat_info_articles_acl_roles_article_index");
        });
    }
};

