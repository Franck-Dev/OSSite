<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Mandat;
use App\Security\EmailVerifier;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use Symfony\Component\Mime\Address;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\Query\AST\Functions\ConcatFunction;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;

class RegistrationController extends AbstractController
{
    private EmailVerifier $emailVerifier;

    public function __construct(EmailVerifier $emailVerifier)
    {
        $this->emailVerifier = $emailVerifier;
    }

    #[Route('/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager, MailerInterface $mailer): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            //Création d'un token user
            $user->setToken(bin2hex(random_bytes(60)));

            //Gestion des rôles
            $tbMandats=$form->get('mandat')->getData();
            foreach ($tbMandats as $key => $mandat) {
                $role=$entityManager->getRepository(Mandat::class)->findOneBy(['id' => $mandat]);
                $tbRoles[$key]=$role->getRole();
            }
            $user->setRoles($tbRoles);
            $entityManager->persist($user);
            $entityManager->flush();

            // Validation admin pour les rôles autres que adhérents
            if ($role->getRole()!=="ROLE_USER") {
                // generate a signed url and email it to the admin
                $this->emailVerifier->sendEmailConfirmation('app_verify_email', $user,
                (new TemplatedEmail())
                    ->from(new Address('postmaster@cgt-daher.com', 'Admin CGT DAHER'))
                    ->To('f.dartois@daher.com')
                    ->subject('Please Confirm your Email')
                    ->htmlTemplate('registration/confirmation_admin_email.html.twig')
                );
                // On crée le mail d'information de l'utilisateur
                $email = (new TemplatedEmail())
                ->from(new Address('postmaster@cgt-daher.com', 'Admin CGT DAHER'))
                ->to($user->getEmail())
                ->subject('Confirmation d\'inscription')
                ->htmlTemplate("emails/register.html.twig")
                ->context(['name'=>$user->getNom()]);

                // On envoie le mail
                $mailer->send($email);
            } else {
                // generate a signed url and email it to the user
                $this->emailVerifier->sendEmailConfirmation('app_verify_email', $user,
                (new TemplatedEmail())
                    ->from(new Address('postmaster@cgt-daher.com', 'Admin CGT DAHER'))
                    ->replyTo($user->getEmail())
                    ->subject('Please Confirm your Email')
                    ->htmlTemplate('registration/confirmation_email.html.twig')
                );
            }
            
            
            // do anything else you need here, like send an email
            $this->addFlash('success', 'Un email a été généré pour finir de valider votre compte.');
            return $this->redirectToRoute('app_home');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form,
        ]);
    }

    #[Route('/verify/email', name: 'app_verify_email')]
    public function verifyUserEmail(Request $request, TranslatorInterface $translator, UserRepository $userRepository, MailerInterface $mailer): Response
    {
        $id = $request->query->get('id');

        if (null === $id) {
            $this->addFlash('warning', 'Votre id n\'est pas connu. Veuillez contacter votre administrateur');
            return $this->redirectToRoute('app_register');
        }

        $user = $userRepository->find($id);

        if (null === $user) {
            $this->addFlash('warning', 'Votre compte a un problème. Veuillez contacter votre administrateur');
            return $this->redirectToRoute('app_register');
        }

        // validate email confirmation link, sets User::isVerified=true and persists
        try {
            $this->emailVerifier->handleEmailConfirmation($request, $user);
        } catch (VerifyEmailExceptionInterface $exception) {
            $this->addFlash('verify_email_error', $translator->trans($exception->getReason(), [], 'VerifyEmailBundle'));

            return $this->redirectToRoute('app_register');
        }

        // @TODO Change the redirect on success and handle or remove the flash message in your templates
        $this->addFlash('success', 'Your email address has been verified.');

        //Envoie email de validation
        $email = (new TemplatedEmail())
                ->from(new Address('postmaster@cgt-daher.com', 'Admin CGT DAHER'))
                ->to($user->getEmail())
                ->subject('Confirmation d\'inscription')
                ->htmlTemplate("emails/validation_register.html.twig")
                ->context(['name'=>$user->getNom()]);
        $mailer->send($email);

        return $this->redirectToRoute('app_home');
    }
}
