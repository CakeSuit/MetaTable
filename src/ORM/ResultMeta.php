<?php
/**
 * Created by PhpStorm.
 * User: frederickoller
 * Date: 20/11/2017
 * Time: 07:01
 */

namespace Cakesuit\MetaTable\ORM;


use Cake\Collection\Collection;
use Cake\Collection\CollectionTrait;
use Cake\Core\InstanceConfigTrait;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;

class ResultMeta
{
    use InstanceConfigTrait;

    /**
     * Default configuration.
     *
     * @var array
     */
    protected $_defaultConfig = [
        'keyField' => 'meta_key',
        'valueField' => 'meta_value',
    ];

    protected $_originalItems;
    protected $_items;

    public function __construct(array $items, array $config)
    {
        $this->setConfig($config);
        $this->_originalItems = $items;
        $this->initialize();
    }

    public function initialize()
    {
        $collection = new Collection($this->getOriginalItems());
        $this->_items = $collection
            ->indexBy($this->getConfig('keyField'))
            ->toArray();
        $valueField = $this->getConfig('valueField');

        foreach ($this->_items as $name => $item) {
            $table = TableRegistry::get($item->getSource());
            if (!$valueField) {
                $valueField = $table->getDisplayField();
            }
            $this->set($name, $item{$valueField});
        }
    }

    public function __get($key)
    {
        // Silent is gold
    }

    /**
     * Get de properties
     * @param $properties
     * @param null $default
     * @return null|void
     */
    public function get($properties, $default = null)
    {
        if (isset($this->{$properties})) {
            $default = $this->{$properties};
        }
        return $default;
    }

    public function set($key, $value)
    {
        $this->{$key} = $value;
    }

    /**
     * @param $key
     * @param null $default
     * @return mixed
     */
    public function fetch($key, $default = null)
    {
        $key = $this->_formatKey($key);
        return Hash::get($this->_items, $key, $default);
    }

    /**
     * Chack if the path exist
     * @param $key
     * @return bool
     */
    public function has($key)
    {
        $key = $this->_formatKey($key);
        return Hash::check($this->_items, $key);
    }

    /**
     * Check if empty value
     * @param $key
     * @return bool
     */
    public function isEmpty($key)
    {
        $key = $this->_formatKey($key);
        $val = $this->get($key, null);
        return empty($val);
    }

    /**
     * Check equal value
     * @param $expected
     * @param $key
     * @param bool $strict
     * @return bool
     */
    public function equalTo($expected, $key, $strict = false)
    {
        $key = $this->_formatKey($key);
        $key = $this->fetch($key);
        if ($strict) {
            return $expected === $key;
        } else {
            return $expected == $key;
        }
    }

    /**
     * Check if metas is empty
     * @return bool
     */
    public function isEmptyMetas()
    {
        return !((bool)count($this->_items));
    }

    /**
     * @return \ArrayObject
     */
    public function getOriginalItems()
    {
        return $this->_originalItems;
    }

    /**
     * @return mixed
     */
    public function getItems()
    {
        return $this->_items;
    }

    protected function _formatKey($key)
    {
        if (strpos($key, '.') === false)
        {
            $key .= '.' . $this->getConfig('valueField');
        }
        return $key;
    }
}