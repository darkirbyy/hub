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

    abstract protected function setConfigNew(): array;

    abstract protected function setConfigEdit(): array;

    protected ServiceEntityRepositoryInterface $repository;
    protected array $configMain;
    protected array $configIndex;
    protected array $configShow;
    protected array $configNew;
    protected array $configEdit;

    public function __construct(ServiceEntityRepositoryInterface $repository)
    {
        $this->repository = $repository;
        $this->configMain = $this->validateConfig($this->setConfigMain(), ['route_prefix', 'entity_class', 'entity_key', 'main_title'], []);
        $this->configIndex = $this->validateConfig(
            $this->setConfigIndex(),
            ['cols'],
            [
                'template' => 'theme/crud/index.html.twig',
                'button' => ['new' => true, 'show' => true, 'edit' => true, 'delete' => true],
                'backlink' => ['text' => 'form.other.back', 'route' => 'home_index'],
                'repo_method' => 'findAll',
            ],
        );
        $this->configShow = $this->validateConfig(
            $this->setConfigShow(),
            ['rows'],
            [
                'template' => 'theme/crud/show.html.twig',
                'button' => ['edit' => true, 'delete' => true],
            ],
        );
        $this->configNew = $this->validateConfig(
            $this->setConfigNew(),
            ['form_class'],
            [
                'template' => 'theme/crud/new.html.twig',
            ],
        );
        $this->configEdit = $this->validateConfig(
            $this->setConfigEdit(),
            ['form_class'],
            [
                'template' => 'theme/crud/edit.html.twig',
                'button' => ['delete' => true],
            ],
        );
    }

    #[Route('', name: 'index', methods: ['GET'])]
    public function index(): Response
    {
        $repoMethod = $this->configIndex['repo_method'];
        $objects = $this->repository->$repoMethod();

        return $this->render($this->configIndex['template'], [
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
    public function show(int $id): Response
    {
        $object = $this->repository->find($id);
        empty($object) ? throw new NotFoundHttpException($this->configMain['entity_class'] . ' object not found.') : null;

        return $this->render($this->configShow['template'], [
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

        $form = $this->createForm($isNewObject ? $this->configNew['form_class'] : $this->configEdit['form_class'], $object);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($object);
            $em->flush();

            $this->addFlash('success', ['message' => $isNewObject ? 'form.flash.added' : 'form.flash.updated', 'params' => ['object' => (string) $object]]);

            return $this->redirectToRoute($this->configMain['route_prefix'] . 'index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render($isNewObject ? $this->configNew['template'] : $this->configEdit['template'], [
            'config_main' => $this->configMain,
            'config_edit' => $this->configEdit,
            'form' => $form,
            'object' => $object,
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

        $this->addFlash('success', ['message' => 'form.flash.deleted', 'params' => ['object' => (string) $object]]);

        return $this->redirectToRoute($this->configMain['route_prefix'] . 'index', [], Response::HTTP_SEE_OTHER);
    }

    protected function validateConfig(array $config, array $requiredKeys, array $defaultKeys)
    {
        foreach ($requiredKeys as $key) {
            if (!isset($config[$key])) {
                throw new \InvalidArgumentException(sprintf('Configuration key "%s" is missing.', $key));
            }
            if (in_array($key, ['cols', 'rows'])) {
                foreach ($config[$key] as $cell) {
                    $this->validateConfig($cell, ['getter'], []);
                }
            }
        }

        return array_replace_recursive($defaultKeys, $config);
    }
}
