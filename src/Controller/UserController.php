<?php
    namespace App\Controller;

    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Routing\Annotation\Route;
   
class UserController extends AbstractController {

      /**
       * @Route("/user", name="user_list")
       */
      public function userList()
      {
          return $this->render ('user/user.html.twig');
      }
  }