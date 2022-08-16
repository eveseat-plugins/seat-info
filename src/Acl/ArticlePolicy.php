<?php

namespace RecursiveTree\Seat\InfoPlugin\Acl;

use RecursiveTree\Seat\InfoPlugin\Model\AclRole;
use RecursiveTree\Seat\InfoPlugin\Model\Article;
use Seat\Web\Acl\Policies\AbstractPolicy;
use Seat\Web\Models\User;

class ArticlePolicy extends AbstractPolicy
{
    public static function view(User $user,$article_id)
    {
        $roles = AclRole::where("article",$article_id)
            ->where("allows_view",true)
            ->pluck("role");

        $article = Article::find($article_id);
        if($article === null){
            return false;
        }

        return
            ($user
                ->roles()
                ->whereIn("id", $roles)
                ->exists()
                && $article->public)
            || $user->isAdmin()
            || $user->can("info.edit_all")
            || self::edit($user,$article_id)
            || $user->id === $article->owner;
    }

    public static function edit(User $user,$article_id)
    {
        $roles = AclRole::where("article",$article_id)
            ->where("allows_edit",true)
            ->pluck("role");

        $user = auth()->user();

        $article = Article::find($article_id);
        if($article === null){
            return false;
        }

        return $user
                ->roles()
                ->whereIn("id", $roles)
                ->exists()
            || $user->can("info.edit_all")
            || $user->isAdmin()
            || $user->id === $article->owner;
    }
}