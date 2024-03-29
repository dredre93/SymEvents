<?php
    namespace App\Controller;

    use App\Document\Event;
use App\Document\UserEvent;
use DateTimeInterface;
use DateTimeZone;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Routing\Annotation\Route;
    use Doctrine\ODM\MongoDB\DocumentManager;
use EnumEventType;
use Psr\Log\LoggerInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
    use Symfony\Component\Form\Extension\Core\Type\SubmitType;
    use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PropertyInfo\Extractor\ReflectionExtractor;
use Symfony\Component\PropertyInfo\PropertyInfoExtractor;
use Symfony\Component\Security\Http\Event\SwitchUserEvent;

class EventController extends AbstractController {

      /**
       * @Route("/event/list", name="event_list")
       */
      public function getEvents(DocumentManager $dm, LoggerInterface $logger) :Response
      {
          
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $id = $user->getId();

        $logger->info($user->getId());

        $events = $this->getEventsByUserid($id, $dm);

        foreach($events as $event){
          $logger->info($event['startDate']->toDateTime()->getTimeStamp());
        }

        
        $reflectionExtractor = new ReflectionExtractor();
        $listExtractors = [$reflectionExtractor];
        $propertyInfo = new PropertyInfoExtractor($listExtractors);
        $properties = $propertyInfo->getProperties(Event::class);
        $exceptProps = "id";
        $properties = array_filter($properties, function ($element) use ($exceptProps) { return ($element != $exceptProps); } );
        
          return $this->render ('event/event_list.html.twig', array('events'=> $events, 'properties'=> $properties));
      }

      public function getEventsByUserid(string $userid, DocumentManager $dm) :array
      {

        $uec = new UserEventController();
        $userevents = $uec->getUserEventsByUserId($userid, $dm);        
        $eventIds = array_column($userevents, 'eventid');

        $events =  $dm->createQueryBuilder(Event::class)
        ->field('id')->in($eventIds)        
        ->hydrate(false)
        ->getQuery()            
        ->execute()
        ->toArray();

        return $events;
      }
      
      /**
       * @Route("/event/calendar", name="calendar_overview")
       */
      public function showCalendar() :Response
      {
          return $this->render ('Calendar/calendar.html.twig');
      }

      /**
       * @Route("/event/createForm", name="create_form_event")
       */
      public function createEvent(DocumentManager $dm, Request $request) :Response
      {
        
          $user = $this->get('security.token_storage')->getToken()->getUser();
          $event = new Event();
          //$timezone = new DateTimeZone("Europe/Germany");
          
          $startDt = new \DateTime(date(DateTimeInterface::ISO8601, strtotime('today')) );
          //$start = $date->setISODate($date->format("y"), $date->format("n"), $date->format("d"));
          $event->setStartDate( $startDt );

          $endDt = new \DateTime(date(DateTimeInterface::ISO8601, strtotime('tomorrow') ));
          $event->setEndDate( $endDt);
          
          $form = $this->createFormBuilder($event)
              ->add('name', TextType::class)
              ->add('startDate', DateTimeType::class)        
              ->add('endDate', DateTimeType::class)         
              ->add('type', ChoiceType::class, array( 
                  'choices' => EnumEventType::getEnumValues(),
                  'choice_label' => function($choice) {
                    return EnumEventType::getTypeName($choice);
                },
              ))
              ->add('save', SubmitType::class, 
                    array('label' => 'Create Event')
                    )
              ->getForm();

              $form->handleRequest($request);
              if ($form->isSubmitted() && $form->isValid()) {
                  // $form->getData() holds the submitted values
                  // but, the original `$task` variable has also been updated
                  $event = $form->getData();
                  $dm->persist($event);
                  $dm->flush();

                  $uec = new UserEventController();
                  $uec->createUserEvent($user->getId(), $event->getId(), $dm);

                  return $this->redirectToRoute('event_list');
              }

              return $this->render(
                'event/createEvent.html.twig',
                array('form' => $form->createView(),
                      'compound' => true,
                    )
            );
      }

        /**
       * @Route("/event/edit/{id}", name="edit_event")
       */
      public function edit(DocumentManager $dm, string $id, Request $request) :Response
      {
          // creates a task object and initializes some data for this example
          $event = $dm->getRepository(Event::class)->find($id);
          
          $form = $this->createFormBuilder($event)
              ->add('name', TextType::class)
              ->add('startDate', DateTimeType::class)        
              ->add('endDate', DateTimeType::class)         
              ->add('type', ChoiceType::class, array( 
                  'choices' => EnumEventType::getEnumValues(),
                  'choice_label' => function($choice) {
                    return EnumEventType::getTypeName($choice);
                },
              ))
              ->add('save', SubmitType::class, 
                    array('label' => 'Save Event')
                    )
              ->getForm();

              $form->handleRequest($request);
              if ($form->isSubmitted() && $form->isValid()) {
                  // $form->getData() holds the submitted values
                  // but, the original `$task` variable has also been updated
                  $event = $form->getData();
                  
                  $dm->persist($event);
                  $dm->flush();
                  return $this->redirectToRoute('event_list');
              }

              return $this->render(
                'event/editEvent.html.twig',
                array('form' => $form->createView(),
                      'compound' => true,
                    )
            );
      }


       /**
       * @Route("/event/delete/{id}", name="create_event")
       */
      public function delete(string $id, DocumentManager $dm, LoggerInterface $logger) :Response
      {          
        $event = $dm->getRepository(Event::class)->find($id);         
        $user = $this->get('security.token_storage')->getToken()->getUser();

        $uec = new UserEventController();
        $uec->deleteByEventId($user->getId(), $event->getId(), $dm, $logger);
        
        $dm->remove($event);
        $dm->flush();
          
         return $this->redirectToRoute("event_list");
      }
  }