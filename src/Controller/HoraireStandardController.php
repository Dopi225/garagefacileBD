<?php

namespace App\Controller;

use App\Entity\Garage;
use App\Entity\HoraireStandard;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class HoraireStandardController extends AbstractController
{
    private function checkFormFields(array $data): void {
        foreach ($data as $key => $value) {
            // Vérifie si le champ est vide ou nul
            if ($value === null || $value === '') {
                throw new NotFoundHttpException("Le champ '$key' ne peut pas être vide ou nul.");
            }
        }
    }
    #[Route('/horaire/standard', name: 'app_horaire_standard')]
    public function index(): JsonResponse
    {
try {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/HoraireStandardController.php',
        ]);
    } catch (\Exception $e) {
        $errorMessage = "Une erreur est survenue : " . $e->getMessage();
        throw new BadRequestHttpException($errorMessage);
    }
    }

    #[Route('/garage/horaire/{id}', name: 'app_garage_horaire_spécifié')]
    public function Horaire(EntityManagerInterface $entityManager,$id) : JsonResponse
    {
try {   
        $horaireGarageRepository = $entityManager->getRepository(HoraireStandard::class);

        // Utilisation de la méthode findBy() pour récupérer les horaires du garage spécifié
        $horaires = $horaireGarageRepository->findBy(['garage' => $id]);
    
        // Vérifier si des horaires ont été trouvés
        if ($horaires) {
            // Construire un tableau avec les données des horaires
            $horaireData = [];
            foreach ($horaires as $horaire) {
                $horaireData[] = [
                    "idHoraire" => $horaire->getIdHoraire(),
                    "Jour" => $horaire->getJour(),
                    "Ouverture" => $horaire->getOuverture(),
                    "Fermerture" => $horaire->getFermerture()
                ];
            }
    
            // Retourner les données des horaires au format JSON
            return new JsonResponse($horaireData);
        } else {
                throw new NotFoundHttpException("Horaire introuvable.");
            
        }
    } catch (\Exception $e) {
        $errorMessage = "Une erreur est survenue : " . $e->getMessage();
        throw new BadRequestHttpException($errorMessage);
    }
    }

    //ajouter horaire garage
    #[Route('/horaire/create', name: 'app_garage_horaire_create'  , methods:"POST")]
    public function create(EntityManagerInterface $entityManager, Request $request): JsonResponse
    {
    try {
        $touteLaSemaineAjoute = false;
        $weekendAjoute = false;
        $joursSelectionnesAjoutes = false;
        $h1 = '';
        $h2 = '';
        $h3 = '';
        // Récupérer les données de la requête
        $infoData = $request->request->all();
$this->checkFormFields($infoData);
        $garageRepo = $entityManager->getRepository(Garage::class);
        $garage = $garageRepo->findOneBy(["idGarage" => $infoData["idGarage"]]);
        if (!$garage) {
            throw new NotFoundHttpException("Garage introuvable.");
        }
    
        // Initialiser une liste pour les jours déjà associés
        $joursAssocies = [];
    
        // Boucler sur chaque plage horaire ajoutée dans le formulaire
        for ($i = 0; $i < count($infoData['ouverture']); $i++) {
            $ouverture = new \DateTime($infoData['ouverture'][$i]);
            $fermeture = new \DateTime($infoData['fermeture'][$i]);
            $h1 = $ouverture;
            $h2 = $fermeture;
    
            // Vérifier si "Toute la semaine" est cochée
            if (isset($infoData['touteLaSemaine'][$i])&& !$touteLaSemaineAjoute) {
                if (!isset($joursAssocies['Toute la semaine'])) {
                    $touteLaSemaineAjoute = true;
                    $joursAssocies['Toute la semaine'] = true;
                    $jours = ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi'];
                    foreach ($jours as $jour) {
                        $horaire = new HoraireStandard();
                        $horaire->setOuverture($ouverture);
                        $horaire->setFermerture($fermeture);
                        $horaire->setGarage($garage);
                        $horaire->setJour($jour);
                        $entityManager->persist($horaire);
                        $entityManager->flush();
                        
                    }
                }
                
            } else {
                
                    if (!$joursSelectionnesAjoutes) {
                        $joursSelectionnesAjoutes = true;
                        // Sinon, utiliser les jours sélectionnés
                        if (isset($infoData['touteLaSemaine'][$i])&& $touteLaSemaineAjoute) {
                        foreach ($infoData['jour'] as $jour) {
                            for ($j = 1; $j < count($infoData['ouverture']); $j++) {

                                
                                $ouverture = new \DateTime($infoData['ouverture'][$j]);
                                $fermeture = new \DateTime($infoData['fermeture'][$j]);

                                if ( ( $fermeture != $h3) ) {
                                    $horaire = new HoraireStandard();
                                $horaire->setGarage($garage);
                                $horaire->setJour($jour);
                                $horaire->setOuverture($ouverture);
                                $horaire->setFermerture($fermeture);
                                $entityManager->persist($horaire);
                                }else {
                                    $horaire = new HoraireStandard();
                                $horaire->setGarage($garage);
                                $horaire->setJour($jour);
                                $horaire->setOuverture($ouverture);
                                $horaire->setFermerture($fermeture);
                                $entityManager->persist($horaire);
                                }

                                
                            }
                        }
                    }else {
                        foreach ($infoData['jour'] as $jour) {
                            for ($j = 0; $j < count($infoData['ouverture']); $j++) {

                                
                                $ouverture = new \DateTime($infoData['ouverture'][$j]);
                                $fermeture = new \DateTime($infoData['fermeture'][$j]);

                                if ( ( $fermeture != $h3) ) {
                                    $horaire = new HoraireStandard();
                                $horaire->setGarage($garage);
                                $horaire->setJour($jour);
                                $horaire->setOuverture($ouverture);
                                $horaire->setFermerture($fermeture);
                                $entityManager->persist($horaire);
                                }else {
                                    $horaire = new HoraireStandard();
                                $horaire->setGarage($garage);
                                $horaire->setJour($jour);
                                $horaire->setOuverture($ouverture);
                                $horaire->setFermerture($fermeture);
                                $entityManager->persist($horaire);
                                }

                                
                            }
                        }
                    }
                    $entityManager->flush();
                    }
            }
        }
    
        return new JsonResponse(["message" => "Horaires ajoutés avec succès"]);
    } catch (\Exception $e) {
        $errorMessage = "Une erreur est survenue : " . $e->getMessage();
        throw new BadRequestHttpException($errorMessage);
    }
    }


    //modifier horaire garage
    #[Route('/garage/horaire/update/', name: 'app_garage_horaire_update'  , methods:"POST")]
    public function update(EntityManagerInterface $entityManager, Request $request) : JsonResponse
    {
try {
        // Récupérer les données de la requête
        $infoData = $request->request->all();
$this->checkFormFields($infoData);
        $horaire =  $entityManager->getRepository(HoraireStandard::class)->findOneBy(["idHoraire" =>$infoData['id']]);
        if (!$horaire) {
            return new JsonResponse(["message" =>"horaire introuvable."]);
        }else{
            $ouv = new \DateTime($infoData['ouverture']);
            $ferm = new \DateTime($infoData['fermerture']);
           $horaire->setOuverture($ouv);
        $horaire->setFermerture($ferm);
        $entityManager->persist($horaire);
        $entityManager->flush(); 
        }
        
        return new JsonResponse(["message" => "Horaire modifié avec succès"]);
    } catch (\Exception $e) {
        $errorMessage = "Une erreur est survenue : " . $e->getMessage();
        throw new BadRequestHttpException($errorMessage);
    }

    }
    #[Route('/delete/horaire/{id}', name: 'app_horaire_delete' )]
    public function deleteHoraire(EntityManagerInterface $entityManager, $id)
    {
        $reservRepo = $entityManager->getRepository(HoraireStandard::class);

        $reservations = $reservRepo->findOneBy(["idHoraire" => $id]);  

        $entityManager->remove($reservations);
        $entityManager->flush(); 
        

        return new JsonResponse(["message" => "Horaire supprimer"]);
    }
}
