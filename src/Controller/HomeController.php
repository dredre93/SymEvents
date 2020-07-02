<?php
    namespace App\Controller;

    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\Routing\Annotation\Route;
   
class HomeController extends AbstractController {

        /**
       * @Route("/user", name="user")
       */
      public function user()
      {
          return $this->renderView ('user/user.html.twig');
      }

      /**
       * @Route("/", name="home")
       */
      public function index()
      {
          return $this->render ('home/index.html.twig');
      }


  }