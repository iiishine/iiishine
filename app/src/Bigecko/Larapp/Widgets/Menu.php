<?php namespace Bigecko\Larapp\Widgets;

use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\HTML;

/**
 * Simple menu builder.
 *
 * TODO:
 *      * Framework agnostic.
 *      * Custom active menu items.
 *      * Custom html tag.
 */
class Menu
{
    protected $data;

    protected $faltItems;

    /**
     * current path
     */
    protected $path;

    /**
     *
     * @param $data The menu structure data.
     *
     *  array(
     *      'attr' => array('class' => 'nav'),  // html tag attributes
     *
     *      // root menu items
     *      $items => array(
     *          array(
     *              'url' => 'photo/list',
     *              'title' => 'Photos',
     *
     *              // submenu
     *              'submenu' => array(
     *                  'show' => 'match_parent',  // default is match_parent. Allow: 'always', 'hidden'
     *                  'items' => array(
     *                      ......
     *                  ),
     *              ),
     *          ),
     *
     *          array(...),
     *      ),
     *  );
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * flat menu items to an one dimension array.
     */
    protected function flatItems()
    {
        return $this->_flatItems($this->data['items']);
    }

    protected function _flatItems($items, array &$faltItems = array(), $parentKey = null)
    {
        foreach ($items as $key => $options) {
            $url = $options['url'];

            // Generate an item key for every menu item,
            // if has parent menu, use '#' as split symbol.
            $itemKey = is_null($parentKey) ? $key : $parentKey . '#' . $key;

            if (!isset($faltItems[$url])) {
                $faltItems[$url] = array((string)$itemKey);
            }
            else {
                $faltItems[$url][] = $itemKey;
            }

            if (isset($options['submenu'])) {
                $this->_flatItems($options['submenu']['items'], $faltItems, $itemKey);
            }
        }

        return $faltItems;
    }

    /**
     * render menu as html code
     */
    public function render()
    {
        $this->path = Input::path();
        $this->flatItems = $this->flatItems();
        return $this->_render($this->data);
    }

    protected function _render($data, $parentUrl = null)
    {
        $attr = isset($data['attr']) ? $data['attr'] : array();
        $tags = array('<ul' . HTML::attributes($attr) .'>');

        $items = $data['items'];
        foreach ($items as $key => $options) {
            $attr = array();
            $activeClass = $this->activeClass($options, $parentUrl);
            if (!empty($activeClass)) {
                $attr['class'] = $activeClass;
            }

            $s = '<li' . HTML::attributes($attr) . '>' . $this->renderLink($options);

            // submenu
            if (isset($options['submenu'])) {
                $submenu = array_merge(array(
                    'show' => 'match_parent',
                ), $options['submenu']);

                if ($this->isShowMenu($submenu, $options['url'])) {
                    $s .= $this->_render($submenu, $options['url']);
                }
            }

            $s .= '</li>';

            $tags[] = $s;
        }
        $tags[] = '</ul>';

        return implode('', $tags);
    }

    /**
     * check if show submenu
     */
    protected function isShowMenu($menu, $parentUrl)
    {
        if ($menu['show'] == 'always') {
            return true;
        }

        if (isset($menu['match_path'])) {
            foreach ($menu['match_path'] as $path) {
                if (Input::is($path)) {
                    return true;
                }
            }
        }

        if ($menu['show'] == 'match_parent') {
            if (Input::is($parentUrl)) {
                return true;
            }

            if (!isset($this->flatItems[$this->path])) {
                return false;
            }

            $parentKeys = $this->flatItems[$parentUrl];
            foreach ($parentKeys as $parentKey) {
                $pattern = '/^' . preg_quote($parentKey) . '[#|\s]/';
                foreach ($this->flatItems[$this->path] as $key) {
                    if (preg_match($pattern, $key)) {
                        return true;
                    }
                }
            }
        }

        return false;
    }

    /**
     * render a single link
     */
    public function renderLink($options)
    {
        $attributes = isset($options['attr']) ? $options['attr'] : array();

        return HTML::link($options['url'], $options['title'], $attributes);
    }

    /**
     * get active class for a single link
     */
    public function activeClass($options, $parentUrl)
    {
        $url = $options['url'];
        if (Input::is($url, $url . '/*')) {
            return 'active';
        }

        // Check menu item is current path's parent item, use structure check.
        else if (isset($this->flatItems[$this->path]) && $parentUrl != $url) {
            $currentKey = reset($this->flatItems[$this->path]);
            $keys = $this->flatItems[$url];
            //var_dump($currentKey, $keys);
            foreach ($keys as $key) {
                $pattern = '/^' . preg_quote($key) . '[#|\s]/';
                if (preg_match($pattern, $currentKey)) {
                    return 'active';
                }
            }
        }
        return '';
    }
}
