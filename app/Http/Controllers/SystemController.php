<?php

namespace App\Http\Controllers;

use \App\Utils\System;

class SystemController extends Controller
{

    public function getCurrentStatus()
    {
        if (System::isOn()) {
            $buttonText = 'Stop';
            $message = "running";
        } else {
            $buttonText = 'Start';
            $message = "stopped";
        }

        return response()->json([
            "success" => true,
            "message" => $message,
            "data" => [
                'buttonText' => $buttonText
            ]
        ]);
    }

    public function toogleMachine()
    {
        if (!System::isOn()) {
            System::bootUp();
            $buttonText = 'Stop';
            $message = "running";
        } else {
            System::shutDown();
            $buttonText = 'Start';
            $message = "stoped";
        }

        return response()->json([
            "success" => true,
            "message" => $message,
            "data" => [
                "buttonText" => $buttonText
            ],
        ]);

    }
}
