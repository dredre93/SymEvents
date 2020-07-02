<?php
    namespace App\Controller;

use App\Document\User;
use Symfony\Bundle\FrameworkBundle\Controller;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Routing\Annotation\Route;
   
class UserController extends Controller {

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
      public function create() 
      {
        $user = new User();
        $user->setName('Bob');
    
        $dm = $this->get('doctrine_mongodb')->getManager();
        $dm->persist($user);
        $dm->flush();
    
        return new Response('Created product id ' . $user->getId());
      }
  }