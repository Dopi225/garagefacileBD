<?php

namespace App\Controller;

use App\Entity\Garage;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class GarageController extends AbstractController
{
    private function checkFormFields(array $data): void {
        foreach ($data as $key => $value) {
            // Vérifie si le champ est vide ou nul
            if ($value === null || $value === '') {
                throw new NotFoundHttpException("Le champ '$key' ne peut pas être vide ou nul.");
            }
        }
    }
    #[Route('/garage', name: 'app_garage')] //ok
    public function index(EntityManagerInterface $entityManager): JsonResponse
    {
        try {
            $garageRepository = $entityManager->getRepository(Garage::class);
            $options = [];
            // Utilisation de la méthode findall
            $garages = $garageRepository->findAll();
            if (!$garages) {
                throw new NotFoundHttpException("Garages introuvable.");
            }
            
                foreach ($garages as $garage) {
                // Construire un tableau avec les données du garage
                $garageData[] = [
                    "idGarage" => $garage->getIdGarage(),
                    "NomEtablissement" => $garage->getNomEtablissement(),
                    "NomResponsale" => $garage->getNomResponsable(),
                    "PrenomResponsale" => $garage->getPrenomResponsable(),
                    "EmailEtablissement" => $garage->getEmailEtablissement(),
                    "EmailResponsable" => $garage->getEmailResponsable(),
                    "Adresse" => $garage->getAdresse(),
                    "cp" => $garage->getCP(),
                    "Ville" => $garage->getVille(),
                    "DescriptionEtablissement" => $garage->getDescriptionEtablissement(),
                    "TelephoneEtablissement" => $garage->getTelephoneEtablissement(),
                    "verifier" => $garage->isVerifier()
                ];
                $options[] = $garageData;
            
        
                
            } 
            // Retourner les données du garage au format JSON
            return new JsonResponse($options);
        } catch (\Exception $e) {
            $errorMessage = "Une erreur est survenue : " . $e->getMessage();
            throw new BadRequestHttpException($errorMessage);
        }
    }
    #[Route('/verifier', name: 'app_garage_verifier')] //ok
    public function garageVerifier(EntityManagerInterface $entityManager): JsonResponse
    {
        try {
            $stat = true;
            $garageRepository = $entityManager->getRepository(Garage::class);
            $options = [];
            // Utilisation de la méthode findall
            $garages = $garageRepository->findBy(["verifier" => $stat]);
            if (!$garages) {
                throw new NotFoundHttpException("Garages introuvable.");
            }
            
                foreach ($garages as $garage) {
                // Construire un tableau avec les données du garage
                $garageData = [
                    "idGarage" => $garage->getIdGarage(),
                    "NomEtablissement" => $garage->getNomEtablissement(),
                    "NomResponsable" => $garage->getNomResponsable(),
                    "PrenomResponsable" => $garage->getPrenomResponsable(),
                    "EmailEtablissement" => $garage->getEmailEtablissement(),
                    "EmailResponsable" => $garage->getEmailResponsable(),
                    "Adresse" => $garage->getAdresse(),
                    "cp" => $garage->getCP(),
                    "Ville" => $garage->getVille(),
                    "Siret" => $garage->getSiret(),
                    "DescriptionEtablissement" => $garage->getDescriptionEtablissement(),
                    "TelephoneEtablissement" => $garage->getTelephoneEtablissement(),
                ];
                $options[] = $garageData;
            
        
                
            } 
            // Retourner les données du garage au format JSON
            return new JsonResponse($options);
        } catch (\Exception $e) {
            $errorMessage = "Une erreur est survenue : " . $e->getMessage();
            throw new BadRequestHttpException($errorMessage);
        }
    }
    #[Route('/nonVerifier', name: 'app_garage_nonVerifier')] //ok
    public function nonVerifer(EntityManagerInterface $entityManager): JsonResponse
    {
        try {
            $stat = false;
            $garageRepository = $entityManager->getRepository(Garage::class);
            $options = [];
            // Utilisation de la méthode findall
            $garages = $garageRepository->findBy(["verifier" => $stat]);
            if (!$garages) {
                throw new NotFoundHttpException("Garages introuvable.");
            }
            
                foreach ($garages as $garage) {
                // Construire un tableau avec les données du garage
                $garageData = [
                    "idGarage" => $garage->getIdGarage(),
                    "NomEtablissement" => $garage->getNomEtablissement(),
                    "NomResponsale" => $garage->getNomResponsable(),
                    "PrenomResponsale" => $garage->getPrenomResponsable(),
                    "EmailEtablissement" => $garage->getEmailEtablissement(),
                    "EmailResponsable" => $garage->getEmailResponsable(),
                    "Adresse" => $garage->getAdresse(),
                    "Siret" => $garage->getSiret(),
                    "cp" => $garage->getCP(),
                    "Ville" => $garage->getVille(),
                    "DescriptionEtablissement" => $garage->getDescriptionEtablissement(),
                    "TelephoneEtablissement" => $garage->getTelephoneEtablissement(),
                ];
                $options[] = $garageData;
            
        
                
            } 
            // Retourner les données du garage au format JSON
            return new JsonResponse($options);
        } catch (\Exception $e) {
            $errorMessage = "Une erreur est survenue : " . $e->getMessage();
            throw new BadRequestHttpException($errorMessage);
        }
    }
    #[Route('/garage/{id}', name: 'app_garage_spécifié')] //ok
        public function Garage(EntityManagerInterface $entityManager,int $id) {
            $garageRepository = $entityManager->getRepository(Garage::class);
            
            // Utilisation de la méthode findOneBy() pour récupérer un garage par son ID
            $garage = $garageRepository->findOneBy(['idGarage' => $id]);
            if (!$garage) {
                throw new NotFoundHttpException("Garage introuvable.");
            }
            // Vérifier si un garage a été trouvé
            if ($garage) {
                // Construire un tableau avec les données du garage
                $garageData = [
                    "idGarage" => $garage->getIdGarage(),
                    "NomEtablissement" => $garage->getNomEtablissement(),
                    "NomResponsable" => $garage->getNomResponsable(),
                    "PrenomResponsable" => $garage->getPrenomResponsable(),
                    "EmailEtablissement" => $garage->getEmailEtablissement(),
                    "EmailResponsable" => $garage->getEmailResponsable(),
                    "Adresse" => $garage->getAdresse(),
                    "cp" => $garage->getCP(),
                    "Ville" => $garage->getVille(),
                    "DescriptionEtablissement" => $garage->getDescriptionEtablissement(),
                    "Telephone" => $garage->getTelephoneEtablissement(),
                    "verifier" => $garage->isVerifier()
                ];
        
                // Retourner les données du garage au format JSON
                return new JsonResponse($garageData);
            } 
        }

    //inscription garage

    #[Route('/registerGarage', name: 'app_garage_register', methods: ['POST'])]  //ok
    public function inscription(EntityManagerInterface $entityManager, Request $request): JsonResponse
    {
    try {
        $infoData = $request->request->all();
$this->checkFormFields($infoData);
        $verifRepo = $entityManager->getRepository(Garage::class);
        $query = $verifRepo->createQueryBuilder('g')
            ->where('g.emailEtablissement = :emailEtablissement')
            ->orWhere('g.emailResponsable = :emailResponsable')
            ->orWhere('g.siret = :siret')
            ->setParameter('emailEtablissement', $infoData['emailEtablissement'])
            ->setParameter('emailResponsable', $infoData['emailResponsable'])
            ->setParameter('siret', $infoData['siret'])
            ->getQuery();

        $verif = $query->getResult();

        if ($verif) {
            return new JsonResponse(["message" => "Un des Email ou le siret est déja enregistré"]);
        }else{
            $garage = new Garage();
            $garage->setNomEtablissement($infoData['NomEtablissement']);
            $garage->setNomResponsable($infoData['nomResponsable']);
            $garage->setPrenomResponsable($infoData['prenomResponsable']);
            $garage->setEmailEtablissement($infoData['emailEtablissement']);
            $garage->setEmailResponsable($infoData['emailResponsable']);
            $garage->setAdresse($infoData['adresse']);
            $garage->setCP($infoData['cp']);
            $garage->setSiret($infoData['siret']);
            $garage->setVille($infoData['ville']);
            $garage->setDescriptionEtablissement($infoData['descriptionEtablissement']);
            $garage->setTelephoneEtablissement($infoData['telephoneEtablissement']);

            $entityManager->persist($garage);
            $entityManager->flush();

        }

        return new JsonResponse(["message" => "Garage ajouté avec succès", "id" => $garage->getIdGarage()]);
    } catch (\Exception $e) {
        $errorMessage = "Une erreur est survenue : " . $e->getMessage();
        return new JsonResponse($errorMessage);
    }
    }
    #[Route('/updateGarage', name: 'app_garage_update', methods: ['POST'])]  //ok
    public function update(EntityManagerInterface $entityManager, Request $request): JsonResponse
    {
    try {
        $infoData = $request->request->all();
$this->checkFormFields($infoData);
        $garageRepo = $entityManager->getRepository(Garage::class);
        $garage = $garageRepo->findOneBy(["idGarage" => $infoData['idGarage']]);
        if (!$garage) {
            throw new NotFoundHttpException("Garage introuvable.");
        }
        $garage->setNomEtablissement($infoData['NomEtablissement']);
        $garage->setNomResponsable($infoData['nomResponsable']);
        $garage->setPrenomResponsable($infoData['prenomResponsable']);
        $garage->setEmailEtablissement($infoData['emailEtablissement']);
        $garage->setEmailResponsable($infoData['emailResponsable']);
        $garage->setAdresse($infoData['adresse']);
        $garage->setCP($infoData['cp']);
        $garage->setVille($infoData['ville']);
        $garage->setDescriptionEtablissement($infoData['descriptionEtablissement']);
        $garage->setTelephoneEtablissement($infoData['telephoneEtablissement']);

        $entityManager->persist($garage);
        $entityManager->flush();

        return new JsonResponse(["message" => "Garage modifié avec succès", "id" => $garage->getIdGarage()]);
    } catch (\Exception $e) {
        $errorMessage = "Une erreur est survenue : " . $e->getMessage();
        throw new BadRequestHttpException($errorMessage);
    }
    }

    #[Route('/confirmation/{id}', name: 'app_verification')]  //ok
    public function verifier(EntityManagerInterface $entityManager, $id): JsonResponse
    {
    try {
        $garageRepo = $entityManager->getRepository(Garage::class);
        $verifier = true;
        $garage = $garageRepo->findOneBy(["idGarage" => $id]);
        if (!$garage) {
            // throw new NotFoundHttpException("Garage introuvable.");
            return new JsonResponse(["message" => "Garage introuvable !"]);
        }else {
            $garage->setVerifier($verifier);

            $entityManager->persist($garage);
            $entityManager->flush();
            return new JsonResponse(["message" => "Garage vérifié !"]);
        }
    } catch (\Exception $e) {
        $errorMessage = "Une erreur est survenue : " . $e->getMessage();
        throw new BadRequestHttpException($errorMessage);
    }

    }
    #[Route('/deverifierGarage/{id}', name: 'app_garage_deverification')]  //ok
    public function deverifier(EntityManagerInterface $entityManager, int $id): JsonResponse
    {
    try {
        $garageRepo = $entityManager->getRepository(Garage::class);
        $verifier = false;
        $garage = $garageRepo->findOneBy(["idGarage" => $id]);
        if (!$garage) {
            throw new NotFoundHttpException("Garage introuvable.");
        }else {
            $garage->setVerifier($verifier);

            $entityManager->persist($garage);
            $entityManager->flush();
            return new JsonResponse(["message" => "Garage devérifié !"]);
        }
    } catch (\Exception $e) {
        $errorMessage = "Une erreur est survenue : " . $e->getMessage();
        throw new BadRequestHttpException($errorMessage);
    }

    }
}
