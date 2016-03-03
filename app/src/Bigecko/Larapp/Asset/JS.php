<?php namespace Bigecko\Larapp\Asset;

use Illuminate\Support\Facades\HTML;
use Illuminate\Support\Facades\Session;

class JS
{
    protected $appObj = array();

    protected $scripts = array();

    public function __construct()
    {
        $this->settings(array(
            'baseUrl' => url(),
            'publicUrl' => $this->getPublicUrl(),
            'csrfToken' => Session::token(),
        ));
    }

    public function getPublicUrl()
    {
        $url = asset('');
        $qPos = strpos($url, '?');
        return $qPos === false ? $url : substr($url, 0, $qPos);
    }

    public function settings(array $content)
    {
        $this->appObj = array_merge($this->appObj, $content);
    }

    public function renderObj($varname)
    {
        $s = "var $varname = " . json_encode($this->appObj) . ';';
        return $s;
    }

    /**
     * Add scripts.
     *
     * @param $path
     *   js文件路径，相对于public目录
     *
     * @param array $options
     */
    public function add($path, array $options = array())
    {
        if (isset($this->scripts[$path])) {
            return;
        }

        $this->scripts[$path] = true;
    }

    public function renderScripts()
    {
        $tags = array();
        foreach ($this->scripts as $path => $options) {
            $tags[] = HTML::script($path);
        }

        return implode("\n", $tags);
    }

}
