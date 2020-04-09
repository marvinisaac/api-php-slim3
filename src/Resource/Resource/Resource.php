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

        $missingKeys = $this->checkMissingKeys($input, $requiredKeys);
        if (count($missingKeys) > 0) {
            $message = 'Missing required values: ' . json_encode($missingKeys);
            $status = $this->convertToStatus($message);
            $this->logError($message);
            return $this->output->error($status, $message);
        }

        $result = $this->database->create($input);
        if (!$result['success']) {
            unset($result['success']);
            $message = $result['error_message'];
            $status = $this->convertToStatus($message);
            return $this->output->error($status, $message);
        }

        return $this->output->success(201);
    }

    public function update(int $id, array $input)
    {
        $result = $this->database->readById($id);
        if (!$result['success']) {
            unset($result['success']);
            $message = $result['error_message'];
            $status = $this->convertToStatus($message);
            return $this->output->error($status, $message);
        }

        $result = $this->database->update($id, $input);
        if (!$result['success']) {
            unset($result['success']);
            $message = $result['error_message'];
            $status = $this->convertToStatus($message);
            return $this->output->error($status, $message);
        }

        return $this->output->success(204);
    }

    public function delete(int $id)
    {
        $result = $this->database->delete($id);
        if (!$result['success']) {
            return $this->handleError($result);
        }
        
        return $this->output->success(204);
    }

    private function checkMissingKeys(array $input, array $requiredKeys) : array
    {
        $inputKeys = array_keys($input);
        $missingKeys = array_diff($requiredKeys, $inputKeys);
        if (count($missingKeys) > 0) {
            return $missingKeys;
        }

        $missingKeys = [];
        foreach ($input as $key => $value) {
            if ($value === '') {
                $missingKeys[] = $key;
            }
        }
        return $missingKeys;
    }

    private function convertToStatus(string $errorMessage) : int
    {
        switch ($errorMessage) {
            case 'No record(s) found.':
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

    private function logError(string $errorMessage) : void
    {
        if ($_ENV['PHP_ENVIRONMENT'] !== 'PRODUCTION' &&
            $_ENV['PHP_ENVIRONMENT'] !== 'TEST'
        ) {
            error_log('>>> Debug message: ' . $errorMessage);
        }
    }
}
