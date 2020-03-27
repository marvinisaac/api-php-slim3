<?php

    namespace Resource;

    use Api\Shared\AdapterInterface\Output;

final class Resource
{
    private $output;

    public function __construct(Output $output)
    {
        $this->output = $output;
    }

    public function getAll()
    {
        return $this->output->withJson([
            'hello' => 'world',
        ]);
    }
}
