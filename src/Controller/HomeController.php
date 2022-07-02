<?php

namespace App\Controller;

use App\Entity\Client;
use App\Entity\Etapes;
use App\Entity\Events;
use App\Entity\Images;
use App\Entity\Infos;
use App\Entity\InfosCarosserie;
use App\Entity\NoteMarque;
use App\Entity\NoteModele;
use App\Entity\Reparation;
use App\Entity\ReparationCarosserie;
use App\Entity\ReparationECar;
use App\Entity\ReparationEtat;
use App\Entity\Voiture;
use App\EventSubscriber\CalendarSubscriber;
use App\Form\ClientType;
use App\Form\InfosCarosserieType;
use App\Form\InfosType;
use App\Form\ReparationCarosserieType;
use App\Form\ReparationECarType;
use App\Form\ReparationEtatType;
use App\Form\ReparationType;
use App\Form\ReparationVType;
use App\Form\ReparationVType2;
use App\Form\VoitureType;
use App\Form\VoitureType2;
use App\Repository\EventsRepository;
use App\Repository\InfosRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use CalendarBundle\CalendarEvents;
use CalendarBundle\Entity\Event;
use CalendarBundle\Event\CalendarEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {
        $listeVoitures = $entityManager->getRepository(Voiture::class)->findAll();
        $voiture = new Voiture();
        $form = $this->createForm(VoitureType::class, $voiture);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){

            $marque = $request->request->get('marque');
            $modele = $request->request->get('modele');
            $type = $request->request->get('type');
            $image = $form->get('image')->getData();
            // On génère un nouveau nom de fichier
            $fichier = md5(uniqid()).'.'.$image->guessExtension();

            // On copie le fichier dans le dossier uploads
            $image->move(
                $this->getParameter('images_directory'),
                $fichier
            );
            // On crée l'image dans la base de données
            $img = new Images();
            $img->setName($fichier);
            $voiture->setImage($img);
            $voiture->setMarque($marque);
            $voiture->setModele($modele);
            $voiture->setEtat('rouge');
            $voiture->setType($type);
            $entityManager->persist($voiture);
            $entityManager->flush();
            $this->addFlash('success', 'La voiture a été rajouté au garage');
            $route = $request->headers->get('referer');
            return $this->redirect($route);
        }

        $client = new Client();
        $form2 = $this->createForm(ClientType::class, $client);
        $form2->handleRequest($request);
        if ($form2->isSubmitted() && $form2->isValid()){
           $entityManager->persist($client);
           $entityManager->flush();
            $this->addFlash('success', 'Le client a été créé');
            $route = $request->headers->get('referer');
            return $this->redirect($route);
        }

        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            "listeVoitures" => $listeVoitures,
            "form" => $form->createView(),
            "form2" => $form2->createView(),


        ]);
    }


    /***********************************************
     * *********************************************
     * Gestion des Clients
     *
     * *********************************************
     * *********************************************
     * *********************************************
     */



    /**
     * @Route("/clients", name="clients")
     */

    public function listeClients(EntityManagerInterface $entityManager, Request $request){

        $listeClients = $entityManager->getRepository(Client::class)->findBy(array(),array('nom' => 'ASC'));



        return $this->render('home/listeClients.html.twig', [
            "listeClients" => $listeClients,
        ]);
    }

    /**
     * @Route("/clients/{id}", name ="clientDetail")
     */
    public function detailClient ($id, EntityManagerInterface $entityManager, Request $request){
        $detail = $entityManager->getRepository(Client::class)->findOneBy(['id'=> $id]);
        if (!$detail){
            throw $this->createNotFoundException("Le client n'existe pas");}

        return $this->render('home/detailClient.html.twig', [
            "detail"=> $detail
        ]);
    }



    /***********************************************
     * *********************************************
     * Gestion des Réparations types mécanique
     *
     * *********************************************
     * *********************************************
     * *********************************************
     */


    /**
     * @Route("/mecanique/{id}", name="carInfos")
     */
    public function CarInfos ($id, EntityManagerInterface $entityManager, Request $request){
        //On récupère l'article correspondant à l'iD
        $car = $entityManager->getRepository(Voiture::class)->findOneBy(['id'=>$id]);
        $listeinfos = $entityManager->getRepository(Infos::class)->findAll();
        $marque = $car->getMarque();
        $modele = $car->getModele();
        //Si l'article n'est pas trouvé
        if (!$car){
            throw $this->createNotFoundException("Le client n'existe pas");}
        //On récupère la liste des réparations correspondant à la voiture
        $repair = $entityManager->getRepository(ReparationEtat::class)->findBy(['voiture' => $car->getId()]);


        //Créer une réparation ==> mécanique
        $reparationM = new Reparation();
        $formReparationM = $this->createForm(ReparationType::class, $reparationM);
        $formReparationM->handleRequest($request);
        if($formReparationM->isSubmitted() && $formReparationM->isValid()){
            $entityManager->persist($reparationM);
            $entityManager->flush();
            $this->addFlash('success', 'La réparation a été créée');
            $route = $request->headers->get('referer');
            return $this->redirect($route);
        };

        //Ajout de la réparation ==> mecanique à la voiture
        $ajoutReparationM = new ReparationEtat();
        $formAjoutReparationM = $this->createForm(ReparationEtatType::class, $ajoutReparationM);
        $formAjoutReparationM->handleRequest($request);
        if ($formAjoutReparationM->isSubmitted() && $formAjoutReparationM->isValid()){
            $ajoutReparationM->setVoiture($car);
            $ajoutReparationM->setEtat('rouge');
            $entityManager->persist($ajoutReparationM);
            $entityManager->flush();
            $this->addFlash('success', 'La réparation a été ajouté');
            $route = $request->headers->get('referer');
            return $this->redirect($route);
        };


        //Creation d'une infos ==> mecanique
        $infos = new Infos();
        $formI = $this->createForm(InfosType::class, $infos);
        $formI->handleRequest($request);
        if ($formI->isSubmitted() && $formI->isValid()){
            $infos->setModeleVoiture($modele);
            $infos->setMarqueVoiture($marque);
            $entityManager->persist($infos);
            $entityManager->flush();
            $this->addFlash('success', "L'infos a été rajoutée");
            $route = $request->headers->get('referer');
            return $this->redirect($route);
        };

        return $this->render('home/reparations.html.twig', [
            "car" => $car,
            "repair" => $repair,
            "formAjoutReparationM" => $formAjoutReparationM->createView(),
            "formReparationM" => $formReparationM->createView(),
            'formI' => $formI->createView(),
            'listeinfos' => $listeinfos

        ]);
    }


        /**
     * @Route("/reparation/jaune/{id}", name="jaune")
     */

    public function changementEtatjaune(ReparationEtat $reparation, EntityManagerInterface $entityManager, Request $request){

        $reparation->setEtat('jaune');
        $reparation->setDateDebut(new \DateTime());
        $entityManager->persist($reparation);
        $entityManager->flush();
        $this->addFlash('success', "L'etat de la reparation a été modifié");
        $route = $request->headers->get('referer');
        return $this->redirect($route);
    }

    /**
     * @Route("/reparation/vert/{id}", name="vert")
     */

    public function changementEtatvert(ReparationEtat $reparation, EntityManagerInterface $entityManager, Request $request){

        $reparation->setEtat('vert');
        $reparation->setDateFin(new \DateTime());
        $entityManager->persist($reparation);
        $entityManager->flush();
        $this->addFlash('success', "L'etat de la reparation a été modifié");
        $route = $request->headers->get('referer');
        return $this->redirect($route);
    }

    /**
     * @Route("/reparation/rouge/{id}", name="rouge")
     */

    public function changementEtatrouge(ReparationEtat $reparation, EntityManagerInterface $entityManager, Request $request){

        $reparation->setEtat('rouge');
        $entityManager->persist($reparation);
        $entityManager->flush();
        $this->addFlash('success', "L'etat de la reparation a été modifié");
        $route = $request->headers->get('referer');
        return $this->redirect($route);
    }


    /**
     * @Route("/reparation/delete/{id}", name="deleteReparation")
     */
    public function deleteReparation (ReparationEtat $reparation, EntityManagerInterface $entityManager, Request $request){

        //Si l'article n'est pas trouvé
        if (!$reparation){
            throw $this->createNotFoundException("La réparation n'existe pas");}

        //Si l'article existe

        $entityManager->remove($reparation);
        $entityManager->flush();
        $this->addFlash('success', 'La réparation a été supprimé !');
        $route = $request->headers->get('referer');
        return $this->redirect($route);
    }




    /***********************************************
     * *********************************************
     * Gestion des Réparations types carrosserie
     *
     * *********************************************
     * *********************************************
     * *********************************************
     */

    /**
     * @Route("/carrosserie/{id}", name="carDetail")
     */
    public function CarDetails ($id, EntityManagerInterface $entityManager, Request $request)
    {
        //On récupère l'article correspondant à l'iD
        $car = $entityManager->getRepository(Voiture::class)->findOneBy(['id' => $id]);
        $listeinfosC = $entityManager->getRepository(InfosCarosserie::class)->findBy(['marque_voiture' => $car->getMarque(), 'modele_voiture' => $car->getModele()]);
        $marque = $car->getMarque();
        $modele = $car->getModele();
        //Si l'article n'est pas trouvé
        if (!$car) {
            throw $this->createNotFoundException("Le client n'existe pas");
        }
        //On récupère la liste des réparations correspondant à la voiture
        $repair = $entityManager->getRepository(ReparationECar::class)->findBy(['voiture' => $car->getId()]);

        //Créer une réparation ==> carrosserie
        $reparationC = new ReparationCarosserie();
        $formReparationC = $this->createForm(ReparationCarosserieType::class, $reparationC);
        $formReparationC->handleRequest($request);
        if ($formReparationC->isSubmitted() && $formReparationC->isValid()) {
            $entityManager->persist($reparationC);
            $entityManager->flush();
            $this->addFlash('success', 'La réparation a été créée');
            $route = $request->headers->get('referer');
            return $this->redirect($route);
        };

        //Ajout de la réparation ==> carrosserie à la voiture
        $ajoutReparationC = new ReparationECar();
        $formAjoutReparationC = $this->createForm(ReparationECarType::class, $ajoutReparationC);
        $formAjoutReparationC->handleRequest($request);
        if ($formAjoutReparationC->isSubmitted() && $formAjoutReparationC->isValid()){
            $ajoutReparationC->setVoiture($car);
            $ajoutReparationC->setEtat('rouge');
            $entityManager->persist($ajoutReparationC);
            $entityManager->flush();
            $this->addFlash('success', 'La réparation a été ajouté');
            $route = $request->headers->get('referer');
            return $this->redirect($route);
        };

        //Creation d'une infos ==> carrosserie
        $infosC = new InfosCarosserie();
        $formC = $this->createForm(InfosCarosserieType::class, $infosC);
        $formC->handleRequest($request);
        if ($formC->isSubmitted() && $formC->isValid()){
            $infosC->setModeleVoiture($modele);
            $infosC->setMarqueVoiture($marque);
            $entityManager->persist($infosC);
            $entityManager->flush();
            $this->addFlash('success', "L'infos a été rajoutée");
            $route = $request->headers->get('referer');
            return $this->redirect($route);
        };

        return $this->render('home/reparationC.html.twig', [
            "car" => $car,
            "repair" => $repair,
            "formAjoutReparationC" => $formAjoutReparationC->createView(),
            "formReparationC" => $formReparationC->createView(),
            'formC' => $formC->createView(),
            'listeinfosC' => $listeinfosC
        ]);

    }



    /**
     * @Route("/reparationC/jaune/{id}", name="jauneC")
     */

    public function changementEtatjauneC(ReparationECar $reparation, EntityManagerInterface $entityManager, Request $request){

        $reparation->setEtat('jaune');
        $reparation->setDateDebut(new \DateTime());
        $entityManager->persist($reparation);
        $entityManager->flush();
        $this->addFlash('success', "L'etat de la reparation a été modifié");
        $route = $request->headers->get('referer');
        return $this->redirect($route);
    }


    /**
     * @Route("/reparationC/vert/{id}", name="vertC")
     */

    public function changementEtatvertC(ReparationECar $reparation, EntityManagerInterface $entityManager, Request $request){

        $reparation->setEtat('vert');
        $reparation->setDateFin(new \DateTime());
        $entityManager->persist($reparation);
        $entityManager->flush();
        $this->addFlash('success', "L'etat de la reparation a été modifié");
        $route = $request->headers->get('referer');
        return $this->redirect($route);
    }

    /**
     * @Route("/reparationC/rouge/{id}", name="rougeC")
     */

    public function changementEtatrougeC(ReparationECar $reparation, EntityManagerInterface $entityManager, Request $request){

        $reparation->setEtat('rouge');
        $entityManager->persist($reparation);
        $entityManager->flush();
        $this->addFlash('success', "L'etat de la reparation a été modifié");
        $route = $request->headers->get('referer');
        return $this->redirect($route);
    }


    /**
     * @Route("/reparationC/delete/{id}", name="deleteReparationC")
     */
    public function deleteReparationC (ReparationECar $reparation, EntityManagerInterface $entityManager, Request $request){

        //Si l'article n'est pas trouvé
        if (!$reparation){
            throw $this->createNotFoundException("La réparation n'existe pas");}

        //Si l'article existe

        $entityManager->remove($reparation);
        $entityManager->flush();
        $this->addFlash('success', 'La réparation a été supprimé !');
        $route = $request->headers->get('referer');
        return $this->redirect($route);
    }






    /***********************************************
     * *********************************************
     * Gestion des Voitures
     *
     * *********************************************
     * *********************************************
     * *********************************************
     */


    /**
     * @Route("/supp/{id}", name = "suppVoiture")
     */

    public function deleteVoiture ($id, EntityManagerInterface $entityManager, Request $request){
        $voiture = $entityManager->getRepository(Voiture::class)->findOneBy(['id'=> $id]);
        $entityManager->remove($voiture);
        $entityManager->flush();
        $this->addFlash('success', 'La voiture a été supprimé !');
        return $this->redirectToRoute('home');
    }

    /**
     * @Route("/retourgarage/{id}", name="retourG")
     */

    public function retourGarage(Voiture $voiture, EntityManagerInterface $entityManager){

        $voiture->setEtat("rouge");
        $entityManager->persist($voiture);
        $entityManager->flush();
        $this->addFlash('success', "La voiture est au garage !");
        return $this->redirectToRoute('home');
    }

    /**
     * @Route("/departgarage/{id}", name="departG")
     */

    public function departGarage(Voiture $voiture, EntityManagerInterface $entityManager){

        $voiture->setEtat("vert");
        $entityManager->persist($voiture);
        $entityManager->flush();
        $this->addFlash('success', "La voiture a été livrée !");
        return $this->redirectToRoute('home');
    }




}

