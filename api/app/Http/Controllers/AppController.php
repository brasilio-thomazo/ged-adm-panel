<?php

namespace App\Http\Controllers;

use App\Models\App;
use App\Utils\Kubernetes;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Symfony\Component\Yaml\Yaml;
use App\Http\Requests\StoreAppRequest;
use App\Http\Requests\UpdateAppRequest;

class AppController extends Controller
{
    public function index()
    {
        $builder = App::with(['client', 'databaseConfig', 'cacheConfig']);
        return response($builder->get());
    }

    private function generatePort(): int
    {
        [$min, $max] = explode('-', config('app.kubernetes.node_ports', '30000-32767'));
        $ports = App::get(['grpc_port'])->toArray();
        do {
            $port = rand($min, $max);
        } while (in_array($port, $ports));
        return $port;
    }

    public function store(StoreAppRequest $request)
    {
        $domain = config('app.domain_name');
        $nodePort = $this->generatePort();
        $useDomain = $request->get('use_domain', false);
        $useS3 = $request->get('use_s3', false);
        $save = [
            'client_id' => $request->get('client_id'),
            'application' => $request->get('application'),
            'path' => $request->get('path'),
            'subdomain' => $request->get('path') . '.' . $domain,
            'grpc_port' =>  $nodePort,
            'use_domain' => $useDomain,
            'domain' => $useDomain ? $request->get('domain') : null,
            'use_s3' => $useS3,
            'aws_region' => $useS3 ? $request->get('aws_region') : config('filesystems.disks.s3.region'),
            'aws_bucket' => $useS3 ? $request->get('aws_bucket') : config('filesystems.disks.s3.bucket'),
        ];
        $app = App::create($save);
        $app->client;
        $app->databaseConfig;
        $app->cacheConfig;
        return response($app, 201);
    }

    public function show(App $app)
    {
        $app->client;
        $app->databaseConfig;
        $app->cacheConfig;
        return response($app);
    }

    public function update(UpdateAppRequest $request, App $app)
    {
        $useDomain = $request->get('use_domain', false);
        $useS3 = $request->get('use_s3', false);
        $save = [
            'use_domain' => $useDomain,
            'domain' => $useDomain ? $request->get('domain') : null,
            'use_s3' => $useS3,
            'aws_region' => $useS3 ? $request->get('aws_region') : config('filesystems.disks.s3.region'),
            'aws_bucket' => $useS3 ? $request->get('aws_bucket') : config('filesystems.disks.s3.bucket'),
        ];
        $app->update($save);
        $app->client;
        $app->databaseConfig;
        $app->cacheConfig;
        return response($app, 200);
    }

    public function destroy(App $app)
    {
        $app->delete();
        return response('', 204);
    }

    public function subscribe(App $app, Request $request): Response
    {
        $update = [];
        if (!$app->installed_at) {
            $update['installed_at'] = now()->timestamp;
        }
        $update['started_at'] = now()->timestamp;
        $app->update($update);
        return response($app);
    }

    public function kubernetes(App $app, Request $request): Response
    {
        $yaml = $this->makeKubernetes($app, $request);
        return response($yaml, 200, ['Content-Type' => 'text/plain']);
    }

    public function makeEnvironments(App $app): array
    {
        return [
            'APP_NAME' => $app->path,
            'APP_ENV' => 'production',
            'APP_PATH' => $app->path,
            'LOG_CHANNEL' => 'stdout',
            'LOG_LEVEL' => 'debug',
            'BROADCAST_DRIVER' => 'log',
            'DB_CONNECTION' => $app->databaseConfig->driver,
            'DB_DATABASE' => $app->databaseConfig->database,
            'DB_WRITER_HOST' => $app->databaseConfig->writer_host,
            'DB_WRITER_PORT' => $app->databaseConfig->writer_port,
            'DB_READER_HOST' => $app->databaseConfig->reader_host,
            'DB_READER_PORT' => $app->databaseConfig->reader_port,
            'SESSION_DRIVER' => $app->cacheConfig->driver,
            'CACHE_DRIVER' => $app->cacheConfig->driver,
            'REDIS_HOST' => $app->cacheConfig->host,
            'REDIS_PORT' => $app->cacheConfig->port,
            'FILESYSTEM_DISK' => 'local',
            'AWS_REGION' => $app->aws_region,
            'AWS_BUCKET' => $app->aws_bucket,
        ];
    }

    public function makeSecrets(App $app, Request $request): array
    {
        $key = sprintf('base64:%s', base64_encode(openssl_random_pseudo_bytes(32)));
        $useCustom = $request->get('use_custom', false);
        $useAwsS3 = $request->get('use_aws_s3', false);
        $databaseDriver = $app->databaseConfig->driver;
        $section = "database.connections.{$databaseDriver}";

        return [
            'APP_KEY' => $key,
            'PASSWORD_SYSTEM' => config('app.passwords.system'),
            'PASSWORD_ADMIN' => config('app.passwords.admin'),
            'PASSWORD_USER' => config('app.passwords.user'),
            'DB_SUPER_USERNAME' => $useCustom ? $request->super_username : config($section . ".super.username"),
            'DB_SUPER_PASSWORD' => $useCustom ? $request->super_password : config($section . ".super.password"),
            'DB_USERNAME' => $useCustom ? $request->db_username : config($section . ".username"),
            'DB_PASSWORD' => $useCustom ? $request->db_password : config($section . ".password"),
            'REDIS_USERNAME' => $useCustom ? $request->cache_username : config("database.redis.cache.username"),
            'REDIS_PASSWORD' => $useCustom ? $request->cache_password : config("database.redis.cache.password"),
            'AWS_ACCESS_KEY_ID' => $useAwsS3 ? $request->aws_key : config('filesystems.disks.s3.key'),
            'AWS_SECRET_ACCESS_KEY' => $useAwsS3 ? $request->aws_secret : config('filesystems.disks.s3.secret'),
            'URL_SUBSCRIBE' => $app->url_subscribe,
        ];
    }

    public function makeKubernetes(App $app, Request $request)
    {
        $name = "ged-client-" . $app->path;
        $fpmName = "ged-client-fpm-" . $app->path;
        $grpcName = "ged-client-grpc-" . $app->path;
        $nginxName = "ged-client-nginx-" . $app->path;

        $config_map = Kubernetes::configMap($name, $this->makeEnvironments($app));
        $nginxConfigMap = Kubernetes::configMap($nginxName, [
            'FPM_HOST' => 'ged-client-fpm-' . $app->path . ':9000',
            'VITE_API_URL' => '/api',
        ]);
        $secret = Kubernetes::secret($name, $this->makeSecrets($app, $request));

        $mount = [
            [
                'name' => 'storage',
                'path' => '/home/app/public_html/storage/app'
            ]
        ];

        $volume = [
            Kubernetes::volume('storage', config('app.kubernetes.persistent_volume_clain'))
        ];

        $fpmContainer = Kubernetes::container($fpmName, "devoptimus/ged-client-fpm", [9000], $mount, [$name], [$name]);
        $fpmService = Kubernetes::service($fpmName, [['port' => 9000, 'targetPort' => 9000]]);
        $nginxContainer = Kubernetes::container($nginxName, "devoptimus/ged-client-nginx", [80], null, [$nginxName]);
        $nginxService = Kubernetes::service($nginxName, [['port' => 80, 'targetPort' => 80]], "LoadBalancer");
        $grpcContainer = Kubernetes::container($grpcName, "devoptimus/ged-grpc-server", [50051], $mount, [$name], [$name]);
        $grpcService = Kubernetes::service($grpcName, [['port' => 50051, 'targetPort' => 50051, 'nodePort' => $app->grpc_port]], "NodePort");
        $sidecarContainer = Kubernetes::initContainer($fpmName . '-sidecar', "devoptimus/ged-client-cli", ["php"], ["artisan", "app:install"], [$name], [$name], $mount);


        $fpm = Kubernetes::deployment($fpmName, [$fpmContainer], $volume, [$sidecarContainer]);
        $nginx = Kubernetes::deployment($nginxName, [$nginxContainer]);
        $grpc = Kubernetes::deployment($grpcName, [$grpcContainer], $volume);

        $arr = [$config_map, $nginxConfigMap, $secret, $fpm, $fpmService, $nginx, $nginxService, $grpc, $grpcService];
        $all = [];
        foreach ($arr as $data) {
            $all[] = Yaml::dump($data, 10, 2, Yaml::DUMP_NUMERIC_KEY_AS_STRING);
        }
        $yaml = implode("\n---\n\n", $all);
        return $yaml;
    }
}
