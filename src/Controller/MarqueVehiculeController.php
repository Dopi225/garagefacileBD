<?php

namespace App\Controller;

use App\Entity\MarqueVehicule;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class MarqueVehiculeController extends AbstractController
{
    #[Route('/marque/vehicule', name: 'app_marque_vehicule')]
    public function index(EntityManagerInterface $entityManager): JsonResponse
    {
try {
        $marqueVehiculeRepo = $entityManager->getRepository(MarqueVehicule::class);
        $marqueVehicule = $marqueVehiculeRepo->findAll();

        $marqueData = [];

        if (!$marqueVehicule) {
            throw new NotFoundHttpException("Marque Vehicule  introuvable.");       
        }else {
            foreach ($marqueVehicule as $marque) {
                $marqueData[] = [
                    "id" => $marque->getIdMarque(),
                    "libelle" => $marque->getMarque(),
                ];
            }
            return new JsonResponse($marqueData);
        }
    } catch (\Exception $e) {
        $errorMessage = "Une erreur est survenue : " . $e->getMessage();
        throw new BadRequestHttpException($errorMessage);
    }
        
    }
    

}
