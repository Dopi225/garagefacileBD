<?php

namespace App\Controller;

use App\Entity\Garage;
use App\Entity\HoraireExceptionnelle;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class HoraireExceptionnelleController extends AbstractController
{
    private function checkFormFields(array $data): void {
        foreach ($data as $key => $value) {
            // Vérifie si le champ est vide ou nul
            if ($value === null || $value === '') {
                throw new NotFoundHttpException("Le champ '$key' ne peut pas être vide ou nul.");
            }
        }
    }
    #[Route('/horaire/exceptionnelle', name: 'app_horaire_exceptionnelle')]
    public function index(): JsonResponse
    {
    try {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/HoraireExceptionnelleController.php',
        ]);
    } catch (\Exception $e) {
        $errorMessage = "Une erreur est survenue : " . $e->getMessage();
        throw new BadRequestHttpException($errorMessage);
    }
    }
    //afficher horaire exceptionnelle d'un garage
    #[Route('/horaire/expetionnelle/{id}', name: 'app_garage_horaire_spécifié')]
    public function Horaire(EntityManagerInterface $entityManager,$id) : JsonResponse
    {
    try {   
        $horaireGarageRepository = $entityManager->getRepository(HoraireExceptionnelle::class);

        // Utilisation de la méthode findBy() pour récupérer les horaires du garage spécifié
        $horaires = $horaireGarageRepository->findBy(['garage' => $id]);
    
        // Vérifier si des horaires ont été trouvés
        if (!$horaires) {
            throw new NotFoundHttpException("Garage introuvable.");
        }else {
            // Construire un tableau avec les données des horaires
            $horaireData = [];
            foreach ($horaires as $horaire) {
                $horaireData[] = [
                    "idHoraire" => $horaire->getIdHoraireExceptionnel(),
                    "Jour" => $horaire->getDateExceptionnel(),
                    "Ouverture" => $horaire->getOuverture(),
                    "Fermerture" => $horaire->getFermerture()
                ];
            }
    
            // Retourner les données des horaires au format JSON
            return new JsonResponse($horaireData);
        } 
    } catch (\Exception $e) {
        $errorMessage = "Une erreur est survenue : " . $e->getMessage();
        throw new BadRequestHttpException($errorMessage);
    }
    }
    
    //ajouter horaire exceptionnelle
    #[Route('/garage/horaire/exceptionnelle/create/{id}', name: 'app_garage_excpetionnelle_create'  , methods:"POST")]
    public function create(EntityManagerInterface $entityManager, Request $request) : JsonResponse
    {
    try {
        // Récupérer les données de la requête
        $infoData = $request->request->all();
$this->checkFormFields($infoData);
        $horaire = new HoraireExceptionnelle();
        $garageRepo = $entityManager->getRepository(Garage::class);
        $garage = $garageRepo->findOneBy(["idGarage" => $infoData["idGarage"]]);
        if (!$garage) {
            throw new NotFoundHttpException("Garage introuvable.");
        }
        $horaire->setDateExceptionnel($infoData['jour']);
        $horaire->setOuverture($infoData['ouverture']);
        $horaire->setFermerture($infoData['fermerture']);
        $horaire->setGarage($garage);
        $entityManager->persist($horaire);
        $entityManager->flush();

        return new JsonResponse(["message" => "Horaire ajouté avec succès"]);
    } catch (\Exception $e) {
        $errorMessage = "Une erreur est survenue : " . $e->getMessage();
        throw new BadRequestHttpException($errorMessage);
    }

    }
    //modifier horaire exceptionnelle

    #[Route('/garage/horaire/exceptionnelle/update/{id}', name: 'app_garage_exceptionnelle_update'  , methods:"POST")]
    public function update(EntityManagerInterface $entityManager, Request $request, $id) : JsonResponse
    {
    try {
        // Récupérer les données de la requête
        $infoData = $request->request->all();
$this->checkFormFields($infoData);
        $horaire =  $entityManager->getRepository(HoraireExceptionnelle::class)->findOneBy(["idHoraire" =>$infoData['id']]);
        if(!$horaire){
            throw new NotFoundHttpException("Horaire introuvable.");
        
        }
        $horaire->setDateExceptionnel($infoData['jour']);
        $horaire->setOuverture($infoData['ouverture']);
        $horaire->setFermerture($infoData['fermerture']);
        $entityManager->persist($horaire);
        $entityManager->flush();
        return new JsonResponse(["message" => "Horaire modifié avec succès"]);
    } catch (\Exception $e) {
        $errorMessage = "Une erreur est survenue : " . $e->getMessage();
        throw new BadRequestHttpException($errorMessage);
    }

    }

    //supprimer horaire exceptionnelle
    #[Route('/garage/horaire/delete/{id}', name: 'app_garage_horaire_delete'  , methods:"POST")]
    public function delete(EntityManagerInterface $entityManager, int $id) : JsonResponse
    {
    try {
        $horaire = $entityManager->getRepository(HoraireExceptionnelle::class)->findOneBy(["idHoraireExceptionnel" =>$id]);
        if(!$horaire){
            throw new NotFoundHttpException("Horaire introuvable.");
        
        }
        $entityManager->remove($horaire);
        $entityManager->flush();

        return new JsonResponse(["message" => "horaire esceptionnelle supprimé"]);
    } catch (\Exception $e) {
        $errorMessage = "Une erreur est survenue : " . $e->getMessage();
        throw new BadRequestHttpException($errorMessage);
    }
    }

}
