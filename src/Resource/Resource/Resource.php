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

    public function readAll()
    {
        $result = $this->database->readAll();
        if (!$result['success']) {
            return $this->handleError($result);
        }
        return $this->output->success(200, $result['result']);
    }

    public function readById(int $id)
    {
        $result = $this->database->readById($id);
        if (!$result['success']) {
            return $this->handleError($result);
        }
        
        return $this->output->success(200, $result['result']);
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

        return $this->output->createSuccess();
    }

    public function update(int $id, array $input)
    {
        $object = $this->database->readById($id);
        if (is_null($object)) {
            return $this->output->notFound();
        }

        $result = $this->database->update($id, $input);
        if (!$result['success']) {
            unset($result['success']);
            return $this->output->invalidUserRequest($result);
        }

        return $this->output->updateSuccess();
    }

    private function convertToStatus(string $errorMessage) : int
    {
        switch($errorMessage) {
            case 'No records found.':
                return 404;
            case 'Database error.':
                return 500;
        }

        if (stripos($errorMessage, 'Missing required values:') !== false) {
            return 400;
        }
    }

    private function handleError(array $result)
    {
        unset($result['success']);
        $message = $result['error_message'];
        $status = $this->convertToStatus($message);
        return $this->output->error($status, $message);
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
