<?php

namespace App\Controller;

use App\Entity\Garage;
use App\Entity\Service;
use App\Entity\PrestationGarage;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class PrestationGarageController extends AbstractController
{
    private function checkFormFields(array $data): void {
        foreach ($data as $key => $value) {
            // Vérifie si le champ est vide ou nul
            if ($value === null || $value === '') {
                throw new NotFoundHttpException("Le champ '$key' ne peut pas être vide ou nul.");
            }
        }
    }

    //renvoie tout les garages qui ont comme pour prestation ce service
    #[Route('/prestation/garage/{id}', name: 'app_prestation_garage')]
    public function index(EntityManagerInterface $entityManager,int $id): JsonResponse
    {
    try {
        $prestationRepository = $entityManager->getRepository(PrestationGarage::class);
        $prestations = $prestationRepository->findBy(["garage"=>$id]);

        if (!$prestations) {
            throw new NotFoundHttpException("Prestations introuvable.");
        }else {
            $prestationData = [];
            foreach ($prestations as $prestation) {
                $prestationData[] = [
                    'id' => $prestation->getIdPrestation(),
                    "libelleService" => $prestation->getService()->getLibelleService(),
                    "duree" => $prestation->getDureeType(),
                    "tarif" => $prestation->getTarifMinimumHT(),
                    
                   
                ];
            }
            return new JsonResponse($prestationData, 200);
        }
    } catch (\Exception $e) {
        $errorMessage = "Une erreur est survenue : " . $e->getMessage();
        throw new BadRequestHttpException($errorMessage);
    }
    }


    //renvoie tout les garages d'une ville qui ont pour prestation ce service et dont les dispo commencent le jour de la date
    #[Route('/garageprecis/prestation', name: 'app_prestation_garage_precis', methods: ['POST'])]
    public function AllGarageServicePrecis(EntityManagerInterface $entityManager,Request $request): JsonResponse
    {
    try {
        $infoData = $request->request->all();
$this->checkFormFields($infoData);
        $id = $infoData['idService'];
        $date = $infoData['date'];
        $ville = $infoData['ville'];
        $prestationRepository = $entityManager->getRepository(PrestationGarage::class);
        $prestations = $prestationRepository->findGarageDate($ville,$id,$date);
        if (!$prestations) {
            throw new NotFoundHttpException("Prestations introuvable.");
        }else {
            $prestationData = [];
            $prestationIds = []; // Tableau pour stocker les identifiants des prestations déjà ajoutées
        
            foreach ($prestations as $prestation) {
                $idGarage = $prestation['idGarage'];
                // Vérifiez si l'identifiant de la prestation existe déjà dans le tableau des identifiants
                if (!in_array($idGarage, $prestationIds)) {
                    // Si elle n'existe pas, ajoutez-la au tableau des prestations
                    $prestationData[] = [
                        "idGarage" => $idGarage,
                        "libelleService" => $prestation['libelleService'],
                        "descriptionService" => $prestation['descriptionService'],
                        "Tarif" => $prestation['tarifMinimumHT'],
                        "NomEtablissement" => $prestation['nomEtablissement'],
                        "TelephoneEtablissement" => $prestation['telephoneEtablissement'],
                        "Adresse" => $prestation['adresse'],
                        "cp" => $prestation['cp'],
                        "ville" => $prestation['ville'],
                        "url_image" => $prestation['urlImage'],
                    ];
                    // Ajoutez l'identifiant de la prestation au tableau des identifiants
                    $prestationIds[] = $idGarage;
                }
            }
            return new JsonResponse($prestationData, 200);
        }
    } catch (\Exception $e) {
        $errorMessage = "Une erreur est survenue : " . $e->getMessage();
        throw new BadRequestHttpException($errorMessage);
    }
        
    }

    //ajouter prestation à un garage
    #[Route('/prestation/add', name: 'app_prestation_add' , methods:"POST")]
    public function addPrestation(EntityManagerInterface $entityManager, Request $request): JsonResponse
{
    try {
    $infoData = $request->request->all();
$this->checkFormFields($infoData);
    $idGarage = $infoData['idGarage'];
    $idServices = $infoData['idService'];
    $tarifs = $infoData['tarif'];
    $durees = $infoData['duree'];
    
    $garageRepository = $entityManager->getRepository(Garage::class);
    $garage = $garageRepository->findOneBy(["idGarage" => $idGarage]);
    
    if (!$garage) {
        throw new NotFoundHttpException("Garage introuvable.");
    }
    
    $serviceRepository = $entityManager->getRepository(Service::class);
    
    foreach ($idServices as $index => $idService) {
        $service = $serviceRepository->findOneBy(["idService" => $idService]);
        
        if (!$service) {
            throw new NotFoundHttpException("Service introuvable.");
        }
        
        $tarif = $tarifs[$index];
        $duree = $durees[$index];
        
        $prestation = new PrestationGarage();
        $prestation->setGarage($garage);
        $prestation->setService($service);
        $prestation->setTarifMinimumHT($tarif);
        $prestation->setDureeType($duree);
        $entityManager->persist($prestation);
    }
    
    $entityManager->flush();
    
    return new JsonResponse([
        'message' => 'Prestations ajoutées avec succès.',
    ], 200);
} catch (\Exception $e) {
    $errorMessage = "Une erreur est survenue : " . $e->getMessage();
    throw new BadRequestHttpException($errorMessage);
}
}


    //modifier prestation garage
    #[Route('/prestation/update', name: 'app_prestation_update' , methods:"POST")]
    public function updatePrestation(EntityManagerInterface $entityManager,Request $request): JsonResponse
    {
    try {
        $infoData = $request->request->all();
$this->checkFormFields($infoData);
        $idPrestation = $infoData['idPrestation'];
        $prestationRepository = $entityManager->getRepository(PrestationGarage::class);
        $prestation = $prestationRepository->findOneBy(["idPrestation" => $idPrestation]);
        if (!$prestation) {
            throw new NotFoundHttpException("Prestations introuvable.");
        } else {
            $tarif = $infoData['tarif'];
            $duree =$infoData['dureeType'];
            $prestation->setDureeType($duree);
            $prestation->setTarifMinimumHT($tarif);
            $entityManager->persist($prestation);
            $entityManager->flush();
            return new JsonResponse([
                'message' => 'Prestation modifiée',
            ], 200);
        }
    } catch (\Exception $e) {
        $errorMessage = "Une erreur est survenue : " . $e->getMessage();
        throw new BadRequestHttpException($errorMessage);
    }
    }

    //supprimer prestation garage
    #[Route('/prestation/delete/{id}', name: 'app_prestation_delete')]
    public function deletePrestation(EntityManagerInterface $entityManager,$id): JsonResponse
    {
    try {
        
        $idPrestation = $id;
        $prestationRepository = $entityManager->getRepository(PrestationGarage::class);
        $prestation = $prestationRepository->findOneBy(["idPrestation" => $idPrestation]);
        if (!$prestation) {
            throw new NotFoundHttpException("Prestations introuvable.");
        } 
        $entityManager->remove($prestation);
        $entityManager->flush();

        return new JsonResponse(["message" => "Prestation supprimé"]);
    } catch (\Exception $e) {
        $errorMessage = "Une erreur est survenue : " . $e->getMessage();
        throw new BadRequestHttpException($errorMessage);
    }
    }



}
