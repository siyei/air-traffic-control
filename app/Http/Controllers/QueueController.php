<?php

namespace App\Http\Controllers;

use \App\Models\Queue;
use \App\Models\Aircraft;
use \App\Utils\System;

class QueueController extends Controller
{

    public function __construct()
    {
        //
    }

    public function index()
    {
        /*$resultSet = app('db')->select(
            "SELECT q.id, q.priority, a.type, a.size 
            FROM queues q 
                JOIN acs a ON a.id=q.ac_id 
            ORDER BY q.priority DESC, a.size DESC, q.created_at ASC"
        );*/

        $resultSet = Queue::select(['queues.id', 'queues.priority', 'acs.type', 'acs.size'])
            ->join('acs', 'acs.id', 'queues.ac_id')
            ->orderBy('queues.priority', 'DESC')
            ->orderBy('acs.size', 'DESC')
            ->orderBy('queues.created_at', 'ASC')
            ->get();

        return response()->json([
            "success" => true,
            "data" => $resultSet,
            "message" => 'List has been retrieved!'
        ]);
    }

    public function store()
    {
        if (!System::isOn()) {
            return response()->json([
                "success" => false,
                "message" => "System has to boot up before queueing!"
            ]);
        }

        $size = strtolower(request()->get('size', ''));
        $type = strtolower(request()->get('type', ''));

        if (!in_array($size, ['large', 'small'])) {
            return response()->json([
                "success" => false,
                "message" => "Invalid value for size"
            ]);
        }

        if (!in_array($type, ['emergency', 'vip', 'passenger', 'cargo'])) {
            return response()->json([
                "success" => false,
                "message" => "Invalid value for type"
            ]);
        }
        //app('db')->insert("INSERT INTO acs (size, type) VALUES(?, ?)", [$size, $type]);
        //$acId = app('db')->table('acs')->insertGetId(['size' => $size, 'type' => $type]);

        $aircraft = new Aircraft;
        $aircraft->size = $size;
        $aircraft->type = $type;
        if (!$aircraft->save()) {
            return response()->json([
                "success" => false,
                "message" => "An error occurred while creating the aircraft!"
            ]);
        }

        switch ($type) {
            case 'emergency':
                $priority = 5;
                break;
            case 'vip':
                $priority = 3;
                break;
            case 'passenger':
                $priority = 2;
                break;
            default:
                $priority = 1;
        }

        //priority 4 will be a wildcard, just in case
        //$queueId = app('db')->table('queues')->insertGetId(['ac_id' => $acId, 'priority' => $priority]);
        $queue = new Queue;
        $queue->ac_id = $aircraft->id;
        $queue->priority = $priority;
        if (!$queue->save()) {
            return response()->json([
                "success" => false,
                "message" => "An error occurred while queueing the aircraft!"
            ]);
        }

        return response()->json([
            'success' => true,
            'data' => [],
            "message" => 'AC has entered the queue!'
        ]);
    }

    public function destroy($id)
    {
        if (!System::isOn()) {
            return response()->json([
                "success" => false,
                "message" => "System has to boot up before removing an aircraft from queue!"
            ]);
        }
        $data = ['message' => 'Aircraft has been removed from queue successfully!'];
        $queue = Queue::find($id);
        if (empty($queue)) {
            $data['success'] = false;
            $data['message'] = 'Aircraft not found!';
        } else {
            if ($queue->delete()) {
                $data['success'] = true;
                $queue->aircraft->delete();
            }
        }
        return response()->json($data);
    }
}
