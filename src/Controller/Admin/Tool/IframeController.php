<?php

namespace App\Controller\Admin\Tool;

use App\Entity\Param\Tool;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;

#[Route('/admin/tool', name: 'admin_tool_')]
final class IframeController extends AbstractController
{
    #[Route('/{id}', name: 'show', requirements: ['id' => '\d+'])]
    public function show(Tool $tool, HttpClientInterface $client): Response
    {
        // Make the call to the URL
        $response = $client->request('GET', $tool->getUrl(), [
            'max_duration' => 3,
            'headers' => [],
        ]);

        // Error if the status code is not 200
        $content = 200 == $response->getStatusCode() ? $response->getContent() : '';

        return $this->render('admin/tool/iframe.html.twig', [
            'tool' => $tool,
            'content' => $content,
        ]);
    }
}
