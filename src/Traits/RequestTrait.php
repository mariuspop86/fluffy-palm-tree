<?php

namespace App\Traits;

trait RequestTrait
{
    /**
     * @param string $data
     *
     * @return array
     * @throws \Exception
     */
    private function getValidatedRequestContent(string $data): array
    {
        $content = json_decode($data, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \Exception(json_encode('Invalid JSON format: ' . json_last_error_msg()));
        }

        return $content;
    }
}
