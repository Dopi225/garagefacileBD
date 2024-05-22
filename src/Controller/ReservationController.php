<?php

namespace App\Controller;

use App\Entity\Client;
use App\Entity\Garage;
use App\Entity\Statut;
use App\Entity\Service;
use App\Entity\Vehicule;
use App\Entity\Reservation;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class ReservationController extends AbstractController
{
    private function checkFormFields(array $data): void {
        foreach ($data as $key => $value) {
            // Vérifie si le champ est vide ou nul
            if ($value === null || $value === '') {
                throw new NotFoundHttpException("Le champ '$key' ne peut pas être vide ou nul.");
            }
        }
    }
    #[Route('/reservation/precis/{id}', name: 'app_reservation')]
    public function index(EntityManagerInterface $entityManager, int $id): JsonResponse
    {
        try {
            $reservRepo = $entityManager->getRepository(Reservation::class);
            $reservation = $reservRepo->findOneBy(["idReserv" => $id]);
            if (!$reservation) {
                throw new NotFoundHttpException("Reservation introuvable.");
            }
            $resData = [];
            if ($reservation) {
                $resData = [
                    "idReserv" => $reservation->getIdReserv(),
                    "dateRDV" => $reservation->getDateRDV()->format("Y-m-d"),
                    "heureRDV" => $reservation->getHeureRDV()->format("H:i"),
                    "idGarage" => $reservation->getGarage()->getIdGarage(),
                    "idService" => $reservation->getService()->getIdService(),
                    "idClient" => $reservation->getClient()->getIdClient(),
                    "remarque" => $reservation->getRemarque(),
                    "idVoiture" => $reservation->getVehiculeClient()->getidVehicule(),


                ];
                return new JsonResponse($resData);
            }else {
                return new JsonResponse(["message" => "Aucune Reservation trouvé avec cet id spécifié"]);
            }
        } catch (\Exception $e) {
            $errorMessage = "Une erreur est survenue : " . $e->getMessage();
            throw new BadRequestHttpException($errorMessage);
        }
    }
    #[Route('/reservation/create', name: 'app_reservation_create' , methods:"POST")]
    public function createRes(EntityManagerInterface $entityManager, Request $request): JsonResponse
    {
        try {
            $statutRepo = $entityManager->getRepository(Statut::class);
            $statutDef = $statutRepo->findOneBy(["idStatut" => 1]);
            if (!$statutDef) {
                throw new NotFoundHttpException("Statut introuvable.");
            }

            $infoData = $request->request->all();
$this->checkFormFields($infoData);
            $date = new \DateTime($infoData['dateRDV']);
            $heure = new \DateTime($infoData['heureRDV']);

            $garageRepo = $entityManager->getRepository(Garage::class);
            $garage = $garageRepo->findOneBy(["idGarage" => $infoData["idGarage"]]);
            if (!$garage) {
                throw new NotFoundHttpException("Garage introuvable.");
            }

            $serviceRepo = $entityManager->getRepository(Service::class);
            $service = $serviceRepo->findOneBy(["idService" => $infoData["idService"]]);
            if (!$service) {
                throw new NotFoundHttpException("Service introuvable.");
            }

            $clientRepo = $entityManager->getRepository(Client::class);
            $client = $clientRepo->findOneBy(["idClient" => $infoData["idClient"]]);
            if (!$client) {
                throw new NotFoundHttpException("Client introuvable.");
            }

            $vehiculeClient = $entityManager->getRepository(Vehicule::class);
            $vehicule = $vehiculeClient->findOneBy(["idVehicule" => $infoData["idVehicule"]]);
            if (!$vehicule) {
                throw new NotFoundHttpException("Véhicule introuvable.");
            }

            $reservation = new Reservation();
            $reservation->setDateRDV($date);
            $reservation->setHeureRDV($heure);
            $reservation->setClient($client);
            $reservation->setVehiculeClient($vehicule);
            $reservation->setGarage($garage);
            $reservation->setService($service);
            if(isset($infoData['remarque']) && !empty($infoData['remarque'])){
                $reservation->setRemarque($infoData['remarque']);
            }
            $reservation->setStatut($statutDef);
            $entityManager->persist($reservation);
            $entityManager->flush();

            return new JsonResponse(["message" => "Réservation ajoutée avec succès"]);
        } catch (\Exception $e) {
            $errorMessage = "Une erreur est survenue : " . $e->getMessage();
            throw new BadRequestHttpException($errorMessage);
        }
    }
    #[Route('/reservation/confirmation', name: 'app_reservation_confirmation' , methods:"POST")]
    public function confirmRes(EntityManagerInterface $entityManager, Request $request): JsonResponse
    {
        try {
            $conf = 2;
            $statutRepo = $entityManager->getRepository(Statut::class);
            $statutDef = $statutRepo->findOneBy(["idStatut" => $conf]);
            if (!$statutDef) {
                throw new NotFoundHttpException("Statut introuvable.");
            }

            $infoData = $request->request->all();
$this->checkFormFields($infoData);

            $reservRepo = $entityManager->getRepository(Reservation::class);

        
            $reservation = $reservRepo->findOneBy(["idReserv" => $infoData["id"]]);

            if (!$reservation) {
                throw new NotFoundHttpException("Reservation introuvable.");
            }
            
        
            if(isset($statutDef) &&  !empty($statutDef)){
                $reservation->setStatut($statutDef);
            }

            $entityManager->persist($reservation);
            $entityManager->flush();

            return new JsonResponse(["message" => "Reservation confirmer"]);
        } catch (\Exception $e) {
            $errorMessage = "Une erreur est survenue : " . $e->getMessage();
            throw new BadRequestHttpException($errorMessage);
        }
    }

    #[Route('/reservation/update', name: 'app_reservation_update')]
    public function updateRes(EntityManagerInterface $entityManager, Request $request): JsonResponse
    {
        try {
            $infoData = $request->request->all();
$this->checkFormFields($infoData);
            $reservationRepository = $entityManager->getRepository(Reservation::class);
            $reservations = $reservationRepository->findOneBy(["idReserv" => $infoData['idReserv']]);
            $vehiculeClient = $entityManager->getRepository(Vehicule::class);
            $vehicule = $vehiculeClient->findOneBy(["idVehicule" => $infoData["idVehicule"]]);
            if (!$vehicule) {
                throw new NotFoundHttpException("Véhicule introuvable.");
            }
            if (!$reservations) {
                throw new NotFoundHttpException( "Reservation introuvable ");
            }
            $reservations->setRemarque($infoData['remarque']);
            $reservations->setVehiculeClient($vehicule);

            $entityManager->persist($reservations);
            $entityManager->flush();

            return new JsonResponse(["message" => "Reservation modifié avec succès"]);
        } catch (\Exception $e) {
            $errorMessage = "Une erreur est survenue : " . $e->getMessage();
            throw new BadRequestHttpException($errorMessage);
        }
        
    }
    #[Route('/reservation/annuler/{id}', name: 'app_reservation_annulation')]
    public function annulerRes(EntityManagerInterface $entityManager, int $id): JsonResponse
    {
        try {
            $reservationRepository = $entityManager->getRepository(Reservation::class);
            $reservations = $reservationRepository->findOneBy(["idReserv" => $id]);
            if (!$reservations) {
                throw new NotFoundHttpException("Reservation introuvable.");
            }else {
                $statutRepo = $entityManager->getRepository(Statut::class);
                $statutDef = $statutRepo->findOneBy(["idStatut" => 3]);
                $reservations->setStatut($statutDef);
                $entityManager->persist($reservations);
                $entityManager->flush();
                return new JsonResponse(["message" => "Reservation annulé avec succès"]);
            }
        } catch (\Exception $e) {
            $errorMessage = "Une erreur est survenue : " . $e->getMessage();
            throw new BadRequestHttpException($errorMessage);
        }
    }

    #[Route("/api/reservation/attente/{id}", name:"api_reservation_attente_client" )]
    public function getReservationsAttenteClient(EntityManagerInterface $entityManager, int $id): JsonResponse
    {
        try {
            $clientRepository = $entityManager->getRepository(Reservation::class);
            $clients = $clientRepository->findReservationsAttenteByClientId($id);
            if (!$clients) {
                throw new NotFoundHttpException("Reservation introuvable.");
            }else {
                
                $clientData = [];
                foreach ($clients as $client) {
                    $idReserv = $client['idReserv'];
                    // Vérifiez si la réservation existe déjà dans le tableau
                    if (!isset($clientData[$idReserv])) {
                        // Si elle n'existe pas, ajoutez-la au tableau
                        $clientData[$idReserv] = [
                            "idReserv" => $idReserv,
                            "remarque" => $client['remarque'],
                            "dateReserv" => $client['dateRDV']->format('Y-m-d'),
                            "heureReserv" => $client['heureRDV']->format('H:i'),
                            "statut" => $client['libelleStatut'],
                            "Tarif" => $client['tarifMinimumHT'],
                            "garage" => $client['nomEtablissement'],
                            "adresse" => $client['adresse'],
                            "ville" => $client['ville'],
                            "codePostal" => $client['cp'],
                            "nomclient" => $client['nomClient'],
                            "prenomClient"=> $client['prenomClient'],
                            "telClient"=> $client['telephoneClient'],
                            "emailClient"=> $client['emailClient'],
                            "service" => $client['libelleService'],
                            "marque" => $client['marque'],
                            "immatriculation" => $client['immatriculation'],
                        ];
                    }
                }

            }

            return new JsonResponse($clientData);
        } catch (\Exception $e) {
            $errorMessage = "Une erreur est survenue : " . $e->getMessage();
            throw new BadRequestHttpException($errorMessage);
        }
    }
    #[Route('/reservation/verifService/{id}/{id2}', name: 'app_reservation_verif')]
    public function verif(EntityManagerInterface $entityManager, $id, $id2): JsonResponse
    {
        
        try {
            $date = new \DateTime(); 
            $id3 = $date->format('Y-m-d');
            $reservationRepo = $entityManager->getRepository(Reservation::class);
            $query = $entityManager->createQuery(
                'SELECT r FROM App\Entity\Reservation r WHERE r.client = :clientId AND r.service = :serviceId AND r.dateRDV >= :dateVerif'
            )->setParameter('clientId', $id)
            ->setParameter('serviceId', $id2)
            ->setParameter('dateVerif', $id3);
            
            $reservations = $query->getResult();
            
            $reservationData = [];
            if (!$reservations) {
                return new JsonResponse(["message" => "Aucune Réservation "]);
            }else {
                foreach ($reservations as $reservation) {
                    $reservationData = [ "dateReserv" => $reservation->getDateRDV()->format('Y-m-d'),
                    "heureReserv" => $reservation->getHeureRDV()->format('H:i'),
                ];
                }
            }
            return new JsonResponse($reservationData);
        } catch (\Exception $e) {
            $errorMessage = "Une erreur est survenue : " . $e->getMessage();
            throw new BadRequestHttpException($errorMessage);
        }
    }
    #[Route('/reservation/stat/{id}', name: 'app_reservation_stat')]
    public function stat(EntityManagerInterface $entityManager, int $id): JsonResponse
    {
    try {
        

        $resData = [];
        $reservRepo = $entityManager->getRepository(Reservation::class);

     
        $reservation = $reservRepo->findBy([
            "garage" => $id,
            "statut" => [2, 6, 5]
        ]);        
        if (!$reservation) {
            throw new NotFoundHttpException("Reservation introuvable.");
        }else {
            foreach ($reservation as $res) {
                $resData[] = [
                    "service" => $res->getService()->getLibelleService(),
                    "time" => $res->getHeureRDV()->format('H:i'),
                ];
            }
        }
        

        return new JsonResponse($resData);
    } catch (\Exception $e) {
        $errorMessage = "Une erreur est survenue : " . $e->getMessage();
        throw new BadRequestHttpException($errorMessage);
    }

    }
    #[Route('/reservation/voiture/{id}', name: 'app_reservation_voiture')]
    public function voit(EntityManagerInterface $entityManager, int $id): JsonResponse
    {
    try {
        

        $reservRepo = $entityManager->getRepository(Reservation::class);

        $id2 = 2;
        $reservation = $reservRepo->findBy(["vehiculeClient" => $id, "statut" => $id2]);        
        if (!$reservation) {
            return new JsonResponse(["message" => "Aucune Réservation "]);
        }else {
            return new JsonResponse(["message" => "Réservation existante"]);
        }
        

    } catch (\Exception $e) {
        $errorMessage = "Une erreur est survenue : " . $e->getMessage();
        throw new BadRequestHttpException($errorMessage);
    }

    }

    #[Route("/api/reservation/avenirClient/{id}", name:"api_reservation_avenir_client" )]
    public function getReservationsAVenirClient(EntityManagerInterface $entityManager, int $id): JsonResponse
    {
    try {
        $clientRepository = $entityManager->getRepository(Reservation::class);
        $clients = $clientRepository->findReservationsVenirByClientId($id);
        if (!$clients) {
            throw new NotFoundHttpException("Reservation introuvable.");
        }else {
            
            $clientData = [];
            foreach ($clients as $client) {
                $idReserv = $client['idReserv'];
                // Vérifiez si la réservation existe déjà dans le tableau
                if (!isset($clientData[$idReserv])) {
                    // Si elle n'existe pas, ajoutez-la au tableau
                    $clientData[$idReserv] = [
                        "idReserv" => $idReserv,
                        "idService" => $client['idService'],
                        "remarque" => $client['remarque'],
                        "dateReserv" => $client['dateRDV']->format('Y-m-d'),
                        "heureReserv" => $client['heureRDV']->format('H:i'),
                        "statut" => $client['libelleStatut'],
                        "Tarif" => $client['tarifMinimumHT'],
                        "garage" => $client['nomEtablissement'],
                        "adresse" => $client['adresse'],
                        "ville" => $client['ville'],
                        "codePostal" => $client['cp'],
                        "nomclient" => $client['nomClient'],
                        "prenomClient"=> $client['prenomClient'],
                        "telClient"=> $client['telephoneClient'],
                        "emailClient"=> $client['emailClient'],
                        "service" => $client['libelleService'],
                        "marque" => $client['marque'],
                        "immatriculation" => $client['immatriculation'],
                    ];
                }
            }
            $clientData = array_values($clientData);
        }

        return new JsonResponse($clientData);
    } catch (\Exception $e) {
        $errorMessage = "Une erreur est survenue : " . $e->getMessage();
        throw new BadRequestHttpException($errorMessage);
    }
    }
    #[Route("/api/reservation/passeClient/{id}", name:"api_reservation_passe_client")]
    public function getReservationsPasseClient(EntityManagerInterface $entityManager, int $id): JsonResponse
    {
    try {
        $clientRepository = $entityManager->getRepository(Reservation::class);
        $clients = $clientRepository->findReservationsPasseByClientId($id);
        if (!$clients) {
            throw new NotFoundHttpException("Reservation introuvable.");
        }else {
            $clientData = [];
            foreach ($clients as $client) {
                $idReserv = $client['idReserv'];
                // Vérifiez si la réservation existe déjà dans le tableau
                if (!isset($clientData[$idReserv])) {
                    // Si elle n'existe pas, ajoutez-la au tableau
                    $clientData[$idReserv] = [
                        "idReserv" => $idReserv,
                        "remarque" => $client['remarque'],
                        "dateReserv" => $client['dateRDV']->format('Y-m-d'),
                        "heureReserv" => $client['heureRDV']->format('H:i'),
                        "statut" => $client['libelleStatut'],
                        "Tarif" => $client['tarifMinimumHT'],
                        "garage" => $client['nomEtablissement'],
                        "adresse" => $client['adresse'],
                        "ville" => $client['ville'],
                        "codePostal" => $client['cp'],
                        "nomclient" => $client['nomClient'],
                        "prenomClient"=> $client['prenomClient'],
                        "telClient"=> $client['telephoneClient'],
                        "emailClient"=> $client['emailClient'],
                        "service" => $client['libelleService'],
                        "marque" => $client['marque'],
                        "immatriculation" => $client['immatriculation'],
                    ];
                }
            }
            $clientData = array_values($clientData);

        }

        return new JsonResponse($clientData);
    } catch (\Exception $e) {
        $errorMessage = "Une erreur est survenue : " . $e->getMessage();
        throw new BadRequestHttpException($errorMessage);
    }
        // Votre logique pour récupérer les réservations à venir pour un client spécifié
    }
    #[Route("/reservation/Garage/{id}/{id2}", name:"api_reservation_date_garage")]
    public function getReservationsDateGarage(EntityManagerInterface $entityManager, int $id, string $id2): JsonResponse
    {
    try {
     
        $clientRepository = $entityManager->getRepository(Reservation::class);
        $clients = $clientRepository->findReservationsDate($id,$id2);
        if (!$clients) {
            throw new NotFoundHttpException("Reservation introuvable.");
        }else {
            $clientData = [];
            foreach ($clients as $client) {
                $idReserv = $client['idReserv'];
                // Vérifiez si la réservation existe déjà dans le tableau
                if (!isset($clientData[$idReserv])) {
                    // Si elle n'existe pas, ajoutez-la au tableau
                    $clientData[$idReserv] = [
                        "idReserv" => $idReserv,
                        "remarque" => $client['remarque'],
                        "dateReserv" => $client['dateRDV']->format('Y-m-d'),
                        "heureReserv" => $client['heureRDV']->format('H:i'),
                        "statut" => $client['libelleStatut'],
                        "Tarif" => $client['tarifMinimumHT'],
                        "garage" => $client['nomEtablissement'],
                        "adresse" => $client['adresse'],
                        "ville" => $client['ville'],
                        "codePostal" => $client['cp'],
                        "nomclient" => $client['nomClient'],
                        "prenomClient"=> $client['prenomClient'],
                        "telClient"=> $client['telephoneClient'],
                        "emailClient"=> $client['emailClient'],
                        "service" => $client['libelleService'],
                        "marque" => $client['marque'],
                        "immatriculation" => $client['immatriculation'],
                    ];
                }
            }
            $clientData = array_values($clientData);
        }

        return new JsonResponse($clientData);
    } catch (\Exception $e) {
        $errorMessage = "Une erreur est survenue : " . $e->getMessage();
        throw new BadRequestHttpException($errorMessage);
    }
        // Votre logique pour récupérer les réservations à venir pour un client spécifié
    }
    #[Route("/api/reservation/avenirGarage/{id}", name:"api_reservation_avenir_garage")]
    public function getReservationsAVenirGarage(EntityManagerInterface $entityManager, int $id): JsonResponse
    {
    try {
     
        $clientRepository = $entityManager->getRepository(Reservation::class);
        $clients = $clientRepository->findReservationsVenirByGarageId($id);
        if ($clients == null) {
            return  new JsonResponse(["message" => "Aucune reservation"]);
        }else {
            $clientData = [];
            foreach ($clients as $client) {
                $idReserv = $client['idReserv'];
                // Vérifiez si la réservation existe déjà dans le tableau
                if (!isset($clientData[$idReserv])) {
                    // Si elle n'existe pas, ajoutez-la au tableau
                    $clientData[$idReserv] = [
                        "idReserv" => $idReserv,
                        "remarque" => $client['remarque'],
                        "dateReserv" => $client['dateRDV']->format('Y-m-d'),
                        "heureReserv" => $client['heureRDV']->format('H:i'),
                        "statut" => $client['libelleStatut'],
                        "Tarif" => $client['tarifMinimumHT'],
                        "garage" => $client['nomEtablissement'],
                        "adresse" => $client['adresse'],
                        "ville" => $client['ville'],
                        "codePostal" => $client['cp'],
                        "nomclient" => $client['nomClient'],
                        "prenomClient"=> $client['prenomClient'],
                        "telClient"=> $client['telephoneClient'],
                        "emailClient"=> $client['emailClient'],
                        "service" => $client['libelleService'],
                        "marque" => $client['marque'],
                        "immatriculation" => $client['immatriculation'],
                    ];
                }
            }
            $clientData = array_values($clientData);
        }

        return new JsonResponse($clientData);
    } catch (\Exception $e) {
        $errorMessage = "Une erreur est survenue : " . $e->getMessage();
        throw new BadRequestHttpException($errorMessage);
    }
        // Votre logique pour récupérer les réservations à venir pour un client spécifié
    }
    #[Route("/api/reservation/passeGarage/{id}", name:"api_reservation_passe_garage")]
    public function getReservationsPasseGarage(EntityManagerInterface $entityManager, int $id): JsonResponse
    {
    try {
        $clientRepository = $entityManager->getRepository(Reservation::class);
        $clients = $clientRepository->findReservationsPasseByGarageId($id);
        if (!$clients) {
            throw new NotFoundHttpException("Reservation introuvable.");
        }else {
            $clientData = [];
            foreach ($clients as $client) {
                $idReserv = $client['idReserv'];
                // Vérifiez si la réservation existe déjà dans le tableau
                if (!isset($clientData[$idReserv])) {
                    // Si elle n'existe pas, ajoutez-la au tableau
                    $clientData[$idReserv] = [
                        "idReserv" => $idReserv,
                        "remarque" => $client['remarque'],
                        "dateReserv" => $client['dateRDV']->format('Y-m-d'),
                        "heureReserv" => $client['heureRDV']->format('H:i'),
                        "statut" => $client['libelleStatut'],
                        "Tarif" => $client['tarifMinimumHT'],
                        "garage" => $client['nomEtablissement'],
                        "adresse" => $client['adresse'],
                        "ville" => $client['ville'],
                        "codePostal" => $client['cp'],
                        "nomclient" => $client['nomClient'],
                        "prenomClient"=> $client['prenomClient'],
                        "telClient"=> $client['telephoneClient'],
                        "emailClient"=> $client['emailClient'],
                        "service" => $client['libelleService'],
                        "marque" => $client['marque'],
                        "immatriculation" => $client['immatriculation'],
                    ];
                }
            }
            $clientData = array_values($clientData);
        }

        return new JsonResponse($clientData);
    } catch (\Exception $e) {
        $errorMessage = "Une erreur est survenue : " . $e->getMessage();
        throw new BadRequestHttpException($errorMessage);
    }
       
    }
    #[Route('/reservation/present/{id}', name: 'app_reservation_present')]
    public function present(EntityManagerInterface $entityManager, int $id): JsonResponse
    {
    try {
        $statutRepo = $entityManager->getRepository(Statut::class);
        $statutDef = $statutRepo->findOneBy(["idStatut" => 5]);
        if (!$statutDef) {
            throw new NotFoundHttpException("Statut introuvable.");
        }


        $reservRepo = $entityManager->getRepository(Reservation::class);

     
        $reservation = $reservRepo->findOneBy(["idReserv" => $id]);
        if (!$reservation) {
            throw new NotFoundHttpException("Reservation introuvable.");
        }
        
        
        $reservation->setStatut($statutDef);
        $entityManager->persist($reservation);
        $entityManager->flush();

        return new JsonResponse(["message" => "Présence à la Reservation confirmer"]);
    } catch (\Exception $e) {
        $errorMessage = "Une erreur est survenue : " . $e->getMessage();
        throw new BadRequestHttpException($errorMessage);
    }

    }
    #[Route('/reservation/absent/{id}', name: 'app_reservation_absent')]
    public function absent(EntityManagerInterface $entityManager, int $id): JsonResponse
    {
    try {
        $statutRepo = $entityManager->getRepository(Statut::class);
        $statutDef = $statutRepo->findOneBy(["idStatut" => 6]);
        if (!$statutDef) {
            throw new NotFoundHttpException("Statut introuvable.");
        }


        $reservRepo = $entityManager->getRepository(Reservation::class);

     
        $reservation = $reservRepo->findOneBy(["idReserv" => $id]);
        if (!$reservation) {
            throw new NotFoundHttpException("Reservation introuvable.");
        }
        
        
        $reservation->setStatut($statutDef);
        $entityManager->persist($reservation);
        $entityManager->flush();

        return new JsonResponse(["message" => "Absence à la Reservation confirmer"]);
    } catch (\Exception $e) {
        $errorMessage = "Une erreur est survenue : " . $e->getMessage();
        throw new BadRequestHttpException($errorMessage);
    }

    }
    #[Route("/reservation/delete-attente", name: "delete_attente_reservations")]
    public function deleteAttenteReservations(EntityManagerInterface $entityManager): JsonResponse
    {
    try {
        $reservationRepository = $entityManager->getRepository(Reservation::class);

        // Récupérer toutes les réservations en attente
        $attenteReservations = $reservationRepository->findBy(["statut" => 1]);
        if (!$attenteReservations) {
            throw new NotFoundHttpException("Reservation introuvable.");
        } 


        // Parcourir toutes les réservations en attente
        foreach ($attenteReservations as $reservation) {
           
                $entityManager->remove($reservation); // Supprimer la réservation
            
        }

        $entityManager->flush(); // Appliquer les suppressions

        return new JsonResponse(["message" => "Les réservations en attente ont été supprimées avec succès."]);
    } catch (\Exception $e) {
        $errorMessage = "Une erreur est survenue : " . $e->getMessage();
        throw new BadRequestHttpException($errorMessage);
    }
    }


}
