<?php
namespace Prettus\Repository\Contracts;

/**
 * Interface PresenterInterface
 * @package Prettus\Repository\Contracts
 */
interface PresenterInterface
{
    /**
     * 准备数据呈现
     * Prepare data to present
     *
     * @param $data
     *
     * @return mixed
     */
    public function present($data);
}
