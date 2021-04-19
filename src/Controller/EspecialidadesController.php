<?php
namespace App\Controller;

use App\Entity\Especialidade;
use App\Repository\EspecialidadeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EspecialidadesController extends BaseController
    {


    public function __construct(
        EntityManagerInterface $entityManager,
        EspecialidadeRepository $repository
    ) {
        parent::__construct($repository, $entityManager);

    }

    /**
    * @Route("/especialidades", methods={"POST"})
    */
    public function nova(Request $request): Response
    {
    $dadosRequest = $request->getContent();
    $dadosEmJson = json_decode($dadosRequest);

    $especialidade = new Especialidade();
    $especialidade->setDescricao($dadosEmJson->descricao);

    $this->entityManager->persist($especialidade);
    $this->entityManager->flush();

    return new JsonResponse($especialidade);
    }
    /**
    * @Route("/especialidades/{id}", methods={"PUT"})
    */
    public function atualiza(int $id, Request $request): Response
    {
    $dadosRequest = $request->getContent();
    $dadosEmJson = json_decode($dadosRequest);

    $especialidade = $this->repository->find($id);
    $especialidade
    ->setDescricao($dadosEmJson->descricao);

    $this->entityManager->flush();

    return new JsonResponse($especialidade);
    }
}