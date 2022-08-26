<?php

namespace RecursiveTree\Seat\InfoPlugin\Http\Controllers;

use RecursiveTree\Seat\InfoPlugin\Acl\RoleHelper;
use RecursiveTree\Seat\InfoPlugin\Model\ArticleAclRole;
use RecursiveTree\Seat\InfoPlugin\Model\Article;
use RecursiveTree\Seat\InfoPlugin\Model\Resource;
use RecursiveTree\Seat\InfoPlugin\Model\ResourceAclRole;
use Seat\Web\Http\Controllers\Controller;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Gate;
use Seat\Web\Models\Acl\Role;
use Seat\Web\Models\User;


class InfoController extends Controller
{

    public function deleteArticle(Request $request){
        $request->validate([
            "data"=>"required|integer"
        ]);

        Gate::authorize("info.article.edit", $request->data);

        $article = Article::find($request->data);

        if ($article !== null) {
            Article::destroy($request->data);
            ArticleAclRole::where("article", $request->data)->delete();

            $request->session()->flash('success',  trans("info::info.manage_delete_article_success"));

            return redirect()->route('info.manage');
        } else {
            $request->session()->flash('error', trans("info::info.manage_article_not_found"));

            return redirect()->route('info.manage');
        }
    }

    public function setArticlePublic(Request $request){
        $request->validate([
            "data"=>"required|integer"
        ]);

        $article = Article::find($request->data);

        if ($article !== null) {
            Gate::authorize("info.article.edit", $article->id);

            $article->public = true;
            $article->save();

            $request->session()->flash('success', trans("info::info.manage_publish_article_success"));

            return redirect()->route('info.manage');
        } else {
            $request->session()->flash('error', trans("info::info.manage_article_not_found"));

            return redirect()->route('info.manage');
        }
    }

    public function setArticlePrivate(Request $request){
        $request->validate([
            "data"=>"required|integer"
        ]);

        $article = Article::find($request->data);

        if ($article !== null) {

            Gate::authorize("info.article.edit", $article->id);

            $article->public = false;
            $article->save();

            $request->session()->flash('success', trans("info::info.manage_set_article_private_success"));

            return redirect()->route('info.manage');
        } else {
            $request->session()->flash('error', trans("info::info.manage_article_not_found"));

            return redirect()->route('info.manage');
        }
    }

    public function setArticlePinned(Request $request){
        $request->validate([
           "data" => "required|integer"
        ]);

        $article = Article::find($request->data);

        if($article === null){
            $request->session()->flash('error', trans("info::info.manage_article_not_found"));
            return redirect()->back();
        } else {
            Gate::authorize("info.article.edit", $article->id);

            $article->pinned = true;
            $article->save();

            $request->session()->flash('success',  trans("info::info.manage_pin_article_success"));

            return redirect()->back();
        }
    }

    public function setArticleUnpinned(Request $request){
        $request->validate([
            "data" => "required|integer"
        ]);

        $article = Article::find($request->data);

        if($article === null){
            $request->session()->flash('error', trans("info::info.manage_article_not_found"));
            return redirect()->back();
        } else {
            Gate::authorize("info.article.edit", $article->id);

            $article->pinned = false;
            $article->save();

            $request->session()->flash('success', trans("info::info.manage_unpin_article_success"));

            return redirect()->back();
        }
    }

    public function getCreateView(){
        $article = new Article();

        $editAclRole = new ArticleAclRole();
        $editAclRole->role =  RoleHelper::getDefaultEditRole();
        $editAclRole->allows_edit = true;

        $viewAclRole = new ArticleAclRole();
        $viewAclRole->role =  RoleHelper::getDefaultViewRole();
        $viewAclRole->allows_view = true;

        //fake the relation
        $roles = collect([$editAclRole, $viewAclRole]);

        $other_roles = Role::whereNotIn("id",$roles->pluck("role"))
            ->get()
            ->map(function ($role){
                $aclRole = new ArticleAclRole();
                $aclRole->role = $role->id;
                return $aclRole;
            });

        $roles = $roles->merge($other_roles);

        return view("info::edit", compact('article','roles'));
    }

    public function getSaveInterface(User $user,Request $request){
        $request->validate([
            "id"=>"nullable|integer",
            "name"=>"required|string",
            "text"=>"required|string",
            "public"=>"nullable",
            "aclAccessType"=>"required|array",
            "aclAccessType.*"=>"required|string|in:nothing,edit,view"
        ]);

        $article = Article::find($request->id);

        //if the article exists, check for edit access, otherwise for article creation access
        if($request->id){
            Gate::authorize("info.article.edit", $request->id);
        } else {
            Gate::authorize("info.create_article");
        }

        if ($article===null){
            $article = new Article();
        }

        //add a user for new articles, or when saving old articles
        if ($article->owner === null){
            $article->owner = auth()->user()->id;
        }

        $article->name = $request->name;
        $article->text = $request->text;

        if(Gate::allows("info.make_public")){
            $article->public = isset($request->public);
        }

        if($article->public === null){
            $article->public = false;
        }

        $article->save();

        $article->aclRoles()->delete();

        foreach ($request->aclAccessType as $id=>$value){
            if($value === "nothing") continue;
            $aclRole = new ArticleAclRole();
            $aclRole->article = $article->id;
            if($value==="edit") {
                $aclRole->role = RoleHelper::checkForExistenceOrDefault($id, RoleHelper::getDefaultEditRole());
                $aclRole->allows_edit = true;
            }
            if($value==="view") {
                $aclRole->role = RoleHelper::checkForExistenceOrDefault($id, RoleHelper::getDefaultViewRole());
                $aclRole->allows_view = true;
            }
            $aclRole->save();
        }

        $request->session()->flash('success', trans("info::info.manage_save_article_success"));
        return redirect()->route('info.view',["id"=>$article->id]);
    }

    public function getEditView(Request $request,$id){
        Gate::authorize("info.article.edit", $id);

        $article = Article::find($id);

        if ($article===null){
            $request->session()->flash('error', trans("info::info.manage_article_not_found"));
            return redirect()->route('info.manage');
        }

        $roles = $article->aclRoles;

        $other_roles = Role::whereNotIn("id",$roles->pluck("role"))
            ->get()
            ->map(function ($role){
                $aclRole = new ArticleAclRole();
                $aclRole->role = $role->id;
                return $aclRole;
            });

        $roles = $roles->toBase()->merge($other_roles->toBase());

        return view("info::edit", compact('article', 'roles'));
    }

    public function getListView(){

        $articles = Article::orderBy("pinned","desc")->get()->filter(function ($article){
            return Gate::allows("info.article.view",$article->id);
        });
        return view("info::list", compact('articles'));
    }

    public function getManageView(){
        $articles = Article::all()->filter(function ($article){
            return Gate::allows("info.article.edit",$article->id);
        });

        $resources = Resource::query()->orderBy("owner")->get()->filter(function ($resource){
            return Gate::allows("info.resource.view",$resource->id);
        });

        return view("info::manage", compact('articles','resources'));
    }

    public function getArticleView(Request $request, $article_id_name){
        $article = Article::find($article_id_name);
        if($article === null){
            $article = Article::where("name",$article_id_name)->first();
        }

        if ($article===null){
            $request->session()->flash('error',  trans("info::info.view_article_not_found"));
            return view("info::view");
        }

        Gate::authorize("info.article.view", $article->id);

        $can_edit = Gate::allows("info.article.edit", $article->id);

        return view("info::view", compact('can_edit','article'));
    }

    public function uploadResource(Request $request){
        $request->validate([
            "file"=>"required|file",
            "mime_src_client"=>"nullable"
        ]);

        $file = $request->file;

        if($request->mime_src_client){
            $mime_type = $file->getClientMimeType();
        } else {
            $mime_type = $file->getMimeType();
        }

        $path = $file->store('recursive_tree_info_module_resources');

        $resource = new Resource();
        $resource->mime = $mime_type;
        $resource->path = $path;
        $resource->name = $file->getClientOriginalName();
        $resource->owner = auth()->user()->id;
        $resource->save();

        $request->session()->flash('success', trans("info::info.manage_resource_upload_success"));
        return redirect()->route('info.manage');
    }

    public function viewResource($resource_name_id){
        $db_entry = Resource::find($resource_name_id);

        if($db_entry === null){
            $db_entry = Resource::where("name",$resource_name_id)->first();
        }

        if ($db_entry===null){
            return abort(404);
        }

        Gate::authorize("info.resource.view", $db_entry->id);

        if(!Storage::exists($db_entry->path)){
            abort(500);
        }

        $size = Storage::size($db_entry->path);
        $type = $db_entry->mime;

        return Storage::download($db_entry->path, $db_entry->name, [
            'Content-Type'=> $type,
            'Accept-Ranges'=>'bytes',
            'Content-Length',strlen($size)
        ]);
    }

    public function deleteResource(Request $request){
        $request->validate([
            "data"=>"required|integer",
        ]);

        Gate::authorize("info.resource.edit", $request->data);

        $resource = Resource::find($request->data);
        if ($resource===null){
            $request->session()->flash('error', trans("info::info.resource_not_found"));
            return redirect()->route('info.manage');
        }

        //dd($resource->aclRoles);
        $resource->aclRoles()->delete();

        Storage::delete($resource->path);
        Resource::destroy($request->data);

        $request->session()->flash('success', trans("info::info.manage_resource_delete_success"));
        return redirect()->route('info.manage');
    }

    public function configureResource(Request $request, $id){
        Gate::authorize("info.resource.edit", $id);

        $resource = Resource::find($id);
        if($resource === null){
            $request->session()->flash('error', trans("info::info.resource_not_found"));
            return redirect()->back();
        }

        $roles = $resource->aclRoles;

        $other_roles = Role::whereNotIn("id",$roles->pluck("role"))
            ->get()
            ->map(function ($role){
                $aclRole = new ResourceAclRole();
                $aclRole->role = $role->id;
                return $aclRole;
            });

        $roles = $roles->toBase()->merge($other_roles->toBase());

        return view("info::resource",compact("resource","roles"));
    }

    public function configureResourceSave(Request $request, $id){
        $request->validate([
            "name"=>"required|string",
            "aclAccessType"=>"required|array",
            "aclAccessType.*"=>"required|string|in:nothing,edit,view"
        ]);

        Gate::authorize("info.resource.edit", $id);

        //get resource. otherwise, we can directly abort
        $resource = Resource::find($id);
        if($resource === null){
            $request->session()->flash('error', trans("info::info.resource_not_found"));
            return redirect()->back();
        }

        $resource->name = $request->name;

        $resource->save();

        $resource->aclRoles()->delete();
        foreach ($request->aclAccessType as $id=>$value){
            if($value === "nothing") continue;
            $aclRole = new ResourceAclRole();
            $aclRole->resource = $resource->id;
            if($value==="edit") {
                $aclRole->role = RoleHelper::checkForExistenceOrDefault($id, null);
                $aclRole->allows_edit = true;
            }
            if($value==="view") {
                $aclRole->role = RoleHelper::checkForExistenceOrDefault($id, null);
                $aclRole->allows_view = true;
            }
            $aclRole->save();
        }

        return redirect()->route("info.manage");
    }

    public function about(){

        return view("info::about");
    }
}