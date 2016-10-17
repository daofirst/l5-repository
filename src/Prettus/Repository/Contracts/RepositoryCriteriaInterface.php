<?php
namespace Prettus\Repository\Contracts;

use Illuminate\Support\Collection;


/**
 * Interface RepositoryCriteriaInterface
 * @package Prettus\Repository\Contracts
 */
interface RepositoryCriteriaInterface
{

    /**
     * 添加条件筛选到查询
     * Push Criteria for filter the query
     *
     * @param $criteria
     *
     * @return $this
     */
    public function pushCriteria($criteria);

    /**
     * 从查询中弹出最后一个筛选条件
     * Pop Criteria
     *
     * @param $criteria
     *
     * @return $this
     */
    public function popCriteria($criteria);

    /**
     * 获取条件的集合
     * Get Collection of Criteria
     *
     * @return Collection
     */
    public function getCriteria();

    /**
     * 通过条件查询
     * Find data by Criteria
     *
     * @param CriteriaInterface $criteria
     *
     * @return mixed
     */
    public function getByCriteria(CriteriaInterface $criteria);

    /**
     * 忽略条件
     * Skip Criteria
     *
     * @param bool $status
     *
     * @return $this
     */
    public function skipCriteria($status = true);

    /**
     * 重置筛选条件
     * Reset all Criterias
     *
     * @return $this
     */
    public function resetCriteria();
}
