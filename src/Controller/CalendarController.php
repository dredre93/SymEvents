<?php
    namespace App\Controller;

use App\Document\Event;
use App\Document\UserEvent;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Routing\Annotation\Route;
    use Doctrine\ODM\MongoDB\DocumentManager;
use Monolog\Logger;
use Psr\Log\LoggerInterface;

class CalendarController extends AbstractController {

      /**
       * @Route("/event/calendar/{month}", name="get_calendar", requirements={"month"="\d+"}))
       */
      public function getCalendar(int $month, DocumentManager $dm, LoggerInterface $logger) :Response
      {   

        $nextMonth = date("d.m.Y", mktime(0, 0, 0, $month, 1, date("Y")));

        $user = $this->get('security.token_storage')->getToken()->getUser();

        $evc = new EventController();
        $events = $evc->getEventsByUserid($user->getId(), $dm);

        $events = $this->filterEventsByMonth($events, $month, $logger);

        return $this->render ('calendar/calendar.html.twig', [ 'nextMonth' => $nextMonth, 'events' => $events]);
      }

      private function filterEventsByMonth(array $events, int $nextMonth, LoggerInterface $logger ) :array
      {
        $filteredArray = $events;      
        
        if (!isset($filteredArray)){
            return array();
        }

        $event = new Event();
        foreach ($filteredArray as $event => $value){
           
            //$logger->info(json_encode($filteredArray));
            //$logger->info(var_dump($value));
            //$logger->info(var_dump($filteredArray[$event]));
            //$logger->info(date("n",  $value['startDate']->toDateTime()->getTimeStamp()));
            if (date("m", $value['startDate']->toDateTime()->getTimeStamp()) <> $nextMonth) {
                unset($filteredArray[$event]);
            }
        }

        return $filteredArray;
      }

  }