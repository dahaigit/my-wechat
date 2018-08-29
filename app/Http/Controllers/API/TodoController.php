<?php

namespace App\Http\Controllers\API;

use App\Models\Todo;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TodoController extends ApiController
{
    /**
     * @return \Illuminate\Http\JsonResponse
     * @author luwei
     * @date ${YEAR}-${MONTH}-${DAY} ${TIME}
     */
    public function index()
    {
        $todos = Todo::latest()->paginate(15);
        $rows = [];
        foreach ($todos as $todo) {
            $rows[] = [
                'todo_id' => $todo->id,
                'title' => $todo->title,
                'status' => $todo->status,
            ];
        }
        $data = [
            'rows' => $rows,
            'current_page' => $todos->currentPage(),
            'total' => $todos->total(),
            'page_count' => $todos->perPage(),
        ];
        return $this->response('list ok', $data);
    }

    public function show($id)
    {
        $todo = Todo::findOrFail($id);
        $data = [
            'todo_id' => $todo->id,
            'title' => $todo->title,
            'status' => $todo->status,
        ];
        return $this->response('show ok', $data);
    }

    public function store(Request $request)
    {
        $todo = Todo::create([
            'title' => $request->titie,
            'status' => 0,
        ]);
        $data = [
            'todo_id' => $todo->id,
            'title' => $todo->title,
            'status' => $todo->status,
        ];
        return $this->response('save ok', $data);
    }

    public function destroy($id)
    {
        Todo::findOrFail($id)->delete();
        return $this->response('delete ok');
    }


}
