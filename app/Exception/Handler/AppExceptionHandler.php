<?php

declare(strict_types=1);
/**
 * This file is part of API Service.
 *
 * Please follow the code rules : PSR-2
 */
namespace App\Exception\Handler;

use Exception;
use Throwable;
use Psr\Log\LoggerInterface;
use Hyperf\HttpServer\Response;
use Hyperf\Di\Annotation\Inject;
use Psr\Http\Message\ResponseInterface;
use Hyperf\Validation\ValidationException;
use App\Exception\Service\ServiceException;
use Tusimo\Resource\Entity\ResourceResponse;
use Hyperf\ExceptionHandler\ExceptionHandler;
use Hyperf\HttpMessage\Exception\HttpException;
use App\Exception\Resource\ResourceNotFoundException;
use Hyperf\HttpServer\Contract\ResponseInterface as ContractResponseInterface;

class AppExceptionHandler extends ExceptionHandler
{
    /**
     * Logger.
     * @Inject
     */
    protected LoggerInterface $logger;

    public function handle(Throwable $throwable, ResponseInterface $response)
    {
        $resp = new Response($response);
        return $this->handleExceptions($throwable, $resp)
            ->attachToResponse($resp);
    }

    public function isValid(Throwable $throwable): bool
    {
        if ($throwable instanceof Exception) {
            return true;
        }
        return false;
    }

    protected function handleExceptions(Throwable $throwable, ContractResponseInterface $response): ResourceResponse
    {
        $this->stopPropagation();
        if ($throwable instanceof ResourceNotFoundException) {
            return response()->notFound();
        }
        if ($throwable instanceof ServiceException) {
            return response()->error($throwable->getMessage(), 400);
        }
        if ($throwable instanceof ValidationException) {
            return $this->handleValidationException($throwable);
        }
        $this->logger->error(
            sprintf(
                '%s[%s] in %s',
                $throwable->getMessage(),
                $throwable->getLine(),
                $throwable->getFile()
            )
        );
        if ($throwable instanceof HttpException) {
            return response()->error($throwable->getMessage())
                ->withStatus($throwable->getStatusCode());
        }
        if (is_production()) {
            return response()->error('Server is error', 500);
        }
        return response()->withMeta('trace', $throwable->getTrace())->error($throwable->getMessage(), 500);
    }

    protected function handleValidationException($throwable): ResourceResponse
    {
        return response()->withMeta('errors', $throwable->validator->errors())
            ->error($throwable->validator->errors()->first(), 422);
    }
}
