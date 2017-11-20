<?php
namespace Cakesuit\MetaTable\Model\Behavior;

use ArrayObject;
use Cake\Event\Event;
use Cake\ORM\Behavior;
use Cake\ORM\Entity;
use Cake\ORM\Query;
use Cake\ORM\Table;
use Cake\Utility\Inflector;
use Cakesuit\MetaTable\ORM\ResultMeta;
use Cakesuit\MetaTable\ORM\Result;

/**
 * Meta behavior
 */
class MetaBehavior extends Behavior
{

    /**
     * Default configuration.
     *
     * @var array
     */
    protected $_defaultConfig = [
        'metaTableName' => null,
        'propertyName' => 'meta',
        'keyField' => 'meta_key',
        /**
         * If empty, the Table::getDisplayField() is picked
         */
//        'valueField' => 'meta_value',
        /**
         * false: insert into meta
         * true: insert into object entities
         * both: meta & object entities
         */
        'addProperties' => true,
    ];

    protected $_metaTableName;

    public function initialize(array $config)
    {
        parent::initialize($config);
        $metaTableName = Inflector::underscore($this->getConfig('metaTableName'));
        $this->_metaTableName = Inflector::pluralize($metaTableName);
    }

    /**
     *
     */
    protected function getMetaTableName()
    {
        return $this->_metaTableName;
    }

    public function beforeFind(Event $event, Query $query, ArrayObject $options, $primary)
    {
        $query->find('meta');
    }

    /**
     * Format result with meta
     * @param Query $query
     * @param array $options
     */
    public function findMeta(Query $query, array $options = [])
    {
        $query->formatResults(function ($entities) {
            $entities->each(function ($entity, $key) {
                if ($metas = $entity->{$this->getMetaTableName()}) {
                    $metas = new ResultMeta(
                        $metas,
                        $this->getConfig()
                    );

                    $addProperties = $this->getConfig('addProperties');
                    if ($addProperties === false) {
                        $entity->{$this->getConfig('propertyName')} = $metas;
                    } elseif ($addProperties === true) {
                        $this->_setMetaProperties($entity, $metas);
                    } else {
                        $entity->{$this->getConfig('propertyName')} = $metas;
                        $this->_setMetaProperties($entity, $metas);
                    }
                }
            });

            return $entities;
        });
    }

    /**
     *
     * @param $entity
     * @param $metas
     */
    protected function _setMetaProperties(Entity $entity, ResultMeta $metas)
    {
        foreach ($metas->getItems() as $key => $item) {
            if (!$entity->has($key)) {
                $entity->set($key, $metas->{$key});
            }
        }
    }
}
