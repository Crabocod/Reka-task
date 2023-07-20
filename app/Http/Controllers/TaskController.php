<?php

namespace App\Http\Controllers;

use App\Models\MyList;
use App\Models\Task;
use App\Models\TaskTags;
use App\Models\UserList;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class TaskController extends Controller
{
    public function search(Request $request)
    {
        $tasks_html = "";
        $tasks_modals_html = "";

        $tags = DB::table('task_tags')
            ->leftJoin('tasks', 'task_tags.task_id', '=', 'tasks.id')
            ->where('tasks.list_id', '=', $request->list_id)
            ->select('task_tags.*')
            ->get()
            ->toArray();

        $tasks = DB::table('tasks')
            ->where('list_id', '=', $request->list_id)
            ->where('title', 'like', '%'.trim($request->search_string).'%')
            ->get()
            ->toArray();

        foreach ($tasks as $task){
            $tags_string = '';
            $tags_titles = [];
            $task_tags = [];

            foreach ($tags as $tag){
                if ($tag->task_id == $task->id) {
                    $task_tags[] = $tag;
                    $tags_string .= $tag->title.',';
                    $tags_titles[] = $tag->title;
                }
            }

            if (empty($request->tags) || count(array_intersect($tags_titles, $request->tags)) == count($request->tags)) {
                $tasks_html .= view('templates.tasks', ['task' => $task, 'tags' => $task_tags]);
                $tasks_modals_html .= view('templates.tasks_modals', ['task' => $task, 'tags' => substr($tags_string, 0, -1)]);
            }
        }
        return ['status' => 1, 'tasks' => $tasks_html, 'tasks_modals' => $tasks_modals_html];
    }

    public function delete(Request $request)
    {
        Task::where('id', $request->task_id)->delete();

        return ['status' => 1];
    }

    public function changeStatus(Request $request)
    {
        $task = Task::find($request->task_id);
        $task->status = ($request->status == 'true') ? 1 : 0;
        $task->save();

        return ['status' => 1];
    }

    public function edit(Request $request)
    {
        if (!empty($request->title)){
            $r = $request->all();
            if ($r['task_image'] != 'undefined' && $r['img_delete'] != 'true') {
                $filename = $r['task_image']->getClientOriginalName();
                $r['task_image']->move('img/tasks/origin/', $filename);

                //Создаем миниатюру изображения и сохраняем ее
                $thumbnail = Image::make('img/tasks/origin/' . $filename);
                $thumbnail->fit(150, 150);
                $thumbnail->save('img/tasks/thumbnail/' . $filename);

                $data = [
                    'title' => $r['title'],
                    'img' => 'img/tasks/origin/' . $filename,
                    'img_min' => 'img/tasks/thumbnail/' . $filename,
                ];
            }elseif($r['img_delete'] == 'true'){
                $data = [
                    'title' => $r['title'],
                    'img' => '',
                    'img_min' => '',
                ];
            }else{
                $data = [
                    'title' => $r['title'],
                ];
            }
            Task::where('id', $r['task_id'])->update($data);
            $task = Task::where('id', $r['task_id'])->first();

            TaskTags::where('task_id', $r['task_id'])->delete();

            $tags = explode(',', $r['tags']);
            $tags_data = [];
            foreach ($tags as $tag){
                $tags_data[] = [
                    'task_id' => $task->id,
                    'title' => trim($tag)
                ];
            }

            TaskTags::insert($tags_data);

            $tasks_html = "";
            $tasks_modals_html = "";

            $tags = DB::table('task_tags')
                ->leftJoin('tasks', 'task_tags.task_id', '=', 'tasks.id')
                ->where('tasks.list_id', '=', $task->list_id)
                ->select('task_tags.*')
                ->get()
                ->toArray();

            $tasks = DB::table('tasks')
                ->where('tasks.list_id', '=', $task->list_id)
                ->get()
                ->toArray();

            foreach ($tasks as $task){
                $tags_string = '';
                $task_tags = [];

                foreach ($tags as $tag){
                    if ($tag->task_id == $task->id) {
                        $tags_string .= $tag->title.',';
                        $task_tags[] = $tag;
                    }
                }

                $tasks_html .= view('templates.tasks', ['task' => $task, 'tags' => $task_tags]);
                $tasks_modals_html .= view('templates.tasks_modals', ['task' => $task, 'tags' => substr($tags_string,0,-1)]);
            }

            return ['status' => 1, 'tasks' => $tasks_html, 'tasks_modals' => $tasks_modals_html];
        }else{
            return 0;
        }
    }

    public function create(Request $request)
    {
        if (!empty($request->title)){
            $r = $request->all();
            if ($r['task_image'] != 'undefined') {
                $filename = $r['task_image']->getClientOriginalName();
                $r['task_image']->move('img/tasks/origin/', $filename);

                //Создаем миниатюру изображения и сохраняем ее
                $thumbnail = Image::make('img/tasks/origin/' . $filename);
                $thumbnail->fit(150, 150);
                $thumbnail->save('img/tasks/thumbnail/' . $filename);

                $data = [
                    'list_id' => $r['list_id'],
                    'title' => $r['title'],
                    'img' => 'img/tasks/origin/' . $filename,
                    'img_min' => 'img/tasks/thumbnail/' . $filename,
                ];
            }else{
                $data = [
                    'list_id' => $r['list_id'],
                    'title' => $r['title'],
                ];
            }
            $task = Task::create($data);

            $tags = explode(',', $r['tags']);
            $tags_data = [];
            foreach ($tags as $tag){
                $tags_data[] = [
                    'task_id' => $task->id,
                    'title' => trim($tag)
                ];
            }

            TaskTags::insert($tags_data);

            $tasks_html = "";
            $tasks_modals_html = "";

            $tags = DB::table('task_tags')
                ->leftJoin('tasks', 'task_tags.task_id', '=', 'tasks.id')
                ->where('tasks.list_id', '=', $r['list_id'])
                ->select('task_tags.*')
                ->get()
                ->toArray();

            $tasks = DB::table('tasks')
                ->where('tasks.list_id', '=', $r['list_id'])
                ->get()
                ->toArray();

            foreach ($tasks as $task){
                $tags_string = '';
                $task_tags = [];

                foreach ($tags as $tag){
                    if ($tag->task_id == $task->id) {
                        $tags_string .= $tag->title.',';
                        $task_tags[] = $tag;
                    }
                }

                $tasks_html .= view('templates.tasks', ['task' => $task, 'tags' => $task_tags]);
                $tasks_modals_html .= view('templates.tasks_modals', ['task' => $task, 'tags' => substr($tags_string,0,-1)]);
            }

            return ['status' => 1, 'tasks' => $tasks_html, 'tasks_modals' => $tasks_modals_html];
        }else{
            return 0;
        }
    }
}
