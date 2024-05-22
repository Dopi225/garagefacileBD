<?php

namespace App\Controller;

use App\Entity\Users;
use App\Entity\Garage;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class UsersController extends AbstractController
{
    private function checkFormFields(array $data): void {
        foreach ($data as $key => $value) {
            // Vérifie si le champ est vide ou nul
            if ($value === null || $value === '') {
                throw new NotFoundHttpException("Le champ '$key' ne peut pas être vide ou nul.");
            }
        }
    }
    
    #[Route('/users/{id}', name: 'app_users')]
    public function index(EntityManagerInterface $entityManager, int $id): JsonResponse
    {
try {
        $usersRepository = $entityManager->getRepository(Users::class);
        $users = $usersRepository->findBy(["garage" => $id]);

        if (!$users) {
            throw new NotFoundHttpException("Utilisateurs introuvable.");
        }

        $usersData = [];
        foreach ($users as $user) {
            $usersData[] = [
                "idUser" => $user->getIdUsers(),
                "nom" => $user->getNom(),
                "prenom" => $user->getPrenom(),
                "login" => $user->getLogin(),
                "idGarage" => $user->getGarage()->getIdGarage(),
                "nomGarage" => $user->getGarage()->getNomEtablissement(),
            ];
        }

        return new JsonResponse($usersData);
    } catch (\Exception $e) {
        $errorMessage = "Une erreur est survenue : " . $e->getMessage();
        throw new BadRequestHttpException($errorMessage);
    }
    }

    #[Route('/api/users/{id}', name: 'app_users_spécifié')]
    public function users(EntityManagerInterface $entityManager, int $id): JsonResponse
    {
try {
        $usersRepository = $entityManager->getRepository(Users::class);
        $users = $usersRepository->findOneBy(["idUsers" => $id]);

        if (!$users) {
            throw new NotFoundHttpException("Utilisateurs introuvable.");
        }

        $usersData = [
            "nom" => $users->getNom(),
            "prenom" => $users->getPrenom(),
            "login" => $users->getLogin(),
            "idGarage" => $users->getGarage()->getIdGarage(),
            "nomGarage" => $users->getGarage()->getNomEtablissement(),
        ];

        return new JsonResponse($usersData);
    } catch (\Exception $e) {
        $errorMessage = "Une erreur est survenue : " . $e->getMessage();
        throw new BadRequestHttpException($errorMessage);
    }
    }
   
    #[Route('/connexion/users', name: 'app_users_connexion' , methods:"POST")] //ok
    public function connexionusers(EntityManagerInterface $entityManager, Request $request): JsonResponse
    {
    try {
        
        $infoData = $request->request->all();
$this->checkFormFields($infoData);
        $this->checkFormFields($infoData);
        $login = $infoData['login'];
        $mdp = $infoData['mdp'];
        $usersRepository = $entityManager->getRepository(Users::class);
        $users = $usersRepository->findOneBy(['login' => $login]);
        
        if (!$users) {
            return new JsonResponse(["message" => "Aucun users trouvé avec le login spécifié"]);
        }else {
            $mdpHash = $users->getMdp();
            if (password_verify($mdp, $mdpHash)) {
                $verifier = $users->getGarage()->isVerifier();
                if ($verifier == false) {
                    return new JsonResponse(["message" => "Garage non vérifié"]);
                } else {
                    $usersData = [
                    "idUser" => $users->getIdUsers(),
                    "idGarage" => $users->getGarage()->getIdGarage(),
                    "responsable" => $users->isResponsable(),
                    ];
                }
                
            } else {
                return new JsonResponse(["message" => "Mot de Passe incorrect"]);
            }
        }


        return new JsonResponse($usersData);
    } catch (\Exception $e) {
        $errorMessage = "Une erreur est survenue : " . $e->getMessage();
        throw new BadRequestHttpException($errorMessage);
    }
    }

    #[Route('/register/users', name: 'app_users_register' , methods:"POST")] //ok
    public function registerusers(EntityManagerInterface $entityManager, Request $request): JsonResponse
    {
try {
        $infoData = $request->request->all();
$this->checkFormFields($infoData);
        $mdp = $infoData['mdpusers'];
        $this->checkFormFields($infoData);
        $garageRepo = $entityManager->getRepository(Garage::class);
        $garage = $garageRepo->findOneBy(["idGarage" => $infoData["idGarage"]]);
        if (!$garage) {
            throw new NotFoundHttpException("Garage introuvable.");
        }
        $mdpHash = password_hash($mdp, PASSWORD_DEFAULT);
        $users = new users();
        $users->setNom($infoData['nomusers']);
        $users->setPrenom($infoData['prenomusers']);
        $users->setLogin($infoData['login']);
        $users->setMdp($mdpHash);
        $users->setGarage($garage);
        if (isset($infoData['responsable']) && !empty($infoData['responsable']) && $infoData['responsable'] === "oui") {
            $users->setResponsable(true);
        }
        $entityManager->persist($users);
        $entityManager->flush();

        return new JsonResponse(["message" => "users enregistré !", "id" => $users->getIdUsers(), "idGarage" => $users->getGarage()->getIdGarage(), "responsable" => $users->isResponsable()]);
    } catch (\Exception $e) {
        $errorMessage = "Une erreur est survenue : " . $e->getMessage();
        throw new BadRequestHttpException($errorMessage);
    }
    }

    #[Route('/update/users', name: 'app_users_update' , methods:"POST")]
    public function updateusers(EntityManagerInterface $entityManager, Request $request): JsonResponse
    {
try {
        $infoData = $request->request->all();
        $this->checkFormFields($infoData);
        $usersRepository = $entityManager->getRepository(users::class);
        $users = $usersRepository->findOneBy(['idUsers' => $infoData['idUsers']]);
        if (!$users) {
            return new JsonResponse(["message" => "Modification échouer !"]);
        } else {
            $users->setNom($infoData['nomusers']);
            $users->setPrenom($infoData['prenomusers']);
            $users->setLogin($infoData['login']);
            $entityManager->persist($users);
            $entityManager->flush();

            return new JsonResponse(["message" => "Modification réussie !"]);
        }
    } catch (\Exception $e) {
        $errorMessage = "Une erreur est survenue : " . $e->getMessage();
        throw new BadRequestHttpException($errorMessage);
    }
    }
    #[Route('/delete/users', name: 'app_users_delete' , methods:"POST")]
    public function deleteusers(EntityManagerInterface $entityManager, Request $request): JsonResponse
    {
try {
        $infoData = $request->request->all();
$this->checkFormFields($infoData);
        $usersRepository = $entityManager->getRepository(users::class);
        $users = $usersRepository->findOneBy(['idUsers' => $infoData['idUsers']]);
        if (!$users) {
            throw new NotFoundHttpException("Utilisateurs introuvable.");
        } else {
            $entityManager->remove($users);
            $entityManager->flush();

            return new JsonResponse(["message" => "Suppression réussie !"]);
        }
    } catch (\Exception $e) {
        $errorMessage = "Une erreur est survenue : " . $e->getMessage();
        throw new BadRequestHttpException($errorMessage);
    }
    }
    #[Route('/modifMdp/users', name: 'app_users_modif_mdp' , methods:"POST")]
    public function modifMdp(EntityManagerInterface $entityManager, Request $request): JsonResponse
    {
try {
        $infoData = $request->request->all();
$this->checkFormFields($infoData);
        $usersRepository = $entityManager->getRepository(users::class);
        $users = $usersRepository->findOneBy(['idUsers' => $infoData['idUsers']]);
        $mdpinit = $users->getMdp();
        
        if (!$users) {
            throw new NotFoundHttpException("Utilisateurs introuvable.");
        }

        if (password_verify($infoData['mdp'],$mdpinit)) {
            $users->setMdp(password_hash($infoData['NouveauMdp'], PASSWORD_DEFAULT));
            $entityManager->persist($users);
            $entityManager->flush();
            return new JsonResponse(["message" => "Modification réussie !"]);
        } else {
            return new JsonResponse(["message" => "Mot de passe actuel incorrect !"], 404);
        }
    } catch (\Exception $e) {
        $errorMessage = "Une erreur est survenue : " . $e->getMessage();
        throw new BadRequestHttpException($errorMessage);
    }

    }
}
