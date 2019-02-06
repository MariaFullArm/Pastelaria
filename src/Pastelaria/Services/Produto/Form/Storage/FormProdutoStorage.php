<?php

namespace Pastelaria\Services\Produto\Form\Storage;

use Doctrine\ORM\EntityManager;
use Doctrine\DBAL\Types\Type;
use Pastelaria\Entity\Produto;

class FormProdutoStorage
{

    private $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function save(Produto $produto = null)
    {
        if($produto){
            $this->em->persist($produto);
            $this->em->flush();
        }
        return $produto;
    }

    public function remove(Produto $produto = null)
    {
        if($produto){
            $this->em->remove($produto);
            $this->em->flush();
        }
        return $produto;
    }

    /*public function getOperadoraPorMonitoramento($idMonitoramento)
    {

        $qb = $this->em->createQueryBuilder();

        $qb->select('r.id,r.descricao as text')
            ->from(Operadora::class, 'r')
            ->where(
                $qb->expr()->eq('r.monitoramento', ':monitoramento')
            )
            ->setParameter('monitoramento', $idMonitoramento, Type::INTEGER)
            ->distinct();

        $q = $qb->getQuery();

        try {
            $result = $q->getArrayResult();
        } catch (\Exception $e) {
            $result = [];
        }

        return $result;
    }

    public function getOperadoraPorIdEMonitoramento($data)
    {
        $id = isset($data['id']) ? $data['id'] : 0;
        $monitoramento = isset($data['monitoramento']) ? $data['monitoramento'] : 0;

        $operadoraRepository = $this->em->getRepository(Operadora::class);
        $operadora          = $operadoraRepository->findOneBy(['id'=> $id,'monitoramento'=>$monitoramento]);

        return is_null($operadora)? [] : $operadora->toArray();
    }*/

}