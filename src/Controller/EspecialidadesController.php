<?php
namespace App\Controller;

use App\Entity\Especialidade;
use App\Helper\EspecialidadeFactory;
use App\Helper\ExtratorDadosRequest;
use App\Repository\EspecialidadeRepository;
use Doctrine\ORM\EntityManagerInterface;

class EspecialidadesController extends BaseController
    {


    public function __construct(
        EntityManagerInterface $entityManager,
        EspecialidadeRepository $repository,
        EspecialidadeFactory $factory,
        ExtratorDadosRequest $extratorDadosRequest
    ) {
        parent::__construct($repository, $entityManager, $factory, $extratorDadosRequest);
    }
    /**
     * @param Especialidade $entidadeEnviada
     */
    public function atualizarEntidadeExistente($id, $entidadeEnviada)
    {
        $entidadeExistente = $this->repository->find($id);
        if (is_null($entidadeExistente)){
            throw new \invalidArgumentException();
        }
        $entidadeExistente->setDescricao($entidadeEnviada->getDescricao());
    }
}





