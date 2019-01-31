<?php

namespace Pastelaria\Services\ItensVendidos\Form\Storage;

use Doctrine\ORM\EntityManager;
use Doctrine\DBAL\Types\Type;
use Pastelaria\Entity\ItensVendidos;

class FormItensVendidosStorage
{
    private $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function save(ItensVendidos $itens_vendidos = null)
    {
        if($itens_vendidos){
            $this->em->persist($itens_vendidos);
            $this->em->flush();
        }
        return $itens_vendidos;
    }

    public function remove(ItensVendidos $itens_vendidos = null)
    {
        if($itens_vendidos){
            $this->em->remove($itens_vendidos);
            $this->em->flush();
        }
        return $itens_vendidos;
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