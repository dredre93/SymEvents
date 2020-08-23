<?php
namespace App\Controller;

use App\Form\UserType;
use App\Document\User;
use Doctrine\ODM\MongoDB\DocumentManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RegistrationController extends AbstractController
{
    /**
     * @Route("/register", name="user_registration")
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder, DocumentManager $dm )
    {
        // 1) build the form
        $user = new User();
        $form = $this->createFormBuilder($user)
            ->add('firstname', TextType::class)
            ->add('name', TextType::class)        
            ->add('alias', TextType::class)     
            ->add('email', TextType::class)        
            ->add('password', PasswordType::class)         
            ->add('save', SubmitType::class, 
                array('label' => 'Get registered!')
                )
            ->getForm();

        // 2) handle the submit (will only happen on POST)
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            
            $user = $form->getData();
            // 3) Encode the password (you could also do this via Doctrine listener)
            $password = $passwordEncoder->encodePassword($user, $user->getPassword());
            $user->setPassword($password);
            $user->setIsAdmin(false);

            // 4) save the User!
            $dm->persist($user);
            $dm->flush();

            // ... do any other work - like sending them an email, etc
            // maybe set a "flash" success message for the user

            return $this->redirectToRoute('home');
        }

        return $this->render(
            'registration/register.html.twig',
            array('form' => $form->createView(),
                  'compound' => true,
                )
        );
    }
}