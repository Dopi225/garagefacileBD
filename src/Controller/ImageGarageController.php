<?php

namespace App\Controller;

use App\Entity\Garage;
use App\Entity\ImageGarage;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class ImageGarageController extends AbstractController
{
    private function checkFormFields(array $data): void {
        foreach ($data as $key => $value) {
            // Vérifie si le champ est vide ou nul
            if ($value === null || $value === '') {
                throw new NotFoundHttpException("Le champ '$key' ne peut pas être vide ou nul.");
            }
        }
    }
    #[Route('/image/garage', name: 'app_image_garage')]
    public function index(): JsonResponse
    {
try {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/ImageGarageController.php',
        ]);
    } catch (\Exception $e) {
        $errorMessage = "Une erreur est survenue : " . $e->getMessage();
        throw new BadRequestHttpException($errorMessage);
    }
    }

    //images d'un garage
    #[Route('/garage/image/{id}', name: 'app_garage_image_spécifié')]
    public function image(EntityManagerInterface $entityManager,$id) : JsonResponse
    {
try {   
        $imageGarageRepository = $entityManager->getRepository(ImageGarage::class);

        // Utilisation de la méthode findBy() pour récupérer les images du garage spécifié
        $images = $imageGarageRepository->findOneBy(['garage' => $id]);
    $imageData = [];
        // Vérifier si des images ont été trouvés
        if ($images) {
            // Construire un tableau avec les données des images
            
            
                $imageData= [
                    "idimage" => $images->getIdimage(),
                    "url_image" => $images->getUrlImage(),
                    "description_image" => $images->getDescriptionImage(),
                    "garage" => [
                        "idgarage" => $images->getGarage()->getIdgarage(),]
                ];
            
    
            // Retourner les données des images au format JSON
            return new JsonResponse($imageData);
        } else {
            // Si aucun image n'est trouvé pour le garage spécifié, renvoyer un message d'erreur
            throw new NotFoundHttpException("Image introuvable.");
        }
    } catch (\Exception $e) {
        $errorMessage = "Une erreur est survenue : " . $e->getMessage();
        throw new BadRequestHttpException($errorMessage);
    }
    }

    //inserer les images d'un garage
    #[Route('/image/create', name: 'app_garage_image_create')]
    public function create(EntityManagerInterface $entityManager, Request $request) : JsonResponse
    {
try {
        // Récupérer les données de la requête
        $infoData = $request->request->all();
$this->checkFormFields($infoData);
        $image = new ImageGarage();
        $garageRepo = $entityManager->getRepository(Garage::class);
        $garage = $garageRepo->findOneBy(["idGarage" => $infoData["idGarage"]]);
        if (!$garage) {
            throw new NotFoundHttpException("Garage introuvable.");
        }
        
        $image->setUrlImage($infoData['url_image']);
        $image->setDescriptionImage($infoData['description_image']);
        $image->setGarage($garage);

        $entityManager->persist($image);
        $entityManager->flush();

        return new JsonResponse(["message" => "Image ajoutée avec succès"]);
    } catch (\Exception $e) {
        $errorMessage = "Une erreur est survenue : " . $e->getMessage();
        throw new BadRequestHttpException($errorMessage);
    }
    }

    //modifier les images d'un garage
    #[Route('/garage/image/update/{id}', name: 'app_garage_image_update')]
    public function update(EntityManagerInterface $entityManager, Request $request) : JsonResponse
    {
try {
        // Récupérer les données de la requête
        $infoData = $request->request->all();
$this->checkFormFields($infoData);
        $image = $entityManager->getRepository(ImageGarage::class)->findOneBy(["idImage" =>$infoData['id']]);
        if (!$image) {
            throw new NotFoundHttpException("image introuvable.");
        }
        $image->setUrlImage($infoData['url_image']);
        $image->setDescriptionImage($infoData['description_image']);

        $entityManager->persist($image);
        $entityManager->flush();

        return new JsonResponse(["message" => "Image ajoutée avec succès"]);
    } catch (\Exception $e) {
        $errorMessage = "Une erreur est survenue : " . $e->getMessage();
        throw new BadRequestHttpException($errorMessage);
    }
    }

    //supprimer les images d'un garage
    #[Route('/garage/image/delete/{id}', name: 'app_garage_image_delete')]
    public function delete(EntityManagerInterface $entityManager, int $id) : JsonResponse
    {
try {
        $image = $entityManager->getRepository(ImageGarage::class)->findOneBy(["idImage" =>$id]);
        if (!$image) {
            throw new NotFoundHttpException("image introuvable.");
        }
        
        $entityManager->remove($image);
        $entityManager->flush();

        return new JsonResponse(["message" => "Image supprimé"]);
    } catch (\Exception $e) {
        $errorMessage = "Une erreur est survenue : " . $e->getMessage();
        throw new BadRequestHttpException($errorMessage);
    }
    }
}
