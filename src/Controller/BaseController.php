<?php


namespace App\Controller;

use App\Helper\EntidadeFactory;
use App\Helper\ExtratorDadosRequest;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
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
    /**
     * @var EntidadeFactory
     */
    protected $factory;
    /**
     * @var ExtratorDadosRequest
     */
    private $extratorDadosRequest;

    public function __construct(
        ObjectRepository $repository,
        EntityManagerInterface $entityManager,
        EntidadeFactory $factory,
        ExtratorDadosRequest $extratorDadosRequest
    )
    {
        $this->repository = $repository;
        $this->entityManager = $entityManager;
        $this->factory = $factory;
        $this->extratorDadosRequest = $extratorDadosRequest;
    }
    public function novo(Request $request): Response
    {
        $corpoRequisicao = $request->getContent();
        $entidade = $this->factory->criarEntidade($corpoRequisicao);
        $this->entityManager->persist($entidade);
        $this->entityManager->flush();
        return new JsonResponse($entidade);
    }

    public function buscarTodos(Request $request): Response
    {
        $filtro = $this->extratorDadosRequest->buscaDadosFiltro($request);
        $informacoesDeOrdenacao = $this->extratorDadosRequest->buscaDadosOrdenacao($request);
        $lista = $this->repository->findBy($filtro, $informacoesDeOrdenacao);
        return new JsonResponse($lista);
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
    public function atualiza(int $id, Request $request): Response
    {
        $corpoRequisicao = $request->getContent();
        $entidadeEnviada = $this->factory->criarEntidade($corpoRequisicao);
        $entidadeExistente = $this->repository->find($id);
        if (is_null($entidadeExistente)) {
            return new Response('', Response::HTTP_NOT_FOUND);
        }
        $this->atualizarEntidadeExistente($entidadeExistente, $entidadeEnviada);
        $this->entityManager->flush();

        return new JsonResponse($entidadeExistente);
    }
    abstract public function atualizarEntidadeExistente($entidadeExistente, $entidadeEnviada);

}