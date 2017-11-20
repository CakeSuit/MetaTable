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
        $this->_items = $collection->indexBy($this->getConfig('keyField'));
        $valueField = $this->getConfig('valueField');

        foreach ($this->_items as $name => $item) {
            $table = TableRegistry::get($item->getSource());
            if (!$valueField) {
                $valueField = $table->getDisplayField();
            }
            $this->{$name} = $item{$valueField};
        }
    }

    public function __get($key)
    {
        // Silent is gold
    }

    public function get($key, $default = null)
    {
        return Hash::get($this->_items, $key, $default);
    }

    public function has($key)
    {
        return Hash::check($this->_items, $key, null);
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
}