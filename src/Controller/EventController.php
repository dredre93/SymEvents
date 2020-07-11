<?php
    namespace App\Controller;

    use App\Document\User;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Routing\Annotation\Route;
    use Doctrine\ODM\MongoDB\DocumentManager;
   
class EventController extends AbstractController {

      /**
       * @Route("/event/list", name="event_list")
       */
      public function getEvents() :Response
      {
          return $this->render ('event/evntlist.html.twig');
      }

      /**
       * @Route("/event/create", name="create_event")
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
  }