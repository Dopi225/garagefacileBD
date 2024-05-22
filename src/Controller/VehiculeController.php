<?php

namespace App\Controller;

use App\Entity\Client;
use App\Entity\Vehicule;
use App\Entity\Reservation;
use App\Entity\MarqueVehicule;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class VehiculeController extends AbstractController
{
    #[Route('/vehicule', name: 'app_vehicule')]
    public function index(EntityManagerInterface $entityManager): JsonResponse
    {
        $vehiculeRepository = $entityManager->getRepository(Vehicule::class);
        $vehicule = $vehiculeRepository->findAll();
        $options=[];
        foreach ($vehicule as $services) {
            $serviceData = [
                "id"=> $services->getIdVehicule(),
                "Marque"=> $services->getMarqueVehicule(),
            ];
            $options[] = $serviceData;
        }
        return new JsonResponse($options);
    }

    #[Route('/vehicule/{id}', name: 'app_vehicule_client')]
    public function vehicule(EntityManagerInterface $entityManager, int $id): JsonResponse
    {
        $vehiculeRepository = $entityManager->getRepository(Vehicule::class);
        $vehicule = $vehiculeRepository->findBy(["client" => $id]);
        $options=[];
        foreach ($vehicule as $services) {
            $serviceData = [
                "id"=> $services->getIdVehicule(),
                "Marque"=> $services->getMarqueVehicule()->getMarque(),
                "immatriculation" => $services->getImmatriculation(),
                "kilometrage"=> $services->getKilometrage(),
            ];
            $options[] = $serviceData;
        }
        return new JsonResponse($options);
    }


    //creer un vehicule
    #[Route('/create/vehicule', name: 'app_vehicule_create', methods: ['POST'])] //ok 
public function createVehicule(EntityManagerInterface $entityManager, Request $request)
{
        $infoData = $request->request->all();
        $marque = $infoData['marque'];
        $immatriculation = $infoData['immatriculation'];
        $kilom = $infoData['kilometrage'];
        $marqueRepo = $entityManager->getRepository(MarqueVehicule::class);
        $marque = $marqueRepo->findOneBy(["idMarque" => $marque]);
        $vehicule = new Vehicule();
        $ClientRepo = $entityManager->getRepository(Client::class);
        $client = $ClientRepo->findOneBy(["idClient" => $infoData['idClient']]);
        $vehicule->setMarqueVehicule($marque);
        $vehicule->setImmatriculation($immatriculation);
        $vehicule->setKilometrage($kilom);
        $vehicule->setClient($client);
        $entityManager->persist($vehicule);
        $entityManager->flush();
        
        return new JsonResponse(["message" => "vehicule creer"]);

    }



    //modifier un vehicule
    #[Route('/update/vehicule', name: 'app_vehicule_update' , methods:"POST")]
    public function updateVehicule(EntityManagerInterface $entityManager, Request $request)
    {
        $infoData = $request->request->all();
        $immatriculation = $infoData['immatriculation'];
        $kilom = $infoData['kilometrage'];
        $vehiculeRepo = $entityManager->getRepository(Vehicule::class);
        $vehicule = $vehiculeRepo->findOneBy(["idVehicule" => $infoData['id']]);
        $vehicule->setImmatriculation($immatriculation);
        $vehicule->setKilometrage($kilom);
        $entityManager->persist($vehicule);
        $entityManager->flush();
        
        return new JsonResponse(["message" => "immatriculation modifier"]);
    }

    // //supprimer un vehicule
    #[Route('/delete/vehicule/{id}', name: 'app_vehicule_delete' )]
    public function deleteVehicule(EntityManagerInterface $entityManager, $id)
    {
        $reservRepo = $entityManager->getRepository(Reservation::class);

        $reservations = $reservRepo->findBy(["vehiculeClient" => $id]);  

        if ($reservations) {
            foreach ($reservations as $reservation) {
                $entityManager->remove($reservation);
            }
            $entityManager->flush();
            $vehiculeRepo = $entityManager->getRepository(Vehicule::class);
            $vehicule = $vehiculeRepo->findOneBy(["idVehicule" => $id]);
            $entityManager->remove($vehicule);
            $entityManager->flush();
        }else {
            $vehiculeRepo = $entityManager->getRepository(Vehicule::class);
            $vehicule = $vehiculeRepo->findOneBy(["idVehicule" => $id]);
            $entityManager->remove($vehicule);
            $entityManager->flush(); 
        }

        return new JsonResponse(["message" => "voiture supprimer"]);
    }
}
