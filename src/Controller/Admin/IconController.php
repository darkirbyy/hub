<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\Icon;
use App\Form\IconType;
use App\Repository\IconRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\ExpressionLanguage\Expression;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsCsrfTokenValid;

#[Route('/admin/icon', name: 'admin_icon_')]
final class IconController extends AbstractController
{
    // List and find all objects
    #[Route('', name: 'index', methods: ['GET'])]
    public function index(IconRepository $objectRepo, Request $request): Response
    {
        $objects = $objectRepo->findAll();

        return $this->render('admin/icon/index.html.twig', [
            'objects' => $objects,
        ]);
    }

    // Edit or add a new object
    #[Route('/new', name: 'new', methods: ['GET', 'POST'])]
    #[Route('/{id}/edit', name: 'edit', methods: ['GET', 'POST'], requirements: ['id' => '\d+'])]
    public function new(?Icon $object, Request $request, EntityManagerInterface $em): Response
    {
        // Handling route: creating new object or updating existing one
        $isNewObject = str_ends_with($request->attributes->get('_route'), 'new');
        if (empty($object)) {
            if ($isNewObject) {
                $object = new Icon();
            } else {
                throw new NotFoundHttpException(Icon::class . ' object not found.');
            }
        }

        // Create the form and fill it with the request data
        $form = $this->createForm(IconType::class, $object);
        $form->handleRequest($request);

        // If the form is submitted and valid : recalculate the fullPrice from the non-mapped typePrice field, then persist and send flash
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($object);
            $em->flush();

            if ($isNewObject) {
                $this->addFlash('success', ['message' => 'form.flash.added']);
            } else {
                $this->addFlash('success', ['message' => 'form.flash.updated']);
            }

            return $this->redirectToRoute('admin_icon_index');
        }

        return $this->render('admin/icon/edit.html.twig', [
            'form' => $form,
        ]);
    }

    // Delete an object if the csrf token is valid
    #[IsCsrfTokenValid(new Expression('"delete-" ~ args["object"].getId()'), tokenKey: 'delete_token')]
    #[Route('/{id}/delete', name: 'delete', methods: ['POST'], requirements: ['id' => '\d+'])]
    public function delete(Icon $object, EntityManagerInterface $em): Response
    {
        $em->remove($object);
        $em->flush();

        $this->addFlash('success', ['message' => 'form.flash.deleted']);

        return $this->redirectToRoute('admin_icon_index');
    }
}
