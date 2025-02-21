<?php

namespace App\Controller\Admin\Account;

use App\Controller\CrudController;
use App\Entity\Account\User;
use App\Form\Account\EditUserType;
use App\Form\Account\NewUserType;
use App\Repository\Account\UserRepository;
use App\Service\FlushManager;
use Hackzilla\PasswordGenerator\Generator\ComputerPasswordGenerator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/account/user', name: 'admin_account_user_')]
class UserController extends CrudController
{
    public function __construct(UserRepository $repository)
    {
        parent::__construct($repository);
    }

    protected function setConfigMain(): array
    {
        return [
            'route_prefix' => 'admin_account_user_',
            'entity_class' => User::class,
            'entity_key' => 'user',
            'main_title' => 'admin.title',
        ];
    }

    protected function setConfigIndex(): array
    {
        return [
            'cols' => [
                // 0 => ['getter' => 'id'],
                1 => ['getter' => 'username'],
                2 => ['getter' => 'self', 'label' => 'imageFile', 'filters' => 'get_image_path("default-avatar.png")|fmt_image_path("app-avatar-img app-avatar-container-xs")|raw'],
                3 => ['getter' => 'metaRole'],
                4 => ['getter' => 'dateLastCo', 'breakpoint' => 'md', 'filters' => 'format_datetime("short", "short")'],
                5 => ['getter' => 'dateUpdate', 'breakpoint' => 'md', 'filters' => 'format_datetime("short", "short")'],
            ],
            'backlink' => [
                'text' => 'admin.link.backToMainPage',
                'route' => 'admin_index',
            ],
            'button' => [
                'add' => true,
                'show' => true,
                'edit' => false,
                'delete' => false,
            ],
            'repo_method' => 'findAndSort',
        ];
    }

    protected function setConfigShow(): array
    {
        return [
            'rows' => [
                0 => ['getter' => 'id'],
                1 => ['getter' => 'username'],
                2 => ['getter' => 'password', 'filters' => 'fmt_password'],
                3 => ['getter' => 'metaAdmin', 'filters' => 'fmt_bool'],
                4 => ['getter' => 'metaRole'],
                5 => ['getter' => 'dateAdd', 'filters' => 'format_datetime("medium", "medium")'],
                6 => ['getter' => 'dateUpdate', 'filters' => 'format_datetime("medium", "medium")'],
                7 => ['getter' => 'dateLastCo', 'filters' => 'format_datetime("medium", "medium")'],
                8 => ['getter' => 'self', 'label' => 'imageFile', 'filters' => 'get_image_path("default-avatar.png")|fmt_image_path("app-avatar-img app-avatar-container-lg")|raw'],
                9 => ['getter' => 'imageMeta', 'filters' => 'fmt_image_meta'],
            ],
            'template' => 'admin/user_show.html.twig',
            'button' => ['delete' => false, 'edit' => false],
        ];
    }

    protected function setConfigNew(): array
    {
        return [
            'form_class' => NewUserType::class,
        ];
    }

    protected function setConfigEdit(): array
    {
        return [
            'form_class' => EditUserType::class,
            'button' => ['delete' => false],
        ];
    }

    #[Route('/new', name: 'new', methods: ['GET', 'POST'])]
    public function new(Request $request, FlushManager $fm, ?UserPasswordHasherInterface $userPasswordHasher = null): Response
    {
        $user = new User();
        $form = $this->createForm($this->configNew['form_class'], $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // generate a random temporary password and encode it
            $plainPassword = $this->generatePassword();
            $user->setPassword($userPasswordHasher->hashPassword($user, $plainPassword));

            $fm->persist($user, ['message' => 'admin.flash.userAdded', 'params' => ['username' => $user->getUsername(), 'password' => $plainPassword]]);

            return $this->redirectToRoute('admin_account_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render($this->configNew['template'], [
            'config_main' => $this->configMain,
            'form' => $form,
            'object' => $user,
        ]);
    }

    #[Route('/{id}/reset', name: 'reset', methods: ['GET'], requirements: ['id' => '\d+'])]
    public function reset(User $user, UserPasswordHasherInterface $userPasswordHasher, FlushManager $fm): Response
    {
        // generate a random temporary password and encode it
        $plainPassword = $this->generatePassword();
        $user->setPassword($userPasswordHasher->hashPassword($user, $plainPassword));

        $fm->persist($user, ['message' => 'admin.flash.userReset', 'params' => ['username' => $user->getUsername(), 'password' => $plainPassword]]);

        return $this->redirectToRoute('admin_account_user_show', ['id' => $user->getId()], Response::HTTP_SEE_OTHER);
    }

    private function generatePassword()
    {
        $generator = new ComputerPasswordGenerator();
        $generator->setUppercase()->setLowercase()->setNumbers()->setSymbols()->setLength(20);
        $plainPassword = $generator->generatePassword();

        return $plainPassword;
    }
}
