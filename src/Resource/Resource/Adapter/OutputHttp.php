<?php

    namespace Resource\Adapter;

    use \Slim\Http\Response;
    use Api\Shared\AdapterInterface\Output;

final class OutputHttp implements Output
{
    private $response;

    public function __construct(Response $response)
    {
        $this->response = $response;
    }

    public function success(int $status, array $output = []) : Response
    {
        return $this->response->withStatus($status)
            ->withJson($output);
    }

    public function error(int $status, string $message) : Response
    {
        return $this->response->withStatus($status)
            ->withJson([
                'error_message' => $message,
            ]);
    }
}
