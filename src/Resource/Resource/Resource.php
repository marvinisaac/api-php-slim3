<?php

    namespace Resource;

    use Api\Shared\AdapterInterface\Database;
    use Api\Shared\AdapterInterface\Output;

final class Resource
{
    private $output;
    private $database;

    public function __construct(Database $database, Output $output)
    {
        $this->database = $database;
        $this->output = $output;
    }

    public function getAll()
    {
        $objectAll = $this->database->getAll();
        return $this->output->withJson($objectAll);
    }

    public function getById(int $id)
    {
        $object = $this->database->getById($id);
        if (!is_null($object)) {
            return $this->output->withJson($object);
        }

        return $this->output->notFound();
    }

    public function create(array $input)
    {
        $requiredKeys = [
            'ordinal_position_long',
            'ordinal_position_short',
        ];

        if (!$this->hasRequired($input, $requiredKeys)) {
            return $this->output->invalidUserRequest();
        }

        $success = $this->database->create($input);
        if (!$success) {
            return $this->output->serverError();
        }

        return $this->output->success();
    }

    private function hasRequired(array $input, array $requiredKeys) {
        $hasRequired = true;
        $inputKeys = array_keys($input);
        foreach ($requiredKeys as $key) {
            if (!in_array($key, $inputKeys)) {
                $this->logError('Missing ' . $key);
                $hasRequired = false;
                continue;
            }
            if ($input[$key] === '') {
                $this->logError('Blank ' . $key);
                $hasRequired = false;
            }
        }
        
        return $hasRequired;
    }

    private function logError(string $errorMessage) : void
    {
        if ($_ENV['PHP_ENVIRONMENT'] !== 'PRODUCTION') {
            error_log('>>> Debug message: ' . $errorMessage);
        }
    }
}
