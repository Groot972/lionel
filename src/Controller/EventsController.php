<?php

namespace App\Controller;

use App\Entity\Events;
use App\Form\Events1Type;
use App\Form\EventsType;
use App\Repository\EventsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\DateTime;

/**
 * @Route("/agenda")
 */
class EventsController extends AbstractController
{
    /**
     * @Route("/", name="agenda", methods={"GET"})
     */
    public function index(EventsRepository $eventsRepository): Response
    {
        $evenements = $eventsRepository->findAll();
        $rdv = [];

        foreach ($evenements as $event){
            $rdv[] = [
                'title' => $event->getTitle(),
                'start' => $event->getStart()->format('Y-m-d H:i:s'),
                'end' => $event->getEnd()->format('Y-m-d H:i:s'),
                'createur' => $event->getCreateur()
            ];
        }

        $data = json_encode($rdv);

        return $this->render('home/agenda.html.twig', [
            'data' => $data
        ]);
    }

    /**
     * @Route("/new", name="events_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager, MailerInterface $mailer): Response
    {
        $event = new Events();
        $form = $this->createForm(EventsType::class, $event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {


          $start = new \DateTime($request->request->get('start'));
          $end = new \DateTime($request->request->get('end'));
            $event->setEnd($end);
            $event->setStart($start);
            $entityManager->persist($event);
            $entityManager->flush();
            $evenement = $event->getTitle();
            $createur = $event->getCreateur();
            $debut = $request->request->get('start');
            $fin = $request->request->get('end');

            $mail = (new Email())
                ->from('expediteur@demo.test')
                ->to('lionelmontredon2@gmail.com', 'gdbperf@gmail.com' )
                ->subject('Nouvel evenement sur le calendrier')
                ->text("L'évenement $evenement, qui commence le $debut et fini le $fin, a été rajouté au calendrier par $createur.")
            ;

            $mailer->send($mail);

            return $this->redirectToRoute('agenda', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('events/new.html.twig', [
            'event' => $event,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="events_show", methods={"GET"})
     */
    public function show(Events $event): Response
    {
        return $this->render('events/show.html.twig', [
            'event' => $event,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="events_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Events $event, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(Events1Type::class, $event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('agenda', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('events/edit.html.twig', [
            'event' => $event,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="events_delete", methods={"POST"})
     */
    public function delete(Request $request, Events $event, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$event->getId(), $request->request->get('_token'))) {
            $entityManager->remove($event);
            $entityManager->flush();
        }

        return $this->redirectToRoute('agenda', [], Response::HTTP_SEE_OTHER);
    }
}
