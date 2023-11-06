<?php

namespace App\Utils;

class Kubernetes
{
    public static function configMap(string $name, array $data)
    {
        $configMap = [
            'apiVersion' => 'v1',
            'kind' => 'ConfigMap',
            'metadata' => [
                'name' => $name,
            ],
            'data' => $data
        ];
        foreach ($configMap['data'] as $key => $value) {
            $value = is_int($value) ? strval($value) : $value;
            $configMap['data'][$key] = $value == null ? "" : $value;
        }
        return $configMap;
    }

    public static function secret(string $name, array $data)
    {
        $secret = [
            'apiVersion' => 'v1',
            'kind' => 'Secret',
            'metadata' => [
                'name' => $name,
            ],
            'data' => $data
        ];
        foreach ($secret['data'] as $key => $value) {
            $secret['data'][$key] = base64_encode($value ? $value : "null");
        }
        return $secret;
    }

    public static function container(string $name, string $image, array $ports, $mounts = null, $config_maps = null, $secrets = null)
    {
        $container = [
            'name' => $name,
            'image' => $image,
            'imagePullPolicy' => 'Always',
        ];

        foreach ($ports as $port) {
            $container['ports'][]['containerPort'] = $port;
        }

        if ($mounts) {
            foreach ($mounts as $i => $mount) {
                $container['volumeMounts'][$i]['name'] = $mount['name'];
                $container['volumeMounts'][$i]['mountPath'] = $mount['path'];
            }
        }

        if ($config_maps) {
            foreach ($config_maps as $config_map) {
                $container['envFrom'][]['configMapRef']['name'] = $config_map;
            }
        }

        if ($secrets) {
            foreach ($secrets as $secret) {
                $container['envFrom'][]['secretRef']['name'] = $secret;
            }
        }
        return $container;
    }

    public static function initContainer(string $name, string $image, array $commands, array $args, array $configMaps = null, array $secrets = null, $mounts = null)
    {
        $container = [
            'name' => $name,
            'image' => $image,
            'imagePullPolicy' => 'Always',
            'command' => $commands,
            'args' => $args
        ];

        if ($configMaps) {
            foreach ($configMaps as $configMap) {
                $container['envFrom'][]['configMapRef']['name'] = $configMap;
            }
        }

        if ($secrets) {
            foreach ($secrets as $secret) {
                $container['envFrom'][]['secretRef']['name'] = $secret;
            }
        }

        if ($mounts) {
            foreach ($mounts as $i => $mount) {
                $container['volumeMounts'][$i]['name'] = $mount['name'];
                $container['volumeMounts'][$i]['mountPath'] = $mount['path'];
            }
        }
        return $container;
    }

    public static function volume(string $name, string $clainName)
    {
        return [
            'name' => $name,
            'persistentVolumeClaim' => [
                'claimName' => $clainName
            ]
        ];
    }

    public static function deployment(string $name, array $containers, array $volumes = null, array $sidecars = null)
    {
        $deployment = [
            'apiVersion' => 'apps/v1',
            'kind' => 'Deployment',
            'metadata' => [
                'name' => $name,
            ],
            'spec' => [
                'selector' => [
                    'matchLabels' => [
                        'app' => $name
                    ]
                ],
                'template' => [
                    'metadata' => [
                        'labels' => [
                            'app' => $name
                        ]
                    ],
                    'spec' => [
                        'containers' => $containers,
                    ]
                ]
            ]
        ];

        if ($sidecars) {
            $deployment['spec']['template']['spec']['initContainers'] = $sidecars;
        }

        if ($volumes) {
            $deployment['spec']['template']['spec']['volumes'] = $volumes;
        }
        return $deployment;
    }

    public static function service(string $name, array $ports, $type = 'ClusterIP')
    {
        $service = [
            'apiVersion' => 'v1',
            'kind' => 'Service',
            'metadata' => [
                'name' => $name,
            ],
            'spec' => [
                'type' => $type,
                'selector' => [
                    'app' => $name
                ],
            ]
        ];

        foreach ($ports as $i => $port) {
            $service['spec']['ports'][$i] = [
                'port' => $port['port'],
                'targetPort' => $port['targetPort'],
            ];
            if ($type == 'NodePort') {
                $service['spec']['ports'][$i]['nodePort'] = $port['nodePort'];
            }
        }
        return $service;
    }
}
