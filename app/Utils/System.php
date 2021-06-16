<?php

namespace App\Utils;

class System
{
    public static function isOn()
    {
        return (bool)count(app('db')->select("SELECT 1 FROM system"));
    }

    public static function bootUp()
    {
        return app('db')->table('system')->insert([
            "status" => "on",
            "created_at" => \Carbon\Carbon::now(),
            "updated_at" => \Carbon\Carbon::now()
        ]);
    }

    public static function shutDown()
    {
        return app('db')->table('system')->truncate();
    }
}
