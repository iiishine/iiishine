<?php

namespace Bigecko\Larapp\Cms\Utils;

use Illuminate\Database\DatabaseManager;

class Variable
{
    protected $cache = array();

    /**
     * @var DatabaseManager
     */
    protected $db;

    public function __construct(DatabaseManager $db)
    {
        $this->db = $db;
    }

    public function get($name, $default = null, $nocache = false)
    {
        if (!$nocache && isset($this->cache[$name])) {
            return $this->cache[$name];
        }

        $obj = $this->getQuery()
            ->where('name', $name)
            ->select('value')
            ->first();

        if (is_null($obj)) {
            return $default;
        }

        return $obj->value;
    }

    public function set($name, $value)
    {
        if ($this->has($name)) {
            $this->getQuery()
                ->where('name', $name)
                ->update(array('value' => $value));
        }
        else {
            $this->getQuery()->insert(array('name' => $name, 'value' => $value));
        }

        $this->cache[$name] = $value;
    }

    public function has($name)
    {
        return $this->getQuery()->where('name', $name)->exists();
    }

    /**
     * @return \Illuminate\Database\Query\Builder
     */
    protected function getQuery()
    {
        return $this->db->table('variables');
    }
}