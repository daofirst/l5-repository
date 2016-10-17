<?php
namespace Prettus\Repository\Contracts;

/**
 * Interface CriteriaInterface
 * @package Prettus\Repository\Contracts
 */
interface CriteriaInterface
{
    /**
     * 应用条件至查询库中
     * Apply criteria in query repository
     *
     * @param                     $model
     * @param RepositoryInterface $repository
     *
     * @return mixed
     */
    public function apply($model, RepositoryInterface $repository);
}
