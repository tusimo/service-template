<?php

declare(strict_types=1);
/**
 * This file is part of API Service.
 *
 * Please follow the code rules : PSR-2
 */
namespace App\Component;

use Hyperf\Utils\Str;
use Hyperf\ConfigApollo\ApolloDriver as ConfigApolloApolloDriver;

class ApolloDriver extends ConfigApolloApolloDriver
{
    protected function formatValue($value)
    {
        if ($this->maybeJson($value)) {
            $json = json_decode($value, true);
            if (json_last_error() == JSON_ERROR_NONE) {
                return $json;
            }
        }
        return parent::formatValue($value);
    }

    protected function maybeJson($value)
    {
        if (! is_string($value)) {
            return false;
        }
        if (Str::start($value, '{') && Str::endsWith($value, '}')) {
            return true;
        }
        if (Str::start($value, '[') && Str::endsWith($value, ']')) {
            return true;
        }
        return false;
    }
}
