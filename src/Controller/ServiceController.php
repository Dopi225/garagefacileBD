<?php

namespace App\Controller;

use App\Entity\Service;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class ServiceController extends AbstractController
{
    #[Route('/service', name: 'app_service')]
    public function index(EntityManagerInterface $entityManager): JsonResponse
    {
    try {
        $serviceRepository = $entityManager->getRepository(Service::class);
        $service = $serviceRepository->findAll();
        $options=[];
        if (!$service) {
            throw new NotFoundHttpException("Dispo introuvable.");
        }
        foreach ($service as $services) {
            $serviceData = [
                "idService"=> $services->getIdService(),
                "libelleService"=> $services->getLibelleService(),
                "descriptionService"=>$services->getDescriptionService(),
            ];
            $options[] = $serviceData;
        }
        return new JsonResponse($options);
    } catch (\Exception $e) {
        $errorMessage = "Une erreur est survenue : " . $e->getMessage();
        throw new BadRequestHttpException($errorMessage);
    }
    }
    #[Route('/service/{id}', name: 'app_service_precis')]
    public function getServiceById(EntityManagerInterface $entityManager,int $id): JsonResponse
    {
    try {
        $serviceRepository = $entityManager->getRepository(Service::class);
        $service = $serviceRepository->findOneBy(['idService'=>$id]);
        if (!$service) {
            throw new NotFoundHttpException("Dispo introuvable.");
        }
        $serviceData=[];
        if ($service) {
            $serviceData = [
                "idService"=> $service->getIdService(),
                "libelleService"=> $service->getLibelleService(),
                "descriptionService"=>$service->getDescriptionService(),
            ];
        }
        return new JsonResponse($serviceData);
    } catch (\Exception $e) {
        $errorMessage = "Une erreur est survenue : " . $e->getMessage();
        throw new BadRequestHttpException($errorMessage);
    }
    }
    #[Route('/recherche/service/{id}', name: 'app_service_recherche')]
    public function getRecherche(EntityManagerInterface $entityManager,string $id): JsonResponse
    {
    try {
        $serviceRepository = $entityManager->getRepository(Service::class);
        $services = $serviceRepository->findService($id);
        if (!$services) {
            throw new NotFoundHttpException("Dispo introuvable.");
        }
        $serviceData = [];
        foreach ($services as $service) {
            $serviceData[] = [
                "id" => $service->getIdService(),
                "service" => $service->getLibelleService(),
            ];
        }
        return new JsonResponse($serviceData);
    } catch (\Exception $e) {
        $errorMessage = "Une erreur est survenue : " . $e->getMessage();
        throw new BadRequestHttpException($errorMessage);
    }
    }
    
}
