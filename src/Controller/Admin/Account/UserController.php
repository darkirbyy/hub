<?php

namespace App\Controller\Admin\Account;

use App\Entity\Account\User;
use App\Form\Account\EditUserType;
use App\Form\Account\NewUserType;
use App\Repository\Account\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Hackzilla\PasswordGenerator\Generator\ComputerPasswordGenerator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\ExpressionLanguage\Expression;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsCsrfTokenValid;

#[Route('/admin/account/user', name: 'admin_account_user_')]
class UserController extends AbstractController
{
    #[Route('', name: 'index', methods: ['GET'])]
    public function index(UserRepository $userRepo): Response
    {
        $users = $userRepo->findAll();

        return $this->render('admin/account/user/index.html.twig', [
            'users' => $users,
            'cols' => [
                1 => ['getter' => 'id'],
                2 => ['getter' => 'username'],
                3 => ['getter' => 'dateLastCo'],
                // 3 => ['getter' => 'imageInfos', 'breakpoint' => 'md'],
            ],
        ]);
    }

    #[Route('/new', name: 'new', methods: ['GET', 'POST'])]
    public function new(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $em): Response
    {
        $user = new User();
        $form = $this->createForm(NewUserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // generate a random temporary password and encode it
            $plainPassword = $this->generatePassword();
            $user->setPassword($userPasswordHasher->hashPassword($user, $plainPassword));

            $em->persist($user);
            $em->flush();

            // do anything else you need here, like send an email
            $this->addFlash('success', ['message' => 'admin.user.flash.added', 'params' => ['username' => $user->getUsername(), 'password' => $plainPassword]]);

            return $this->redirectToRoute('admin_account_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/account/user/new.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'show', methods: ['GET'], requirements: ['id' => '\d+'])]
    public function show(User $user): Response
    {
        return $this->render('admin/account/user/show.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/{id}/edit', name: 'edit', methods: ['GET', 'POST'], requirements: ['id' => '\d+'])]
    public function edit(User $user, Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(EditUserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($user);
            $em->flush();

            // do anything else you need here, like send an email
            $this->addFlash('success', ['message' => 'admin.user.flash.updated', 'params' => ['username' => $user->getUsername()]]);

            return $this->redirectToRoute('admin_account_user_show', ['id' => $user->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/account/user/edit.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/{id}/reset', name: 'reset', methods: ['GET'], requirements: ['id' => '\d+'])]
    public function reset(User $user, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $em): Response
    {
        // generate a random temporary password and encode it
        $plainPassword = $this->generatePassword();
        $user->setPassword($userPasswordHasher->hashPassword($user, $plainPassword));

        $em->persist($user);
        $em->flush();

        // do anything else you need here, like send an email
        $this->addFlash('success', ['message' => 'admin.user.flash.reset', 'params' => ['username' => $user->getUsername(), 'password' => $plainPassword]]);

        return $this->redirectToRoute('admin_account_user_show', ['id' => $user->getId()], Response::HTTP_SEE_OTHER);
    }

    #[IsCsrfTokenValid(new Expression('"delete-" ~ args["user"].getId()'), tokenKey: 'delete_token')]
    #[Route('/{id}/delete', name: 'delete', methods: ['POST'], requirements: ['id' => '\d+'])]
    public function delete(User $user, EntityManagerInterface $em): Response
    {
        $em->remove($user);
        $em->flush();

        $this->addFlash('success', ['message' => 'admin.user.flash.deleted', 'params' => ['username' => $user->getUsername()]]);

        return $this->redirectToRoute('admin_account_user_index', [], Response::HTTP_SEE_OTHER);
    }

    private function generatePassword()
    {
        $generator = new ComputerPasswordGenerator();
        $generator->setUppercase()->setLowercase()->setNumbers()->setSymbols()->setLength(20);
        $plainPassword = $generator->generatePassword();

        return $plainPassword;
    }
}
