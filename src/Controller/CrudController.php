<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\FlushManager;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Abstract controller providing CRUD functionality for any entity.
 *
 * This class serves as a base controller for entity management, including listing,
 * creating, updating, showing, and deleting entities. Child controllers must implement
 * configuration methods to specify entity-specific details.
 */
abstract class CrudController extends AbstractController
{
    protected ServiceEntityRepositoryInterface $repository;
    protected array $configMain;
    protected array $configIndex;
    protected array $configShow;
    protected array $configNew;
    protected array $configEdit;

    /**
     * Defines the main configuration for the CRUD entity.
     *
     * Expected array structure:
     * - `route_prefix` (string) **[Required]**: The route prefix for this entity's CRUD operations.
     * - `entity_class` (string) **[Required]**: Fully qualified class name of the entity.
     * - `entity_key` (string) **[Required]**: The identifier for the entity, used in the validators translation file.
     * - `main_title` (string) **[Required]**: The main title displayed in CRUD views.
     *
     * @return array configuration for the main entity settings
     */
    abstract protected function setConfigMain(): array;

    /**
     * Defines the configuration for the index (list) page.
     *
     * Expected array structure:
     * - `cols` (array) **[Required]**: Defines columns in the index table.
     *   Each column is an array with:
     *     - `getter` (string) **[Required]**: The entity property to display.
     *     - `label` (string) *(Optional)*: The identifier for the column label, if different from the getter.
     *     - `filters` (string) *(Optional)*: Twig filters applied to the value.
     * - `backlink` (array) *(Optional)*: Configuration for the back button.
     *     - `text` (string): Label of the backlink.
     *     - `route` (string): Symfony route to go back to.
     * - `button` (array) *(Optional)*: Controls visibility of CRUD buttons.
     *     - `new`, `show`, `edit`, `delete` (bool): Whether each button is shown.
     * - `repo_method` (string) *(Optional, Default: `findAll`)*: Repository method for fetching entities.
     *
     * @return array configuration for the index page
     */
    abstract protected function setConfigIndex(): array;

    /**
     * Defines the configuration for the show (details) page.
     *
     * Expected array structure:
     * - `rows` (array) **[Required]**: Defines rows in the details view.
     *   Each row is an array with:
     *     - `getter` (string) **[Required]**: The entity property to display.
     *     - `label` (string) *(Optional)*: The identifier for the column label, if different from the getter.
     *     - `filters` (string) *(Optional)*: Twig filters applied to the value.
     * - `template` (string) *(Optional, Default: `'theme/crud/show.html.twig'`)*: Template for rendering the page.
     * - `button` (array) *(Optional)*: Controls visibility of edit/delete buttons.
     *
     * @return array configuration for the show page
     */
    abstract protected function setConfigShow(): array;

    /**
     * Defines the configuration for the "new" form page.
     *
     * Expected array structure:
     * - `form_class` (string) **[Required]**: Fully qualified class name of the Symfony form.
     * - `template` (string) *(Optional, Default: `'theme/crud/new.html.twig'`)*: Template for rendering the page.
     *
     * @return array configuration for the new form
     */
    abstract protected function setConfigNew(): array;

    /**
     * Defines the configuration for the "edit" form page.
     *
     * Expected array structure:
     * - `form_class` (string) **[Required]**: Fully qualified class name of the Symfony form.
     * - `template` (string) *(Optional, Default: `'theme/crud/edit.html.twig'`)*: Template for rendering the page.
     * - `button` (array) *(Optional)*: Controls visibility of delete button.
     *
     * @return array configuration for the edit form
     */
    abstract protected function setConfigEdit(): array;

    /**
     * Initializes the CRUD controller with entity repository and configurations.
     * MUST be overriden with the entity repository.
     *
     * @param ServiceEntityRepositoryInterface $repository the repository for managing entity data
     */
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
                'backlink' => ['text' => 'form.link.back', 'route' => 'home_index'],
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

    /**
     * Displays a list of entities.
     *
     * @return Response renders the index template with a list of entities
     */
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

    /**
     * Handles the creation of a new entity.
     *
     * This method delegates the creation process to the `edit` method.
     *
     * @param Request      $request the HTTP request instance
     * @param FlushManager $fm      handles entity persistence
     *
     * @return Response redirects to the newly created entity or re-renders the form if invalid
     */
    #[Route('/new', name: 'new', methods: ['GET', 'POST'])]
    public function new(Request $request, FlushManager $fm): Response
    {
        return $this->edit(null, $request, $fm);
    }

    /**
     * Displays details of a single entity.
     *
     * @param int $id the ID of the entity to show
     *
     * @return Response renders the show template with entity details
     *
     * @throws NotFoundHttpException if the entity is not found
     */
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

    /**
     * Handles the creation or modification of an entity.
     *
     * @param int|null     $id      the ID of the entity to edit (null for creation)
     * @param Request      $request the HTTP request instance
     * @param FlushManager $fm      handles entity persistence
     *
     * @return Response redirects to the entity details page on success or re-renders the form if invalid
     *
     * @throws NotFoundHttpException if the entity is not found
     */
    #[Route('/{id}/edit', name: 'edit', methods: ['GET', 'POST'], requirements: ['id' => '\d+'])]
    public function edit(?int $id, Request $request, FlushManager $fm): Response
    {
        // If `$id` is null, creates a new entity. Otherwise, retrieves the existing entity.
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
            $fm->persist($object, ['message' => $isNewObject ? 'form.flash.added' : 'form.flash.updated', 'params' => ['object' => (string) $object]]);

            return $this->redirectToRoute($this->configMain['route_prefix'] . 'show', ['id' => $object->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->render($isNewObject ? $this->configNew['template'] : $this->configEdit['template'], [
            'config_main' => $this->configMain,
            'config_edit' => $this->configEdit,
            'form' => $form,
            'object' => $object,
        ]);
    }

    /**
     * Handles entity deletion.
     *
     * @param int          $id the ID of the entity to delete
     * @param FlushManager $fm handles entity removal
     *
     * @return Response redirects to the index page after deletion
     *
     * @throws NotFoundHttpException if the entity is not found
     */
    #[Route('/{id}/delete', name: 'delete', methods: ['POST'], requirements: ['id' => '\d+'])]
    public function delete(int $id, Request $request, FlushManager $fm): Response
    {
        $token = $request->getPayload()->get('delete_token');
        $tokenId = 'hub/delete-' . $this->configMain['entity_key'];
        if (!$this->isCsrfTokenValid($tokenId, $token)) {
            throw new BadRequestHttpException('Invalid CSRF Token.');
        }

        $object = $this->repository->find($id);
        empty($object) ? throw new NotFoundHttpException($this->configMain['entity_class'] . ' object not found.') : null;

        $fm->remove($object, ['message' => 'form.flash.deleted', 'params' => ['object' => (string) $object]]);

        return $this->redirectToRoute($this->configMain['route_prefix'] . 'index', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * Validates and merges CRUD configurations.
     *
     * Ensures required keys are present and merges defaults where necessary.
     *
     * @param array $config       the provided configuration
     * @param array $requiredKeys the keys that must be present
     * @param array $defaultKeys  the default values to merge
     *
     * @return array the validated configuration
     *
     * @throws \InvalidArgumentException if a required key is missing
     */
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
