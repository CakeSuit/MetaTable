<?php
namespace Cakesuit\MetaTable\Test\TestCase\Model\Behavior;

use Cake\TestSuite\TestCase;
use Cakesuit\MetaTable\Model\Behavior\MetaBehavior;

/**
 * Cakesuit\MetaTable\Model\Behavior\MetaBehavior Test Case
 */
class MetaBehaviorTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \Cakesuit\MetaTable\Model\Behavior\MetaBehavior
     */
    public $Meta;

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $model = $this->getMockForModel('Users');
        $model->setDisplayField('username');
        $model->belongsTo('meta_users');

        $this->Meta = new MetaBehavior($model, [
            'table' => 'meta_users'
        ]);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Meta);

        parent::tearDown();
    }

    /**
     * Test initial setup
     *
     * @return void
     */
    public function testInitialization()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
