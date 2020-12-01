<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\GiveEmail;
use App\Form\AccountType;
use App\Form\GiveEmailType;
use App\Entity\PasswordReset;
use App\Entity\PasswordUpdate;
use App\Form\RegistrationType;
use App\Form\PasswordResetType;
use App\Form\PasswordUpdateType;
use Symfony\Component\Mime\Email;
use App\Repository\UserRepository;
use Symfony\Component\Form\FormError;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AccountController extends AbstractController
{
    /**
     * Permet d'afficher et de gérer le formulaire de connexion
     * 
     * @Route("/login", name="account_login")
     * 
     * @return Response
     */
    public function login(AuthenticationUtils $utils)
    {
        $error = $utils->getLastAuthenticationError();
        $username = $utils->getLastUsername();

        return $this->render('account/login.html.twig', [
            'hasError' => $error !== null,
            'username' => $username
        ]);
    }

    /**
     * Permet de se deconnecter
     *
     * @Route("/logout", name="account_logout")
     * 
     * @return void
     */
    public function logout() {
        // .. rien !
    }

    /**
     * Permet d'afficher le formulaire d'inscription
     * 
     * @Route("/register", name="account_register")
     *
     * @return Response
     */
    public function register(Request $request, EntityManagerInterface $manager, UserPasswordEncoderInterface $encoder) {
        $user = new User();

        $form = $this->createForm(RegistrationType::class, $user);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $hash = $encoder->encodePassword($user, $user->getHash());
            $user->setHash($hash);

            $manager->persist($user);
            $manager->flush();

            $this->addFlash(
                'success',
                "Votre profil a bien été créé ! Vous pouvez maintenant vous connecter !"
            );

            return $this->redirectToRoute('account_login');
        }

        return $this->render('account/registration.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * Permet de modifier le profile de l'utilisateur
     * 
     * @Route("/account/profile", name="account_profile")
     * @IsGranted("ROLE_USER")
     *
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     */
    public function profile(Request $request, EntityManagerInterface $manager) {
        $user = $this->getUser();

        $form = $this->createForm(AccountType::class, $user);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $manager->persist($user);
            $manager->flush();

            $this->addFlash(
                'success',
                "Les données du profile ont été modifiées."
            );
        }

        return $this->render('account/profile.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * Permet de mofifier le mot de passe
     * 
     * @Route("/account/password-update", name="account_password")
     * @IsGranted("ROLE_USER")
     *
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     */
    public function passwordUpdate(Request $request, EntityManagerInterface $manager, UserPasswordEncoderInterface $encoder) {
        $passwordUpdate = new PasswordUpdate();

        $user = $this->getUser();

        $form = $this->createForm(PasswordUpdateType::class, $passwordUpdate);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            if(!password_verify($passwordUpdate->getOldPassword(), $user->getHash())) {
                $form->get('oldPassword')
                     ->addError(new FormError("Le mot de passe que vous avez tapé n'est pas votre mot de passe actuel !"));
            } else {
                $newPassword = $passwordUpdate->getNewPassword();
                $hash = $encoder->encodePassword($user, $newPassword);

                $user->setHash($hash);

                $manager->persist($user);
                $manager->flush();

                $this->addFlash(
                    'success',
                    "Votre mot de passe a bien été modifié !"
                );

                return $this->redirectToRoute('homepage');
            }
        }

        return $this->render('account/password.html.twig', [
            'form' => $form->createView()
        ]);
    }
    
    /**
     * Permet de saisir une adresse email pour un nouveau mot de passe
     * 
     * @Route("/password-forget", name="password_forget")
     * 
     */
    public function passwordForget(Request $request, UserRepository $repo, EntityManagerInterface $manager, MailerInterface $mailer) {
        $giveEmail = new GiveEmail;

        $form = $this->createForm(GiveEmailType::class, $giveEmail);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $email = $giveEmail->getEmail();

            $user = $repo->findOneBy(['email' => $email]);

            if(!$user) {
                $this->addFlash(
                    'warning',
                    "l'email est inconnu ! Veuillez le réentrer."
                );

                return $this->redirectToRoute('password_forget');            
            } else {
                // create token
                $token = random_int(100000000000, 999999999999);
                $user->setToken($token);

                $manager->persist($user);
                $manager->flush();

                //************
                // Send email
                //************
                $emailSend = (new Email())
                ->from('francisrodier78@yahoo.fr')

                //->to($email)
                //   ou
                ->to('francisrodier78@yahoo.fr')

                ->subject('Réinitialiser le mot de passe.')
                ->text('Cliquer sur l\'URL pour réinitialiser votre mot de pass.')

                ->html('<p>http://localhost:8000/password-reset/' . $token . '</p>');
                
                // Marche pas
                //$mailer->send($emailSend);

                $this->addFlash(
                    'success',
                    "Un email vous a été envoyé !"
                );

                return $this->redirectToRoute('homepage');            
            }
        }

        return $this->render('account/giveEmail.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * Permet de recréé un nouveau mot de passe
     * 
     * @Route("/password-reset/{token}", name="password_reset")
     *
     * @return Response
     */
    public function passwordReset($token, UserRepository $repo, Request $request, EntityManagerInterface $manager, UserPasswordEncoderInterface $encoder) {
        //  http://localhost:8000/password-reset/548139856837
        $user = $repo->findOneBy(['token' => $token]);

        $passwordReset = new PasswordReset();

        if(!$user) {
            $this->addFlash(
                'warning',
                "le token est inconnu !"
            );

            return $this->redirectToRoute('password_forget');            
        } else {
            $form = $this->createForm(PasswordResetType::class, $passwordReset);

            $form->handleRequest($request);

            if($form->isSubmitted() && $form->isValid()) {
                $token = 'null';
                $user->setToken($token);

                $newPassword = $passwordReset->getNewPassword();
                $hash = $encoder->encodePassword($user, $newPassword);

                $user->setHash($hash);

                $manager->persist($user);
                $manager->flush();

                $this->addFlash(
                    'success',
                    "Votre mot de passe a bien été recréé !"
                );

                return $this->redirectToRoute('homepage');
            }

            return $this->render('account/passwordReset.html.twig', [
                'form' => $form->createView()
            ]);
        }
    }
}
