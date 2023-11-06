<?php

namespace App\Utils;

class Docker
{
    public static function environment(array $env)
    {
        $envs = [];
        foreach ($env as $key => $value) {
            $envs[] = $key . '=' . $value;
        }
        return $envs;
    }

    public static function healtCheck(string $command, int $interval = 15, int $timeout = 5, int $retries = 5)
    {
        $healthcheck = [
            'test' => explode(' ', $command),
            'interval' => $interval . 's',
            'timeout' => $timeout . 's',
            'retries' => $retries,
        ];

        return $healthcheck;
    }

    public static function service($name, $image, array $env = null, array $ports = null, $healthcheck = null, array $command = null)
    {
        $service = [
            "container_name" => $name,
            "image" => $image,

        ];

        if ($env) {
            $service['environment'] = $env;
        }

        if ($command) {
            $service['command'] = $command;
        }

        if ($healthcheck) {
            $service['healthcheck'] = $healthcheck;
        }

        if ($ports) {
            $service['ports'] = $ports;
        }
        return $service;
    }

    public static function services(array $services)
    {
        $services = [
            'version' => '3.8',
            'services' => $services
        ];
        return $services;
    }
}
