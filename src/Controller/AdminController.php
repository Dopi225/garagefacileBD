<?php

namespace App\Controller;

use App\Entity\Admin;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class AdminController extends AbstractController
{
    private function checkFormFields(array $data): void {
        foreach ($data as $key => $value) {
            // Vérifie si le champ est vide ou nul
            if ($value === null || $value === '') {
                throw new NotFoundHttpException("Le champ '$key' ne peut pas être vide ou nul.");
            }
        }
    }
    #[Route('/admin', name: 'app_admin')]
    public function index(): JsonResponse
    {
    try {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/AdminController.php',
        ]);
    } catch (\Exception $e) {
        $errorMessage = "Une erreur est survenue : " . $e->getMessage();
        throw new BadRequestHttpException($errorMessage);
    }
    }
    #[Route('/connexion/admin', name: 'app_admin_connexion' , methods:"POST")] //ok
    public function connexionusers(EntityManagerInterface $entityManager, Request $request): JsonResponse
    {
    try {
        $infoData = $request->request->all();
$this->checkFormFields($infoData);
        $login = $infoData['login'];
        $mdp = $infoData['mdp'];
        $usersRepository = $entityManager->getRepository(Admin::class);
        $users = $usersRepository->findOneBy(['login' => $login]);
        
        if (!$users) {
            throw new NotFoundHttpException("Admin introuvable.");
        }else {
            $mdpHash = $users->getMdpAdmin();
            if (password_verify($mdp, $mdpHash)) {
                
                    $usersData = [
                    "idAdmin" => $users->getId(),
                    ];
                
                
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
}
