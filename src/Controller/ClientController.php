<?php

namespace App\Controller;

use App\Entity\Client;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class ClientController extends AbstractController
{
    private function checkFormFields(array $data): void {
        foreach ($data as $key => $value) {
            // Vérifie si le champ est vide ou nul
            if ($value === null || $value === '') {
                throw new NotFoundHttpException("Le champ '$key' ne peut pas être vide ou nul.");
            }
        }
    }
    #[Route('/client/all', name: 'app_all_client')]  //ok
    public function index(EntityManagerInterface $entityManager): JsonResponse
{
    $clientRepository = $entityManager->getRepository(Client::class);
    $clients = $clientRepository->findAll();

    if (!$clients) {
        return new JsonResponse(["message" => "Aucun client trouvé "], 404);
    }

    $clientData = [];
    foreach ($clients as $client) {
        $clientData[] = [
            "idClient" => $client->getId(),
            "emailClient" => $client->getEmailClient(),
            "telephoneClient" => $client->getTelephoneClient(),
            "nomClient" => $client->getNomClient(),
            "prenomClient" => $client->getPrenomClient(),
            "bloquer" => $client->isBloquer()
        ];
    }

    return new JsonResponse($clientData);
}
#[Route('/bloquer', name: 'app_all_client_bloquer')]  //ok
    public function clientBloquer(EntityManagerInterface $entityManager): JsonResponse
{
    $stat = true;
    $clientRepository = $entityManager->getRepository(Client::class);
    $clients = $clientRepository->findBy(["bloquer" => $stat]);

    if (!$clients) {
        return new JsonResponse(["message" => "Aucun client trouvé "], 404);
    }

    $clientData = [];
    foreach ($clients as $client) {
        $clientData[] = [
            "idClient" => $client->getId(),
            "emailClient" => $client->getEmailClient(),
            "telephoneClient" => $client->getTelephoneClient(),
            "nomClient" => $client->getNomClient(),
            "prenomClient" => $client->getPrenomClient(),
        ];
    }

    return new JsonResponse($clientData);
}
#[Route('/actif', name: 'app_all_client_actif')]  //ok
    public function clientActif(EntityManagerInterface $entityManager): JsonResponse
{
    $stat = false;
    $clientRepository = $entityManager->getRepository(Client::class);
    $clients = $clientRepository->findBy(["bloquer" => $stat]);

    if (!$clients) {
        return new JsonResponse(["message" => "Aucun client trouvé "], 404);
    }

    $clientData = [];
    foreach ($clients as $client) {
        $clientData[] = [
            "idClient" => $client->getId(),
            "emailClient" => $client->getEmailClient(),
            "telephoneClient" => $client->getTelephoneClient(),
            "nomClient" => $client->getNomClient(),
            "prenomClient" => $client->getPrenomClient(),
        ];
    }

    return new JsonResponse($clientData);
}
    #[Route('/api/client/{id}', name: 'app_client')]  //ok
    public function client(EntityManagerInterface $entityManager, int $id): JsonResponse
    {
    try {
        $clientRepository = $entityManager->getRepository(Client::class);
        $client = $clientRepository->findOneBy(["idClient" => $id]);

        if (!$client) {
            throw new NotFoundHttpException("Client introuvable.");
        }

        $clientData = [
            "idClient" => $client->getId(),
            "emailClient" => $client->getEmailClient(),
            "telephoneClient" => $client->getTelephoneClient(),
            "nomClient" => $client->getNomClient(),
            "prenomClient" => $client->getPrenomClient(),
            "bloquer" => $client->isBloquer()
        ];

        return new JsonResponse($clientData);
    } catch (\Exception $e) {
        $errorMessage = "Une erreur est survenue : " . $e->getMessage();
        throw new BadRequestHttpException($errorMessage);
    }
    }
   
    #[Route('/client/connexion', name: 'app_client_connexion' , methods:"POST")] //ok
    public function connexionClient(EntityManagerInterface $entityManager, Request $request): JsonResponse
    {
    try {
        $infoData = $request->request->all();
$this->checkFormFields($infoData);
        $email = $infoData['emailClient'];
        $mdp = $infoData['mdpClient'];
        $clientRepository = $entityManager->getRepository(Client::class);
        $client = $clientRepository->findOneBy(['emailClient' => $email]);
        
        if (!$client) {
            throw new NotFoundHttpException("Client introuvable.");
        }else {
            $mdpHash = $client->getMdpClient();
            if (password_verify($mdp, $mdpHash)) {
                $clientData = [
                    "idClient" => $client->getId(),
                ];
            } else {
                return new JsonResponse(["message" => "Mot de Passe incorrect" . $mdp ."!=". $mdpHash]);
            }
        }


        return new JsonResponse($clientData);
    } catch (\Exception $e) {
        $errorMessage = "Une erreur est survenue : " . $e->getMessage();
        throw new BadRequestHttpException($errorMessage);
    }
    }

    #[Route('/client/register', name: 'app_client_register' , methods:"POST")]  //ok
    public function registerClient(EntityManagerInterface $entityManager, Request $request): JsonResponse
    {
    try {
        $infoData = $request->request->all();
$this->checkFormFields($infoData);
        $mdp = $infoData['mdpClient'];
        $mdpHash = password_hash($mdp, PASSWORD_DEFAULT);
        $client = new Client();
        $client->setNomClient($infoData['nomClient']);
        $client->setPrenomClient($infoData['prenomClient']);
        $client->setEmailClient($infoData['emailClient']);
        $client->setTelephoneClient($infoData['telephoneClient']);
        $client->setMdpClient($mdpHash);
        $entityManager->persist($client);
        $entityManager->flush();

        return new JsonResponse(["message" => "Client enregistré !", "id" => $client->getIdClient(), ]);
    } catch (\Exception $e) {
        $errorMessage = "Une erreur est survenue : " . $e->getMessage();
        throw new BadRequestHttpException($errorMessage);
    }
    }

    #[Route('/client/update', name: 'app_client_update' , methods:"POST")]
    public function updateClient(EntityManagerInterface $entityManager, Request $request): JsonResponse
    {
    try {
        $infoData = $request->request->all();
$this->checkFormFields($infoData);
        $clientRepository = $entityManager->getRepository(Client::class);
        $client = $clientRepository->findOneBy(['idClient' => $infoData['idClient']]);
        if (!$client) {
            throw new NotFoundHttpException("Client introuvable.");
        } else {
            $client->setNomClient($infoData['nomClient']);
            $client->setPrenomClient($infoData['prenomClient']);
            $client->setEmailClient($infoData['emailClient']);
            $client->setTelephoneClient($infoData['telephoneClient']);
            $entityManager->persist($client);
            $entityManager->flush();

            return new JsonResponse(["message" => "Modification réussie !"]);
        }
    } catch (\Exception $e) {
        $errorMessage = "Une erreur est survenue : " . $e->getMessage();
        throw new BadRequestHttpException($errorMessage);
    }
    }
    #[Route('/client/modifMdp', name: 'app_client_modif_mdp' , methods:"POST")]
    public function modifMdp(EntityManagerInterface $entityManager, Request $request): JsonResponse
    {
    try {
        $infoData = $request->request->all();
$this->checkFormFields($infoData);
        $clientRepository = $entityManager->getRepository(Client::class);
        $client = $clientRepository->findOneBy(['idClient' => $infoData['idClient']]);
        $mdpinit = $client->getMdpClient();
        if (!$client) {
            throw new NotFoundHttpException("Client introuvable.");
        }
        
        if (password_verify($infoData['mdp'],$mdpinit)) {
            $client->setMdpClient(password_hash($infoData['NouveauMdp'], PASSWORD_DEFAULT));
            $entityManager->persist($client);
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
    #[Route('/client/bloquer/{id}', name: 'app_client_bloquer' )]
    public function bloquer(EntityManagerInterface $entityManager, $id): JsonResponse
    {
    try {
        $clientRepository = $entityManager->getRepository(Client::class);
        $client = $clientRepository->findOneBy(['idClient' => $id]);
        if (!$client) {
            throw new NotFoundHttpException("Client introuvable.");
        }else {
            $client->setBloquer(true);
            $entityManager->persist($client);
            $entityManager->flush();
            return new JsonResponse(["message" => "Client bloqué !"]);
        }
    } catch (\Exception $e) {
        $errorMessage = "Une erreur est survenue : " . $e->getMessage();
        throw new BadRequestHttpException($errorMessage);
    }
    }
    #[Route('/client/debloquer/{id}', name: 'app_client_debloquer' )]
    public function Debloquer(EntityManagerInterface $entityManager, int $id): JsonResponse
    {
    try {
        $clientRepository = $entityManager->getRepository(Client::class);
        $client = $clientRepository->findOneBy(['idClient' => $id]);
        if (!$client) {
            throw new NotFoundHttpException("Client introuvable.");
        }else {
            $client->setBloquer(false);
            $entityManager->persist($client);
            $entityManager->flush();
            return new JsonResponse(["message" => "Client debloqué !"]);
        }
    } catch (\Exception $e) {
        $errorMessage = "Une erreur est survenue : " . $e->getMessage();
        throw new BadRequestHttpException($errorMessage);
    }
    }
}
