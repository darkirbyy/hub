<?php

namespace App\Controller\Admin\Status;

use App\Entity\Param\Status;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/status', name: 'admin_status_')]
final class IframeController extends AbstractController
{
    #[Route('/{id}', name: 'show', requirements: ['id' => '\d+'])]
    public function show(Status $status): Response
    {
        return $this->render('admin/status/iframe.html.twig', [
            'status' => $status,
        ]);
    }
}
