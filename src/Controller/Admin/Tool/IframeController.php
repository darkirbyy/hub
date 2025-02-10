<?php

namespace App\Controller\Admin\Tool;

use App\Entity\Param\Tool;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/tool', name: 'admin_tool_')]
final class IframeController extends AbstractController
{
    #[Route('/{id}', name: 'show', requirements: ['id' => '\d+'])]
    public function show(Tool $tool): Response
    {
        return $this->render('admin/tool/iframe.html.twig', [
            'tool' => $tool,
        ]);
    }
}
