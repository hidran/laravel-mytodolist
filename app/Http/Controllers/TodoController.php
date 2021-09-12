<?php

namespace App\Http\Controllers;

use App\Models\Todo;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Contracts\Support\Jsonable;

class TodoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $req)
    {
        $limit = $req->input('per_page') ?? 10;
        $list_id = $req->list_id ?? 1;
        return Todo::select(['id', 'name', 'list_id', 'completed'])
            ->where('list_id', $list_id)
            ->orderBy('id', 'DESC')
            ->paginate($limit);
    }



    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $todo = new Todo();
        $todo->name = $request->name;
        $todo->list_id = $request->list_id;
        $todo->duedate = $request->duedate ?? Carbon::now();
        $res = $todo->save();
        return $this->getResult($todo, $res, 'Todo created');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Todo  $todo
     * @return \Illuminate\Http\Response
     */
    public function show(Todo $todo)
    {
        return $this->getResult($todo, 1, 'Todo read');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Todo  $todo
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Todo $todo)
    {
        $todo->name = $request->name;
        $date = $request->duedate ?? $todo->duedate;
        $todo->duedate = $date ?? Carbon::now();
        $list = $request->list_id ?? $todo->list_id;
        $todo->list_id = $list;
        $todo->completed = $request->completed ?? $todo->completed;
        $res = $todo->save();
        return $this->getResult($todo, $res, 'Todo updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Todo  $todo
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id, Request $request)
    {

        $todo = Todo::whereId($id)->withTrashed()->first();
        $res = !$request->forceDelete ? $todo->delete() : $todo->forceDelete();
        $message = $request->forceDelete ? 'Todo deleted' : 'Todo logically deleted';
        return $this->getResult($todo, $res, $message);
    }
    private function getResult(Jsonable $data, $success = true, $message = '')
    {
        return  [
            'data' => $data,
            'success' => $success,
            'message' => $message
        ];
    }
}
