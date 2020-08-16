<?php
    namespace App\Controller;

    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\Routing\Annotation\Route;
   
class HomeController extends AbstractController {

      /**
       * @Route("/", name="home")
       */
      public function home()
      {
         if ($this->getUser() == null){
            return $this->redirect ('/login');
         } else {
            return $this->render ('home/index.html.twig');
         }        
      }
  }