<?php
    namespace Api;

final class Setting
{
    public static function get() : array
    {
        $environment = $_ENV['PHP_ENVIRONMENT'] ?? null;
        $isDisplayErrorDetails = false;
        if ($environment === 'LOCAL' || $environment === 'TEST') {
            $isDisplayErrorDetails = true;
        }

        return [
            'settings' => [
                'displayErrorDetails' => $isDisplayErrorDetails,
            ],
        ];
    }
}
