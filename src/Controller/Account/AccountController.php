<?php

namespace App\Controller\Account;

use App\Form\Account\AvatarUserType;
use App\Form\Account\ConnectUserType;
use App\Form\Account\DeleteUserType;
use App\Form\Account\PasswordUserType;
use App\Service\FlushManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

#[Route(path: '/account', name: 'account_')]
class AccountController extends AbstractController
{
    #[Route(path: '', name: 'index', methods: ['GET'])]
    public function index(Request $request): Response
    {
        if ($request->query->getBoolean('flash', false)) {
            $this->addFlash('success', ['message' => 'account.flash.avatarUpdated']);
        }

        return $this->render('account/index.html.twig', []);
    }

    #[Route(path: '/avatar', name: 'avatar', methods: ['GET', 'POST'])]
    public function avatar(Request $request, FlushManager $fm): Response
    {
        /** @var \App\Entity\Account\User $user */
        $user = $this->getUser();

        $form = $this->createForm(AvatarUserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Flash don't work, why ? use a query param instead...
            $fm->persist($user, ['message' => 'account.flash.avatarUpdated']);

            return $this->redirectToRoute('account_index', ['flash' => true], Response::HTTP_SEE_OTHER);
        }

        return $this->render('account/avatar.html.twig', [
            'form' => $form,
        ]);
    }

    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    #[Route(path: '/password', name: 'password', methods: ['GET', 'POST'])]
    public function password(Request $request, FlushManager $fm, ?UserPasswordHasherInterface $userPasswordHasher = null): Response
    {
        /** @var \App\Entity\Account\User $user */
        $user = $this->getUser();

        $form = $this->createForm(PasswordUserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $plainPassword = $form->get('plainPassword')->getData();
            $user->setPassword($userPasswordHasher->hashPassword($user, $plainPassword));

            $fm->persist($user, ['message' => 'account.flash.passwordUpdated']);

            return $this->redirectToRoute('account_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('account/password.html.twig', [
            'form' => $form,
        ]);
    }

    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    #[Route(path: '/delete', name: 'delete', methods: ['GET', 'POST'])]
    public function delete(Request $request, FlushManager $fm, Security $security): Response
    {
        /** @var \App\Entity\Account\User $user */
        $user = $this->getUser();

        $form = $this->createForm(DeleteUserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $security->logout(false);

            $fm->remove($user, ['message' => 'account.flash.accountDeleted']);

            return $this->redirectToRoute('account_login', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('account/delete.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route(path: '/login', name: 'login', methods: ['GET', 'POST'])]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->redirectToRoute('account_index');
        } else {
            $loginType = $this->isGranted('IS_REMEMBERED') ? 'forced' : 'normal';
        }

        // get the login error if there is one and last username entered by the user
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        // create the form and pre fill the username value
        $form = $this->createForm(ConnectUserType::class);
        $form->get('username')->setData($lastUsername);
        if ('forced' == $loginType) {
            $form->get('rememberMe')->setData(true);
        }

        if (!empty($error)) {
            $this->addFlash('danger', ['message' => $error->getMessageKey(), 'params' => $error->getMessageData(), 'domain' => 'security']);
        }

        return $this->render('account/login.html.twig', [
            'form' => $form,
            'error' => $error,
            'loginType' => $loginType,
        ]);
    }

    #[Route(path: '/logout', name: 'logout', methods: ['GET', 'POST'])]
    public function logout(): void
    {
        // This method can be blank - it will be intercepted by the logout key on your firewall.
    }
}
