<?php

    namespace Api;

    use \Illuminate\Database\Capsule\Manager as CapsuleManager;
    use \Slim\App;
    use Api\Shared\AdapterInterface\Input;
    use Resource\Adapter\InputHttp as Resource;

final class DependencyInjector
{
    public function inject(App $api) : App
    {
        $container = $api->getContainer();

        $databaseSettings = $container->get('settings')['database'];
        $capsule = new CapsuleManager();
        $capsule->addConnection($databaseSettings, 'default');
        $capsule->setAsGlobal();
        $capsule->bootEloquent();
        $container['database'] = function ($container) use ($capsule) {
            return $capsule;
        };

        $container['Resource'] = function() {
            return new Resource();
        };

        return $api;
    }
}
