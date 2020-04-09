<?php

    namespace Api;
    
    use \Slim\App;

final class Router
{
    public static function set(App $api) : App
    {
        $api->get('/debug/', function ($request, $response, $args) {
            return $response->withJson([
                'environment' => $_ENV['PHP_ENVIRONMENT'],
            ]);
        });

        $api->group('/resource/', function (App $api) {
            $api->get('', 'Resource:readAll');
            $api->get('{id:[0-9]+}', 'Resource:readById');
            $api->post('', 'Resource:create');
            $api->patch('{id:[0-9]+}', 'Resource:update');
            $api->delete('{id:[0-9]+}', 'Resource:delete');
        });

        $api->any('/[{path:.*}]', function ($request, $response, $args) {
            return $response->withStatus(404);
        });
        
        return $api;
    }
}
