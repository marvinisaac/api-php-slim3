<?php

    namespace Api;

    use \Slim\App;
    use Api\Shared\AdapterInterface\Input;
    use Resource\Adapter\InputHttp as Resource;

final class DependencyInjector
{
    public function inject(App $api) : App
    {
        $container = $api->getContainer();

        $container['Resource'] = function() {
            return new Resource();
        };

        return $api;
    }
}
