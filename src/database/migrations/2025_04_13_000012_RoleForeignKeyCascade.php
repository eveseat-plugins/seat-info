<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up()
    {
        $allowed_roles = DB::table("roles")->pluck("id");
        DB::table("recursive_tree_seat_info_articles_acl_roles")
            ->whereNotIn("role", $allowed_roles)
            ->delete();

        Schema::table("recursive_tree_seat_info_articles_acl_roles", function (Blueprint $table){
            $table->integer("role")->unsigned()->change();
        });

        Schema::table("recursive_tree_seat_info_articles_acl_roles", function (Blueprint $table){
            $table->foreign("role")->references("id")->on("roles")->onDelete('cascade');
        });
    }


    public function down()
    {
        Schema::table("recursive_tree_seat_info_articles_acl_roles", function (Blueprint $table){
            $table->dropForeign("recursive_tree_seat_info_articles_acl_roles_role_foreign");
            $table->dropIndex("recursive_tree_seat_info_articles_acl_roles_role_foreign");
        });
        Schema::table("recursive_tree_seat_info_articles_acl_roles", function (Blueprint $table){
            $table->bigInteger("role")->change();
        });
    }
};

