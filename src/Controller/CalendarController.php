<?php
    namespace App\Controller;

    use App\Document\Event;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Routing\Annotation\Route;
    use Doctrine\ODM\MongoDB\DocumentManager;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Logger\ConsoleLogger;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;

class CalendarController extends AbstractController {

      /**
       * @Route("/event/calendar/{month}", name="get_calendar", requirements={"month"="\d+"}))
       */
      public function getCalendar(int $month, DocumentManager $dm) :Response
      {   

        $nextMonth = date("d.m.Y", mktime(0, 0, 0, $month, 1, date("Y")));

        $filteredEvents = $dm->createQueryBuilder(Event::class)
        ->field('startDate')->gte($nextMonth)
        ->hydrate(false)
        ->getQuery()
        ->execute()
        ->toArray();
    


        return $this->render ('calendar/calendar.html.twig', [ 'nextMonth' => $nextMonth, 'events' => $filteredEvents],);
      }
  }