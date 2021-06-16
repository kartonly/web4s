<?php

declare(strict_types=1);

namespace App\Core\Common\EventSubscriber;

use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\KernelEvents;

class ApiExceptionSubscriber implements EventSubscriberInterface
{
    private const ERROR_MESSAGE = 'Server error. Dont worry we already know about it.';

    private LoggerInterface $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function onKernelException(ExceptionEvent $event): void
    {
        $e = $event->getThrowable();

        if ($e instanceof HttpExceptionInterface) {
            $code    = $e->getStatusCode();
            $message = $e->getMessage();
        } else {
            $code    = Response::HTTP_INTERNAL_SERVER_ERROR;
            $message = self::ERROR_MESSAGE;
        }

        $response = new JsonResponse(['message' => $message, 'code' => $code], $code);

        $response->headers->set('Content-Type', 'application/problem+json');

        $event->setResponse($response);

        $this->logger->critical(
            'App error. Uncaught exception.',
            [
                'message'         => $message,
                'exception'       => $e->getMessage(),
                'exception_class' => get_class($e),
                'exception_stack' => $e->getTraceAsString(),
                'code'            => $code,
            ]
        );
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::EXCEPTION => 'onKernelException',
        ];
    }
}
