<?php


namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

abstract class BaseController extends AbstractController
{
    /**
     * @var ObjectRepository
     */
    protected $repository;
    /**
     * @var EntityManagerInterface
     */
    protected $entityManager;

    public function __construct(
        ObjectRepository $repository,
        EntityManagerInterface $entityManager
    )
    {
        $this->repository = $repository;
        $this->entityManager = $entityManager;
    }

    public function buscarTodos(): Response
    {
        $entityList= $this->repository->findAll();
        return new JsonResponse($entityList);
    }
    public function buscarUm(int $id): Response
    {
        return new JsonResponse($this->repository->find($id));
    }
    public function remove(int $id): Response
    {
        $entidade = $this->repository->find($id);
        $this->entityManager->remove($entidade);
        $this->entityManager->flush();
        return new Response('', Response::HTTP_NO_CONTENT);
    }

}