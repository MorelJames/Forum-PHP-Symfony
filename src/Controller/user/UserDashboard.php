<?php

namespace App\Controller\user;

use App\Entity\User;
use App\Form\DashboardFormType;
use App\Repository\UserRepository;
use App\Security\EmailVerifier;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Contracts\Translation\TranslatorInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;

class UserDashboard extends AbstractController{

    private EmailVerifier $emailVerifier;

    public function __construct(private Security $security, EmailVerifier $emailVerifier){
        $this->emailVerifier = $emailVerifier;
    }

    #[Route('/userDashboard', name:'app_userdashboard')]
    public function index(Request $request, UserPasswordHasherInterface $userPasswordHasher, ManagerRegistry $doctrine, EntityManagerInterface $entityManager): Response
    {
        if (!$this->security->getUser())
        {
            return $this->redirectToRoute('app_login');
        }

        $userId = $this->security->getUser()->getId();
        $user = $doctrine->getRepository(User::class)->find($userId);

        if(!$user->isVerified()) {
            return $this->render('requireValidation.html.twig');
        }

        $form = $this->createForm(DashboardFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $user->setIsVerified(false);

            $entityManager->flush();

            // generate a signed url and email it to the user
            $this->emailVerifier->sendEmailConfirmation('app_verify_email', $user,
                (new TemplatedEmail())
                    ->from(new Address('blog.dnr@atocet.fr', 'DnR Blog Bot'))
                    ->to($user->getEmail())
                    ->subject('Please Confirm your Email')
                    ->htmlTemplate('registration/confirmation_email.html.twig')
            );
            // do anything else you need here, like send an email
            return $this->render('requireValidation.html.twig');
        }

        return $this->render('user/userDashboard.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
}