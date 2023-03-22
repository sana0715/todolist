<?php

namespace App\Http\Controllers;

// クラスのインポート
use Illuminate\Http\Request;
use App\Models\Folder;
use App\Http\Requests\CreateFolder; //バリデーション機能を有効にする
use Illuminate\Support\Facades\Auth; // Authクラスをインポートする

class FolderController extends Controller
{
    public function showCreateForm()
    {
        return view('folders/create');
    }

    // 引数にインポートしたRequestクラスを受け入れる
    public function create(CreateFolder $request)
    {
        // フォルダモデルのインスタンスを作成する
        $folder = new Folder();
        // タイトルに入力値を代入する
        $folder->title = $request->title;

        // ユーザーに紐づけて保存
        Auth::user()->folders()->save($folder);
        
        // // インスタンスの状態をデータベースに書き込む
        // $folder->save();
        
        return redirect()->route('tasks.index', [
            'folder' => $folder->id,
        ]);
    }
}
