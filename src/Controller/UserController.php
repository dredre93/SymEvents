<?php
    namespace App\Controller;

    use App\Document\User;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Routing\Annotation\Route;
    use Doctrine\ODM\MongoDB\DocumentManager;
    use Symfony\Component\HttpFoundation\RedirectResponse;

class UserController extends AbstractController {

      /**
       * @Route("/user", name="user_list")
       */
      public function user() :Response
      {
          return $this->render ('user/index.html.twig');
      }

      /**
       * @Route("/user/create", name="create_user")
       */
      public function create(DocumentManager $dm) :Response
      {
        $user = new User();
        $user->setFirstname('Bob');
        $user->setName('Brown');
        $user->setAlias('Bobby');
        $user->setEmail('email@email.com');
        $user->setIsAdmin(true);

        //$conn1 = $this->get('doctrine_mongodb.odm.default_connection');
        $dm->persist($user);
        $dm->flush();
    
        return new Response('Created product id ' . $user->getId());
      }

       /**
       * @Route("/user/login", name="login")
       */
      public function login(DocumentManager $dm) :RedirectResponse
      {
        $user = new User();
        $user->setFirstname('Bob');
        $user->setName('Brown');
        $user->setAlias('Bobby');
        $user->setEmail('email@email.com');
        $user->setIsAdmin(true);

        //$conn1 = $this->get('doctrine_mongodb.odm.default_connection');
        $dm->persist($user);
        $dm->flush();
    
        return $this->redirectToRoute('home');
      }
  }