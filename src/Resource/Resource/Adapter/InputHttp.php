<?php

    namespace Resource\Adapter;

    use \Slim\Http\Request;
    use \Slim\Http\Response;
    use Api\Shared\AdapterInterface\Input;
    use Resource\Resource;
    use Resource\Adapter\OutputHttp as Output;

final class InputHttp implements Input
{
    public function getAll(Request $request, Response $response, array $args) : Response
    {
        $output = new Output($response);
        $resource = new Resource($output);
        return $resource->getAll();
    }
}
