<?php

namespace App\Controller;

use App\Entity\Garage;
use App\Entity\Disponibilite;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class DisponibiliteController extends AbstractController
{
    private function checkFormFields(array $data): void {
        foreach ($data as $key => $value) {
            // Vérifie si le champ est vide ou nul
            if ($value === null || $value === '') {
                throw new NotFoundHttpException("Le champ '$key' ne peut pas être vide ou nul.");
            }
        }
    }
   
    #[Route('/disponibilite/{id}', name: 'app_disponibilite_garage')]
    public function dispoGarage(EntityManagerInterface $entityManager,int $id){
        try {
        $calendrierRepository = $entityManager->getRepository(Disponibilite::class);
            
        $calendriers = $calendrierRepository->findBy(['garage' => $id]);
        $calendrierData = [];
        if (!$calendriers) {
            throw new NotFoundHttpException("Dispo introuvable.");
        }
        // Afficher les données des calendriers
        foreach ($calendriers as $calendrier) {
            $calendrierData[] = [
                "ID" => $calendrier->getIdCalendrier(),
                "DateRDV" => $calendrier->getDateRDV()->format('Y-m-d'),
                "NombreRDV" => $calendrier->getNombreRDV(),
                "HeureDebut" => $calendrier->getHeureDebut()->format('H:i:s'),
                "HeureFin" => $calendrier->getHeureFin()->format('H:i:s'),
                "DureeRDV" => $calendrier->getDureeRDV()
            ];
        }
        return new JsonResponse($calendrierData);
    } catch (\Exception $e) {
        $errorMessage = "Une erreur est survenue : " . $e->getMessage();
        throw new BadRequestHttpException($errorMessage);
    }
    }
    #[Route('/dispo/precis/{id}', name: 'app_disponibilite_precis')]
    public function dispoId(EntityManagerInterface $entityManager, int $id) {
        try {
        $calendrierRepository = $entityManager->getRepository(Disponibilite::class);
                
        $calendrier = $calendrierRepository->findOneBy(['idDispo' => $id]);
    
        if (!$calendrier) {
            throw new NotFoundHttpException("Dispo introuvable.");
        }
    
        $calendrierData = [
            "ID" => $calendrier->getIdCalendrier(),
            "DateRDV" => $calendrier->getDateRDV()->format('Y-m-d'),
            "NombreRDV" => $calendrier->getNombreRDV(),
            "HeureDebut" => $calendrier->getHeureDebut()->format('H:i:s'),
            "HeureFin" => $calendrier->getHeureFin()->format('H:i:s'),
            "DureeRDV" => $calendrier->getDureeRDV()
        ];
    
        return new JsonResponse($calendrierData);
    } catch (\Exception $e) {
        $errorMessage = "Une erreur est survenue : " . $e->getMessage();
        throw new BadRequestHttpException($errorMessage);
    }
    }
    
    
    #[Route('/dispo/garage', name: 'app_garage_date', methods: ['POST'])]

    public function dispoDate(EntityManagerInterface $entityManager,Request $request) : JsonResponse
    {
    try {
        $infoData= json_decode($request->getContent(), true) ;
        $id = $infoData['id'];
        $date = $infoData['date'];
        $calendrierRepository = $entityManager->getRepository(Disponibilite::class);
        $calendriers = $calendrierRepository->findDate($id,$date);
        $calendrierData = [];
        if (!$calendriers) {
            return  new JsonResponse(["message" => "Aucune Dispo"]);
        }
        // Afficher les données des calendriers
        foreach ($calendriers as $calendrier) {
            $calendrierData[] = [
                "ID" => $calendrier->getIdCalendrier(),
                "DateRDV" => $calendrier->getDateRDV(),
                "NombreRDV" => $calendrier->getNombreRDV(),
                "HeureDebut" => $calendrier->getHeureDebut(),
                "HeureFin" => $calendrier->getHeureFin(),
                "DureeRDV" => $calendrier->getDureeRDV()
            ];
        }
        return new JsonResponse($calendrierData);
    } catch (\Exception $e) {
        $errorMessage = "Une erreur est survenue : " . $e->getMessage();
        throw new BadRequestHttpException($errorMessage);
    }
    }
    #[Route('/disponibilite/dispoSup/{id}', name: 'app_disponibilite_garage_date')]

    public function dispoDateSup(EntityManagerInterface $entityManager,Request $request){
        try {
        $infoData = $request->request->all();
$this->checkFormFields($infoData);
        $calendrierRepository = $entityManager->getRepository(Disponibilite::class);
        $calendriers = $calendrierRepository->findDateSup($infoData['id'],$infoData['date']);
        if (!$calendriers) {
            throw new NotFoundHttpException("Dispo introuvable.");
        }
        $calendrierData = [];
        // Afficher les données des calendriers
        foreach ($calendriers as $calendrier) {
            $calendrierData[] = [
                "ID" => $calendrier->getIdCalendrier(),
                "DateRDV" => $calendrier->getDateRDV(),
                "NombreRDV" => $calendrier->getNombreRDV(),
                "HeureDebut" => $calendrier->getHeureDebut(),
                "HeureFin" => $calendrier->getHeureFin(),
                "TempsRDV" => $calendrier->getTempsRDV(),
                "DureeRDV" => $calendrier->getDureeRDV()
            ];
        }
        return new JsonResponse($calendrierData);
    } catch (\Exception $e) {
        $errorMessage = "Une erreur est survenue : " . $e->getMessage();
        throw new BadRequestHttpException($errorMessage);
    }
    }
    #[Route('/create/disponibilite', name: 'app_disponibilite_garage_create', methods:"POST")]
    public function Insertdispo(EntityManagerInterface $entityManager,Request $request){
        $infoData = $request->request->all();
$this->checkFormFields($infoData);
        $date = new \DateTime($infoData['date']);
        $debut = new \DateTime($infoData['heureDebut']);
        $fin = new \DateTime($infoData['heureFin']);
        $calendrier = new Disponibilite();
        $garageRepo = $entityManager->getRepository(Garage::class);
        $garage = $garageRepo->findOneBy(["idGarage" => $infoData["idGarage"]]);
        if (!$garage) {
            throw new NotFoundHttpException("Dispo introuvable.");
        }
        $calendrier->setDateRDV($date);
        $calendrier->setNombreRDV($infoData['nombre']);
        $calendrier->setHeureDebut($debut);
        $calendrier->setHeureFin($fin);
        $calendrier->setDureeRDV($infoData['dureeRDV']);
        $calendrier->setGarage($garage);
        $entityManager->persist($calendrier);
        $entityManager->flush();

        return new JsonResponse(["message" => "Disponibilité creer"]);


    }
    #[Route('/update/disponibilite', name: 'app_disponibilite_garage_update', methods:"POST")]
    public function updatedispo(EntityManagerInterface $entityManager,Request $request){
        try {
        $infoData = $request->request->all();
$this->checkFormFields($infoData);
        $calendrier = $entityManager->getRepository(Disponibilite::class)->findOneBy(["idDispo" =>$infoData['idDispo']]);
        if (!$calendrier) {
            throw new NotFoundHttpException("Dispo introuvable.");
        }
        $debut = new \DateTime($infoData['heureDebut']);
        $fin = new \DateTime($infoData['heureFin']);
        $calendrier->setNombreRDV($infoData['nombre']);
        $calendrier->setHeureDebut($debut);
        $calendrier->setHeureFin($fin);
        $calendrier->setDureeRDV($infoData['duree']);
        $entityManager->persist($calendrier);
        $entityManager->flush();

        return new JsonResponse(["message" => "Disponibilité modifier"]);
    } catch (\Exception $e) {
        $errorMessage = "Une erreur est survenue : " . $e->getMessage();
        throw new BadRequestHttpException($errorMessage);
    }


    }
    #[Route('/delete/disponibilite', name: 'app_disponibilite_garage_delete', methods:"POST")]
    public function deletedispo(EntityManagerInterface $entityManager,Request $request){
        try {
        $infoData = $request->request->all();
$this->checkFormFields($infoData);
        $calendrier = $entityManager->getRepository(Disponibilite::class)->findOneBy(["idDispo" => $infoData['idDispo']]);
        if (!$calendrier) {
            throw new NotFoundHttpException("Dispo introuvable.");
        }
        $entityManager->remove($calendrier);
        $entityManager->flush();

        return new JsonResponse(["message" => "Disponibilité supprimé"]);
    } catch (\Exception $e) {
        $errorMessage = "Une erreur est survenue : " . $e->getMessage();
        throw new BadRequestHttpException($errorMessage);
    }
    }
}
