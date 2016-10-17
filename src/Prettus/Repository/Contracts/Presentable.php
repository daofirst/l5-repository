<?php
namespace Prettus\Repository\Contracts;

/**
 * Interface Presentable
 * @package Prettus\Repository\Contracts
 */
interface Presentable
{
    /**
     * 设置呈现器
     * @param PresenterInterface $presenter
     *
     * @return mixed
     */
    public function setPresenter(PresenterInterface $presenter);

    /**
     * 呈现器
     * @return mixed
     */
    public function presenter();
}
