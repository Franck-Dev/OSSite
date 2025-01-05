<?php

namespace App\Controller;

use App\DTO\ContactDTO;
use App\Form\ContactType;
use Symfony\Component\Mime\Address;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ContactController extends AbstractController
{
    #[Route('/contact', name: 'app_contact')]
    public function contact(Request $request, MailerInterface $mailer): Response
    {
        $contact = new ContactDTO();
        $form = $this->createForm(ContactType::class, $contact);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($contact->honeyPot === "4") {
                # Envoyer email de contact
                // On crée le mail avec les infos de contact
                $email = (new TemplatedEmail())
                ->from(new Address('postmaster@cgt-daher.com', 'Admin CGT DAHER'))
                ->to(new Address('postmaster@cgt-daher.com', 'Admin CGT DAHER'))
                ->cc($contact->email)
                ->subject('Demande de contact')
                ->htmlTemplate("emails/contact.html.twig")
                ->context(['contact'=>$contact]);
                // On envoie le mail
                $mailer->send($email);

                $this->addFlash(
                    'success',
                    'Votre demande est bien partie, vous devriez recevoir une copie du mail!!!'
                 );
            } else {
                 # Erreur robot
                $this->addFlash(
                   'warning',
                   'vous êtes un robot, repartez chez vous!!!'
                );
                throw $this->createAccessDeniedException('AccessDenied');
            }
            return $this->redirectToRoute('app_contact');
        }
        
        return $this->render('contact/contact.html.twig', [
            'form' => $form,
        ]);
    }
}
