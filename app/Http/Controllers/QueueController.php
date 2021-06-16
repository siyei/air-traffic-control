<?php

namespace App\Http\Controllers;

class QueueController extends Controller {

    public function __construct() {
        //    
    }

    public function index(){
        $resultSet = app('db')->select(
            "SELECT q.id, q.priority, a.type, a.size 
            FROM queue q 
                JOIN acs a ON a.id=q.ac_id 
            ORDER BY q.priority DESC, a.size DESC, q.created_at ASC"
        );

        return response()->json([
            'success' => true,
            'data'    => $resultSet,
            "message" => 'List has been retrieved!'
        ]);
    }

    public function store(){
        if( count(app('db')->select("SELECT 1 FROM system")) == 0 ) {
            return response()->json([
                "success" => false,
                "message" => "System has to boot up before queueing!"
            ]);
        }

        $size = strtolower(request()->get('size', ''));
        $type = strtolower(request()->get('type', ''));

        if( !in_array($size, ['large', 'small']) ){
            return response()->json([
                "success" => false,
                "message" => "Invalid value for size"
            ]);
        }
        
        if( !in_array($type, ['emergency', 'vip', 'passenger', 'cargo']) ){
            return response()->json([
                "success" => false,
                "message" => "Invalid value for type"
            ]);
        }
        //app('db')->insert("INSERT INTO acs (size, type) VALUES(?, ?)", [$size, $type]);
        $acId = app('db')->table('acs')->insertGetId(['size' => $size, 'type' => $type]);
    
        switch( $type ) {
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
        $queueId = app('db')->table('queue')->insertGetId(['ac_id' => $acId, 'priority' => $priority]);

        return response()->json([
            'success' => true,
            'data'    => [
                'queueId' => $queueId
            ],
            "message" => 'AC has entered the queue!'
        ]);
    }

    public function destroy($id){
        if( count(app('db')->select("SELECT 1 FROM system")) == 0 ) {
            return response()->json([
                "success" => false,
                "message" => "System has to boot up before dequeueing!"
            ]);
        }
        $data = [ 'message'=>'Aircraft has been dequeed successfully!' ];
        $data['success'] = app('db')->table('queue')->where('id', $id)->delete();
        if( $data['success'] ) {
            $data['success'] = true;
        }else {
            $data['success'] = false;
            $data['message'] ='Queue not found!';
        }
        return response()->json($data);
    }
}
