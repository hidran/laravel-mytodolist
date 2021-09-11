<?php

namespace App\Http\Controllers;

use App\Models\TodoList;
use Illuminate\Http\Request;

use Illuminate\Contracts\Support\Jsonable;

class TodoListController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $req)
    {
        $limit = $req->input('per_page') ?? 10;
        return TodoList::select(['id', 'name', 'user_id'])->orderBy('id', 'DESC')
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

        $list = new TodoList();
        $list->name = $request->name;
        //TODO Reads user id from Session
        $list->user_id = 1;
        $res =  $list->save();
        return $this->getResult($list, $res, 'List created');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\TodoList  $list
     * @return \Illuminate\Http\Response
     */
    public function show(TodoList $list)
    {
        return $this->getResult($list, 1, '');
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\TodoList  $list
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, TodoList $list)
    {
        $list->name = $request->name;
        $res =  $list->save();
        return $this->getResult($list, $res, 'List updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\TodoList  $list
     * @return \Illuminate\Http\Response
     */
    public function destroy(TodoList $list, Request $req)
    {
        if ($req->forceDelete) {
            return $this->forceDestroy($list);
        }
        $res = $list->delete();
        return $this->getResult($list, $res, 'List logically deleteds');
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\TodoList  $list
     * @return \Illuminate\Http\Response
     */
    private function forceDestroy(TodoList $list)
    {
        $res = $list->forceDelete();
        return $this->getResult($list, $res, 'List deleted');
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
