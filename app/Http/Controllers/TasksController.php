<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Task; //追加

class TasksController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tasks = [];
        if (\Auth::check()) {
            $user = \Auth::user();
            $tasks = $user->tasks()->orderBy('id', 'desc')->paginate(10);
            //$tasks = \Auth::user()->tasks()...と書くこともできる



            // // タスクの一覧を取得
            // $tasks = Task::orderBy('id', 'desc')->paginate(25);
            

        } 
            // タスク一覧ビューでそれを表示
            return view('welcome', [
                'tasks' => $tasks,
                ]);
        // return redirect('/');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $task = new Task;
        
        //タスク作成ビューを表示
        return view('tasks.create', [
            'task' => $task,
            ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //バリデーション
        $request->validate([
            'status' => 'required|max:10',
            'content' => 'required|max:255',
        ]);
        
        // 認証済みユーザ（閲覧者）の投稿として作成（リクエストされた値をもとに作成）
        $request->user()->tasks()->create([
            'status' => $request->status,
            'content' => $request->content,
        
            
        // //タスクを作成
        // $task = new Task;
        // $task->status = $request->status;
        // $task->content = $request->content;
        // $task->user_id = $request->user_id;
        // $task->save();
            ]);
        
        //トップページへリダイレクトさせる
        return redirect('/');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //idの値でを検索して取得
        $task = \App\Task::findOrFail($id);
        
        if (\Auth::id() === $task->user_id) {
        
        //タスク詳細ビューでそれを表示
        return view('tasks.show', [
            'task' => $task, 
        ]);
        }
        return redirect('/');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //idの値でタスクを検索して取得
        $task = Task::findOrFail($id);
        
        //タスク編集ビューでそれを表示
        return view('tasks.edit', [
            'task' => $task,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //バリデーション
        $request->validate([
            'status' => 'required|max:10',
            'content' => 'required|max:255',
        ]);
        
        
        // //idの値でタスクを検索して取得
        $task = Task::findOrFail($id);
        //タスクを更新
        $task->status = $request->status;
        $task->content = $request->content;
        // $task->user_id = $request->user_id;
        $task->save();
    
        //トップページへリダイレクトさせる
        return redirect('/');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //idの値でタスクを検索して取得
        $task = \App\Task::findOrFail($id);
        
        // // 認証済みユーザ（閲覧者）がその投稿の所有者である場合は、投稿を削除
        if (\Auth::id() === $task->user_id) {
            
        //メッセージを削除
        $task->delete();
        }
        
        //トップページへリダイレクトさせる
        return redirect('/');
        
    }
    }