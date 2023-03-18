<?php

namespace App\Http\Controllers;

use App\Models\Folder;
use App\Models\Task; //★追加
use Illuminate\Http\Request;
use App\Http\Requests\CreateTask;
use App\Http\Requests\EditTask;
use Illuminate\Support\Facades\Auth; // Authクラスをインポートする

class TaskController extends Controller
{
    //
    public function index(int $id)
    {
        // ユーザーのフォルダを取得する
        $folders = Auth::user()->folders()->get();

        // すべてのフォルダを取得する
        $folders = Folder::all();

        // 選ばれたフォルダを取得する
        $current_folder = Folder::find($id);

        // 選ばれたフォルダに紐づくタスクを取得する
        // $tasks = Task::where('folder_id', $current_folder->id)->get();
        $tasks = $current_folder->tasks()->get(); 


        return view('tasks/index', [
            'folders' => $folders,
            'current_folder_id' => $current_folder->id,
            'tasks' => $tasks,
        ]);
        // return "Hello world";
    }

    /**
     * GET /folders/{id}/tasks/create
     */
    public function showCreateForm(int $id)
    {
        return view('tasks/create', [
            'folder_id' => $id
        ]);
    }

    public function create(int $id, CreateTask $request)
    {
        $current_folder = Folder::find($id);
    
        $task = new Task();
        $task->title = $request->title;
        $task->due_date = $request->due_date;
    
        $current_folder->tasks()->save($task);
    
        return redirect()->route('tasks.index', [
            'id' => $current_folder->id,
        ]);
    }

    /**
     * GET /folders/{id}/tasks/{task_id}/edit
     */
    public function showEditForm(int $id, int $task_id)
    {
        $task = Task::find($task_id);
    
        return view('tasks/edit', [
            'task' => $task,
        ]);
    }

    public function edit(int $id, int $task_id, EditTask $request)
    {
        // 1 まずリクエストされた ID でタスクデータを取得。これが編集対象となる。
        $task = Task::find($task_id);
    
        // 2 編集対象のタスクデータに入力値を詰めて save する。
        $task->title = $request->title;
        $task->status = $request->status;
        $task->due_date = $request->due_date;
        $task->save();
    
        // 3 最後に編集対象のタスクが属するタスク一覧画面へリダイレクトする
        return redirect()->route('tasks.index', [
            'id' => $task->folder_id,
        ]);
    }
}

