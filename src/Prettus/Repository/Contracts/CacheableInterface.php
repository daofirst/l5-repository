<?php
namespace Prettus\Repository\Contracts;

use Illuminate\Contracts\Cache\Repository as CacheRepository;

/**
 * Interface CacheableInterface
 * @package Prettus\Repository\Contracts
 */
interface CacheableInterface
{
    /**
     * 设置缓存库
     * Set Cache Repository
     *
     * @param CacheRepository $repository
     *
     * @return $this
     */
    public function setCacheRepository(CacheRepository $repository);

    /**
     * 返回一个缓存库实例
     * Return instance of Cache Repository
     *
     * @return CacheRepository
     */
    public function getCacheRepository();

    /**
     * 获取该方法的缓存键
     * Get Cache key for the method
     *
     * @param $method
     * @param $args
     *
     * @return string
     */
    public function getCacheKey($method, $args = null);

    /**
     * 获取缓存时间（分钟）
     * Get cache minutes
     *
     * @return int
     */
    public function getCacheMinutes();


    /**
     * 忽略缓存
     * Skip Cache
     *
     * @param bool $status
     *
     * @return $this
     */
    public function skipCache($status = true);
}
