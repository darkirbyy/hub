<?php

namespace App\Controller\Account;

use App\Entity\Hub\Right;
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

/**
 * Controller for all pages that any authenticated user can see to check and update its account.
 */
#[Route(path: '/account', name: 'account_')]
class AccountController extends AbstractController
{
    /**
     * Displays the account main page of an authenticated user.
     *
     * @param Request $request the HTTP request instance, necessary to get the flash parameter (see {@see self::avatar})
     *
     * @return Response the rendered account main page
     */
    #[Route(path: '', name: 'index', methods: ['GET'])]
    public function index(Request $request): Response
    {
        if ($request->query->getBoolean('flash', false)) {
            $this->addFlash('success', ['message' => 'account.flash.avatarUpdated']);
        }

        return $this->render('account/index.html.twig', []);
    }

    /**
     * Handles user avatar upload through a turbo-frame. Replace or delete the old avatar if successfull.
     *
     * @param Request      $request the HTTP request instance
     * @param FlushManager $fm      handles database persistence
     *
     * @return Response redirects to the account page if successful (with flash param set to true), otherwise re-renders the form
     */
    #[Route(path: '/avatar', name: 'avatar', methods: ['GET', 'POST'])]
    public function avatar(Request $request, FlushManager $fm): Response
    {
        /** @var \App\Entity\Account\User $user */
        $user = $this->getUser();

        $form = $this->createForm(AvatarUserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Flash doesn't work, why ? use a query param instead...
            $fm->persist($user, ['message' => 'account.flash.avatarUpdated']);

            return $this->redirectToRoute('account_index', ['flash' => true], Response::HTTP_SEE_OTHER);
        }

        return $this->render('account/_avatar.html.twig', [
            'form' => $form,
        ]);
    }

    /**
     * Handles user password update through a password (with password strength) form. Rehash the new password if successfull.
     *
     * @param Request                          $request            the HTTP request instance
     * @param FlushManager                     $fm                 handles database persistence
     * @param UserPasswordHasherInterface|null $userPasswordHasher service for hashing the user's new password
     *
     * @return Response redirects to the account page if successful, otherwise re-renders the form
     */
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

    /**
     * Handles user account deletion through a deletion confirmation form. Deletes the user account and logs them out if successfull.
     *
     * @param Request      $request  the HTTP request instance
     * @param FlushManager $fm       handles entity removal
     * @param Security     $security manages user logout
     *
     * @return Response redirects to the login page after account deletion
     */
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

    /**
     * Handles user login or forced login (for sensitive actions).
     *
     * @param AuthenticationUtils $authenticationUtils provides authentication error messages and last username
     *
     * @return Response renders the login form or redirects if already authenticated
     */
    #[Route(path: '/login', name: 'login', methods: ['GET', 'POST'])]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // Redirects the user if fully authenticated, otherwise decides if it's a normal login or forced login for sensitive actions
        if ($this->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->redirectToRoute('account_index');
        } else {
            $loginType = $this->isGranted('IS_REMEMBERED') ? 'forced' : 'normal';
        }

        // Get the login error if there is one and last username entered by the user
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        // Creates the form, pre fill the username value, and enable+hide the rememberme value if it's a forced login
        $form = $this->createForm(ConnectUserType::class);
        $form->get('username')->setData($lastUsername);
        if ('forced' == $loginType) {
            $form->get('rememberMe')->setData(true);
        }

        // Transforms the authentication error into a flash message
        if (!empty($error)) {
            $this->addFlash('danger', ['message' => $error->getMessageKey(), 'params' => $error->getMessageData(), 'domain' => 'security']);
        }

        return $this->render('account/login.html.twig', [
            'form' => $form,
            'error' => $error,
            'loginType' => $loginType,
        ]);
    }

    /**
     * Handles user logout.
     *
     * This method is intercepted by the Symfony firewall configuration.
     */
    #[Route(path: '/logout', name: 'logout', methods: ['GET', 'POST'])]
    public function logout(): void
    {
    }

    /**
     * Check if the user is connected and has the role described in the parameter.
     *
     * @return Response a http code (403, 401, 400 or 200)
     */
    #[Route('/check', name: 'check')]
    public function check(Request $request): Response
    {
        // User MUST be connected
        /** @var \App\Entity\Account\User $user */
        $user = $this->getUser();
        if (!$user) {
            return new Response('Unauthorized', Response::HTTP_UNAUTHORIZED);
        }

        // Parameters ARE mandatory and MUST be valid
        $appliParam = $request->query->get('a');
        $roleParam = $request->query->get('r');

        if (!$appliParam || !preg_match('/^\w+$/', $appliParam)) {
            return new Response('Application parameter (a) is missing or invalid', Response::HTTP_BAD_REQUEST);
        }

        if (!$roleParam || !preg_match('/^\w+$/', $roleParam)) {
            return new Response('Role parameter (r) is missing or invalid', Response::HTTP_BAD_REQUEST);
        }

        $appliToCheck = strtolower($appliParam);
        $roleToCheck = 'ROLE_' . strtoupper($roleParam);

        // Search for any right with the good appli name and role
        $check = array_filter($user->getRights()->toArray(), fn (Right $r) => $r->getRole() === $roleToCheck && $r->getAppli()->getName() === $appliToCheck);
        if (empty($check)) {
            return new Response('Forbidden', Response::HTTP_FORBIDDEN);
        }

        return new Response('OK', Response::HTTP_OK);
    }
}
