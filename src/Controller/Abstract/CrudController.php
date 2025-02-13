<?php

declare(strict_types=1);

namespace App\Controller\Abstract;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\ExpressionLanguage\Expression;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsCsrfTokenValid;

abstract class CrudController extends AbstractController
{
    abstract protected function setConfigMain(): array;

    abstract protected function setConfigIndex(): array;

    abstract protected function setConfigShow(): array;

    protected ServiceEntityRepositoryInterface $repository;
    protected array $configMain;
    protected array $configIndex;
    protected array $configShow;

    public function __construct(ServiceEntityRepositoryInterface $repository)
    {
        $this->repository = $repository;
        $this->validateConfigMain($this->setConfigMain());
        $this->validateConfigIndex($this->setConfigIndex());
        $this->validateConfigShow($this->setConfigShow());
    }

    protected function validateConfigMain(array $configMain): void
    {
        $requiredKeys = ['route_prefix', 'entity_class', 'entity_key', 'form_class', 'main_title'];
        foreach ($requiredKeys as $key) {
            if (!isset($configMain[$key])) {
                throw new \InvalidArgumentException(sprintf('Main configuration key "%s" is missing.', $key));
            }
        }
        $this->configMain = $configMain;
    }

    protected function validateConfigIndex(array $configIndex): void
    {
        $requiredKeys = ['cols'];
        $defaultKeys = [
            'backlink' => [
                'text' => 'form.other.back',
                'route' => 'home_index',
            ],
            'button' => [
                'new' => true,
                'show' => true,
                'edit' => true,
                'delete' => true,
            ],
            'repo_method' => 'findAll',
            'sort' => null,
        ];

        foreach ($requiredKeys as $key) {
            if (!isset($configIndex[$key])) {
                throw new \InvalidArgumentException(sprintf('Index configuration key "%s" is missing.', $key));
            }
        }

        foreach ($configIndex['cols'] as $index => $cell) {
            $this->validateCell($cell);
        }

        $this->configIndex = array_replace_recursive($defaultKeys, $configIndex);
    }

    protected function validateConfigShow(array $configShow): void
    {
        $requiredKeys = ['rows'];
        $defaultKeys = [
            'button' => [
                'edit' => true,
                'delete' => true,
            ],
        ];

        foreach ($requiredKeys as $key) {
            if (!isset($configShow[$key])) {
                throw new \InvalidArgumentException(sprintf('Show configuration key "%s" is missing.', $key));
            }
        }

        foreach ($configShow['rows'] as $index => $cell) {
            $this->validateCell($cell);
        }

        $this->configShow = array_replace_recursive($defaultKeys, $configShow);
    }

    protected function validateCell(array $cell)
    {
        $requiredKeys = ['getter'];

        foreach ($requiredKeys as $key) {
            if (!isset($cell[$key])) {
                throw new \InvalidArgumentException(sprintf('Cell configuration key "%s" is missing.', $key));
            }
        }
    }

    #[Route('', name: 'index', methods: ['GET'])]
    public function index(): Response
    {
        $repoMethod = $this->configIndex['repo_method'];
        $objects = $this->repository->$repoMethod();

        return $this->render('theme/crud/index.html.twig', [
            'config_main' => $this->configMain,
            'config_index' => $this->configIndex,
            'objects' => $objects,
        ]);
    }

    #[Route('/new', name: 'new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        return $this->edit(null, $request, $em);
    }

    #[Route('/{id}', name: 'show', methods: ['GET'], requirements: ['id' => '\d+'])]
    public function show(int $id, Request $request, EntityManagerInterface $em): Response
    {
        $object = $this->repository->find($id);
        empty($object) ? throw new NotFoundHttpException($this->configMain['entity_class'] . ' object not found.') : null;

        return $this->render('theme/crud/show.html.twig', [
            'config_main' => $this->configMain,
            'config_show' => $this->configShow,
            'object' => $object,
        ]);
    }

    #[Route('/{id}/edit', name: 'edit', methods: ['GET', 'POST'], requirements: ['id' => '\d+'])]
    public function edit(?int $id, Request $request, EntityManagerInterface $em): Response
    {
        $isNewObject = empty($id);
        if ($isNewObject) {
            $object = new ($this->configMain['entity_class'])();
        } else {
            $object = $this->repository->find($id);
            empty($object) ? throw new NotFoundHttpException($this->configMain['entity_class'] . ' object not found.') : null;
        }

        $form = $this->createForm($this->configMain['form_class'], $object);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($object);
            $em->flush();

            $this->addFlash('success', ['message' => $isNewObject ? 'form.flash.added' : 'form.flash.updated']);

            return $this->redirectToRoute($this->configMain['route_prefix'] . 'index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render($isNewObject ? 'theme/crud/new.html.twig' : 'theme/crud/edit.html.twig', [
            'config_main' => $this->configMain,
            'form' => $form,
        ]);
    }

    #[IsCsrfTokenValid(new Expression('"delete-" ~ args["id"]'), tokenKey: 'delete_token')]
    #[Route('/{id}/delete', name: 'delete', methods: ['POST'], requirements: ['id' => '\d+'])]
    public function delete(int $id, EntityManagerInterface $em): Response
    {
        $object = $this->repository->find($id);
        empty($object) ? throw new NotFoundHttpException($this->configMain['entity_class'] . ' object not found.') : null;

        $em->remove($object);
        $em->flush();

        $this->addFlash('success', ['message' => 'form.flash.deleted']);

        return $this->redirectToRoute($this->configMain['route_prefix'] . 'index', [], Response::HTTP_SEE_OTHER);
    }
}
