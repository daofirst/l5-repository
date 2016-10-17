<?php
namespace Prettus\Repository\Contracts;

/**
 * Interface Transformable
 * @package Prettus\Repository\Contracts
 */
interface Transformable
{
    /**
     * 转换
     * @return array
     */
    public function transform();
}
