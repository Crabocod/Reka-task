<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\MyList;
use App\Models\UserList;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ListController extends Controller
{
    public function index(){

        if (Auth::check()) {
            $lists_html = "";
            $lists_modals_html = "";
            $lists = DB::table('my_lists')
                ->leftJoin('user_lists', 'my_lists.id', '=', 'user_lists.list_id')
                ->where('user_lists.user_id', '=', Auth::user()->id)
                ->select('my_lists.*')
                ->get()
                ->toArray();

            $users = User::where('id', '<>', Auth::user()->id)
                ->get()
                ->toArray();
            $perms = UserList::where('user_id', '<>', Auth::user()->id)
                ->get()
                ->toArray();

            foreach ($users as &$user){
                foreach ($perms as $perm){
                    if ($perm['user_id'] == $user['id'])
                        $user['list_ids'][] = $perm['list_id'];
                }
            }

            foreach ($lists as $list) {
                $lists_html .= view('templates.lists', (array)$list);
                $lists_modals_html .= view('templates.lists_modals', ['list' => $list, 'users' => $users]);
            }

            return view('index', ['lists' => $lists_html, 'lists_modals' => $lists_modals_html]);
        }

        return view('index');
    }

    public function delete(Request $request)
    {
        MyList::where('id', $request->list_id)->delete();

        return ['status' => 1];
    }

    public function detail($id)
    {
        if (DB::table('my_lists')
            ->leftJoin('user_lists', 'my_lists.id', '=', 'user_lists.list_id')
            ->where('user_lists.user_id', '=', Auth::user()->id)
            ->where('my_lists.id', '=', $id)
            ->exists())
        {
            $list = DB::table('my_lists')
                ->leftJoin('user_lists', 'my_lists.id', '=', 'user_lists.list_id')
                ->where('user_lists.user_id', '=', Auth::user()->id)
                ->where('my_lists.id', '=', $id)
                ->select('my_lists.*')
                ->first();

            $tasks_html = "";
            $tasks_modals_html = "";

            $tags = DB::table('task_tags')
                ->leftJoin('tasks', 'task_tags.task_id', '=', 'tasks.id')
                ->where('tasks.list_id', '=', $id)
                ->select('task_tags.*')
                ->get()
                ->toArray();

            $tasks = DB::table('tasks')
                ->where('tasks.list_id', '=', $id)
                ->get()
                ->toArray();

            foreach ($tasks as $task){
                $tags_string = '';
                $task_tags = [];

                foreach ($tags as $tag){
                    if ($tag->task_id == $task->id) {
                        $task_tags[] = $tag;
                        $tags_string .= $tag->title.',';
                    }
                }

                $tasks_html .= view('templates.tasks', ['task' => $task, 'tags' => $task_tags]);
                $tasks_modals_html .= view('templates.tasks_modals', ['task' => $task, 'tags' => substr($tags_string,0,-1)]);
            }
            $tag_titles = [];
            foreach ($tags as $tag){
                if (!in_array($tag->title, $tag_titles))
                    $tag_titles[] = $tag->title;
            }

            return view('list_detail', ['list' => $list, 'tasks' => $tasks_html, 'tasks_modals' => $tasks_modals_html, 'tags' => $tag_titles]);

        }else{
            return redirect()->route('index');
        }


    }

    public function edit(Request $request)
    {
        if (!empty($request->title)){

            MyList::where('id', $request->list_id)->update([
                'title' => $request->title
            ]);

            UserList::where('user_id', '<>', Auth::user()->id)
                ->where('list_id', '=', $request->list_id)
                ->delete();


            if (!empty($request->perms)) {
                $perm_data = [];
                foreach ($request->perms as $perm) {
                    $perm_data[] = [
                        'user_id' => $perm,
                        'list_id' => $request->list_id
                    ];
                }
                UserList::insert($perm_data);
            }

            $lists_html = "";
            $lists_modals_html = "";


            $lists = DB::table('my_lists')
                ->leftJoin('user_lists', 'my_lists.id', '=', 'user_lists.list_id')
                ->where('user_lists.user_id', '=', Auth::user()->id)
                ->select('my_lists.*')
                ->get()
                ->toArray();

            $users = User::where('id', '<>', Auth::user()->id)->get()->toArray();
            $perms = UserList::where('user_id', '<>', Auth::user()->id)
                ->get()
                ->toArray();

            foreach ($users as &$user){
                foreach ($perms as $perm){
                    if ($perm['user_id'] == $user['id'])
                        $user['list_ids'][] = $perm['list_id'];
                }
            }
            foreach ($lists as $list){
                $lists_html .= view('templates.lists', (array)$list);
                $lists_modals_html .= view('templates.lists_modals', ['list' => $list, 'users' => $users]);
            }

            return ['status' => 1, 'lists' => $lists_html, 'lists_modals' => $lists_modals_html];
        }else{
            return 0;
        }
    }

    public function create(Request $request)
    {
        if (!empty($request->title)){
            $list = MyList::create([
                'title' => $request->title,
            ]);

            UserList::create([
                'user_id' => Auth::user()->id,
                'list_id' => $list->id,
            ]);

            $lists_html = "";
            $lists_modals_html = "";


            $lists = DB::table('my_lists')
                ->leftJoin('user_lists', 'my_lists.id', '=', 'user_lists.list_id')
                ->where('user_lists.user_id', '=', Auth::user()->id)
                ->select('my_lists.*')
                ->get()
                ->toArray();

            $users = User::where('id', '<>', Auth::user()->id)->get()->toArray();
            $perms = UserList::where('user_id', '<>', Auth::user()->id)
                ->get()
                ->toArray();

            foreach ($users as &$user){
                foreach ($perms as $perm){
                    if ($perm['user_id'] == $user['id'])
                        $user['list_ids'][] = $perm['list_id'];
                }
            }
            foreach ($lists as $list){
                $lists_html .= view('templates.lists', (array)$list);
                $lists_modals_html .= view('templates.lists_modals', ['list' => $list, 'users' => $users]);
            }

            return ['status' => 1, 'lists' => $lists_html, 'lists_modals' => $lists_modals_html];
        }else{
            return 0;
        }
    }
}
