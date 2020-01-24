<?php

namespace App\Http\Controllers;

use JWTAuth;
use App\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    /**
     * @var
     */
    protected $user;

    /**
     * TaskController constructor.
     */
    public function __construct()
    {
        $this->user = JWTAuth::parseToken()->authenticate();
    }


    public function index()
	{
	    $tasks = $this->user->tasks()->get(['id','title', 'description'])->toArray();
	     if (!$tasks) {
	        return response()->json([
	            'success' => false,
	            'message' => 'Sorry, task of  ' . $this->user->name . '['.$this->user->email.'] cannot be found.'
	        ], 400);
	    }
	    return $tasks;
	}

	/**
	 * @param $id
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function show($id)
	{
		//dd($id);
	    $task = $this->user->tasks()->find($id);

	    if (!$task) {
	        return response()->json([
	            'success' => false,
	            'message' => 'Sorry, task with id ' . $id . ' cannot be found.'
	        ], 400);
	    }

	    return $task;
	}


	/**
	 * @param Request $request
	 * @return \Illuminate\Http\JsonResponse
	 * @throws \Illuminate\Validation\ValidationException
	 */
	public function store(Request $request)
	{
		echo "Store Function called";
		die;
		dd($request);
	    $this->validate($request, [
	        'title' => 'required',
	        'description' => 'required',
	    ]);

	    $task = new Task();
	    $task->title = $request->title;
	    $task->description = $request->description;

	    if ($this->user->tasks()->save($task))
	        return response()->json([
	            'success' => true,
	            'task' => $task
	        ]);
	    else
	        return response()->json([
	            'success' => false,
	            'message' => 'Sorry, task could not be added.'
	        ], 500);
	}

	/**
	 * @param Request $request
	 * @param $id
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function update(Request $request, $id)
	{
		echo "Update function get_called_class()";
	    dd($request);

	    $task = $this->user->tasks()->find($id);

	    if (!$task) {
	        return response()->json([
	            'success' => false,
	            'message' => 'Sorry, task with id ' . $id . ' cannot be found.'
	        ], 400);
	    }

	    $updated = $task->fill($request->all())->save();

	    if ($updated) {
	        return response()->json([
	            'success' => true
	        ]);
	    } else {
	        return response()->json([
	            'success' => false,
	            'message' => 'Sorry, task could not be updated.'
	        ], 500);
	    }
	}
	/**
	 * @param $id
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function destroy($id)
	{
	    $task = $this->user->tasks()->find($id);

	    if (!$task) {
	        return response()->json([
	            'success' => false,
	            'message' => 'Sorry, task with id ' . $id . ' cannot be found.'
	        ], 400);
	    }

	    if ($task->delete()) {
	        return response()->json([
	            'success' => true
	        ]);
	    } else {
	        return response()->json([
	            'success' => false,
	            'message' => 'Task could not be deleted.'
	        ], 500);
	    }
	}
}