<?php namespace Bigecko\Larapp\Routing;

use Illuminate\Cache\CacheManager;

class UrlGenerator extends \Illuminate\Routing\UrlGenerator {

    private $assetUrlPrefix = null;

    protected $timestamp = null;

    /**
     * @var CacheManager
     */
    protected $cacheManager;

    public function setAssetUrlPrefix($value)
    {
        $this->assetUrlPrefix = trim($value, '/');
    }

    public function getAssetUrlPrefix()
    {
        return $this->assetUrlPrefix;
    }

    /**
     * Overriding default asset method, add asset url prefix.
     */
    public function asset($path, $secure = null)
    {
        // 使用url前缀，如cdn等
        if (!$this->isValidUrl($path)) {
            $path = $this->getAssetUrlPrefix() . '/' . $path;
        }

        $url = parent::asset($path, $secure);

        // 生成时间戳cache放到静态文件url参数后面，方便更新静态文件缓存
        //                                   时间戳只加在有具体后缀名的路径后面
        if (!is_null($this->cacheManager) && preg_match("/\.\w+$/", $path)) {

            // 生成时间戳
            if (is_null($this->timestamp)) {
                $this->timestamp = $this->cacheManager
                    ->rememberForever('larapp.assetts', function() {
                        return time();
                    });
            }

            if (strpos($url, '?') === false) {
                $url .= '?lpts=' . $this->timestamp;
            }
            else {
                $url .= '&lpts=' . $this->timestamp;
            }
        }

        return $url;
    }

    public function setCacheManager(CacheManager $cacheManager)
    {
        $this->cacheManager = $cacheManager;
    }

}

