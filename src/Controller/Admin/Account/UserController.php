<?php

namespace App\Controller\Admin\Account;

use App\Entity\Account\User;
use App\Form\Account\NewUserType;
use App\Repository\Account\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/account/user', name: 'admin_account_user_')]
class UserController extends AbstractController
{
    #[Route('', name: 'index')]
    public function index(UserRepository $userRepo): Response
    {
        $users = $userRepo->findAll();

        return $this->render('admin/account/user/index.html.twig', [
            'users' => $users,
            'cols' => [
                1 => ['getter' => 'id'],
                2 => ['getter' => 'username'],
                3 => ['getter' => 'imageInfos', 'breakpoint' => 'md'],
            ],
        ]);
    }

    #[Route('/{id}', name: 'show', requirements: ['id' => '\d+'])]
    public function show(User $user): Response
    {
        return $this->render('admin/account/user/show.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/new', name: 'new')]
    public function new(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $form = $this->createForm(NewUserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var string $plainPassword */
            $plainPassword = $form->get('plainPassword')->getData();

            // encode the plain password
            $user->setPassword($userPasswordHasher->hashPassword($user, $plainPassword));

            $entityManager->persist($user);
            $entityManager->flush();

            // do anything else you need here, like send an email

            return $this->redirectToRoute('admin_account_user_index');
        }

        return $this->render('admin/account/user/new.html.twig', [
            'form' => $form,
        ]);
    }
}
