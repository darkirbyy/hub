<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\ErrorHandler\Exception\FlattenException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Log\DebugLoggerInterface;

class ErrorController extends AbstractController
{
    public function show(Request $request, FlattenException $exception, ?DebugLoggerInterface $logger = null): Response
    {
        $statusCode = $exception->getStatusCode();
        $errorKey = match ($statusCode) {
            404 => 'notFound',
            403 => 'forbidden',
            default => 'other',
        };

        $isTurbo = $request->headers->has('Turbo-Frame') || str_contains((string) $request->headers->get('Accept'), 'turbo-stream');

        $response = $this->render('theme/error.html.twig', [
            'error_code' => $statusCode,
            'error_key' => $errorKey,
        ]);

        if ($isTurbo && 422 != $statusCode) {
            $response->headers->set('Turbo-Visit-Control', 'reload');
        }

        return new Response($response->getContent(), $statusCode, $response->headers->all());
    }
}
