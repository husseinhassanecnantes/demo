<?php

namespace App\EventListener;


use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\Validator\Exception\ValidationFailedException;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\HttpKernel\Exception\HttpException;

class ValidationExceptionListener
{
    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function __invoke(ExceptionEvent $event)
    {
        $exception = $event->getThrowable();
        $prevException = $exception->getPrevious();

        if ($exception instanceof HttpException && $prevException instanceof ValidationFailedException)
        {
            $this->logger->info('ValidationExceptionListener called');
            $violations = $prevException->getViolations();
            $errorMessages = $this->formatViolations($violations);

            $response = new JsonResponse(['errors' => $errorMessages], JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
            $event->setResponse($response);
            return;
        }

        // Vérifiez si l'exception est une HttpExceptionInterface ou ses sous-classes (comme NotFoundHttpException)
        if ($exception instanceof HttpExceptionInterface) {

            $this->logger->info('ValidationExceptionListener called for HttpExceptionInterface');

            $statusCode = $exception->getStatusCode();
            $message = $exception->getMessage();

            $response = new JsonResponse([
                'error' => [
                    'message' => $message,
                    'type' => get_class($exception)
                ]
            ], $statusCode);

            $event->setResponse($response);
            return;
        }

        // Pour les autres types d'exception, définissez un code de statut 500 (erreur serveur interne)
        $this->logger->info('ValidationExceptionListener called for general exception');

        $response = new JsonResponse([
            'error' => [
                'message' => $exception->getMessage(),
                'type' => get_class($exception)
            ]
        ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);

        $event->setResponse($response);
    }

    private function formatViolations(ConstraintViolationListInterface $violations): array
    {
        $errorMessages = [];
        foreach ($violations as $violation) {
            $errorMessages[] = [
                'field' => $violation->getPropertyPath(),
                'message' => $violation->getMessage()
            ];
        }
        return $errorMessages;
    }
}

