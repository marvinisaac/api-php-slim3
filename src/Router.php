<?php

    namespace Api;
    
    use \Slim\App;

final class Router
{
    public static function set(App $api) : App
    {
        $api->get('/debug/', function($request, $response, $args) {
            return $response->withJson([
                'environment' => $_ENV['PHP_ENVIRONMENT'],
            ]);
        });

        $api->get('/resource/', 'Resource:getAll');

        $api->any('/[{path:.*}]', function($request, $response, $args) {
            return $response->withStatus(404);
        });
        
        return $api;
    }
}
