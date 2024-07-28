<?php

namespace App\DependencyInjection\Traits;

use Doctrine\ORM\EntityManagerInterface;

trait EntityManagerInterfaceInjectionTrait
{
    /**
     * @var EntityManagerInterface
     */
    protected $em;

    /**
     * @required
     *
     * @param EntityManagerInterface $em
     */
    public function setEntityManager(EntityManagerInterface $em): void
    {
        $this->em = $em;
    }

    /**
     * @return EntityManagerInterface
     */
    public function getEntityManager(): EntityManagerInterface
    {
        return $this->em;
    }
}