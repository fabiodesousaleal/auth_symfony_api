<?php
namespace App\Controller;

use App\Entity\Medico;
use App\Helper\MedicoFactory;
use App\Repository\MedicosRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class MedicosController extends BaseController
{
    public function __construct(
        EntityManagerInterface $entityManager,
        MedicoFactory $medicoFactory,
        MedicosRepository $medicosRepository
    )
    {
        parent::__construct($medicosRepository, $entityManager, $medicoFactory);

    }
    /**
     * @Route("/medicos/{id}", methods={"PUT"})
     */
    public function atualizar(int $id, Request $request): Response
    {
        $corpoRequisicao = $request->getContent();
        $medicoEnviado = $this->factory->criarEntidade($corpoRequisicao);
        $medico = $this->buscaMedico($id);
        if (is_null($medico)) {
            return new Response('', Response::HTTP_NOT_FOUND);
        }
        $medico
        ->setCrm($medicoEnviado->getCrm())
        ->setNome($medicoEnviado->getNome());

        $this->entityManager->flush();
        return new JsonResponse($medico);
    }
    /**
     * @param $id
     * @return object|null
     */
    public function buscaMedico($id): ?object
    {
        $medico = $this->repository->find($id);
        return $medico;
    }

    /**
     * @Route("/especialidades/{id}/medicos")
     */
    public function especialidadeMedico(int $id): Response
    {
        $medico = $this->repository->findBy(
            [
                "especialidade"=> $id
            ]
        );
        return new JsonResponse($medico);
    }
}