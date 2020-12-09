<?php
  namespace App\Controller;
    use App\Document\UserEvent;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Doctrine\ODM\MongoDB\DocumentManager;
    use Psr\Log\LoggerInterface;
    use Symfony\Component\HttpFoundation\Request;

class UserEventController extends AbstractController {

      public function getUserEventsByUserId(string $userid, DocumentManager $dm) :array
      {
          
        $userevents = $dm->createQueryBuilder(UserEvent::class)
        ->field('userid')->equals($userid)                      
        ->hydrate(false)
        ->getQuery()            
        ->execute()
        ->toArray();

        return $userevents;
      }

      public function createUserEvent(string $userid, string $eventid, DocumentManager $dm) :UserEvent
      {
        
          $userevent = new UserEvent();
          $userevent->setUserId($userid);
          $userevent->setEventid($eventid);
          $dm->persist($userevent);
          $dm->flush();
          return $userevent;
      }

      public function deleteByEventId(string $userid, string $eventid, DocumentManager $dm, LoggerInterface $logger) :void
      {
        $dm->createQueryBuilder(UserEvent::class)
        ->remove()
        ->field('userid')->equals($userid)
        ->field('eventid')->equals($eventid)                      
        ->hydrate(false)
        ->getQuery()            
        ->execute();

        $dm->flush();
          
        return ;
      }
  }