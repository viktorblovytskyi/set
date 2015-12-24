<?php

/**
 * @package Setting
 * @author Viktor Blovytskyi <viktorb@templatemonster.me>
 */

namespace test\SettingTest\Repository\TableGateway;

use PHPUnit_Framework_TestCase;
use Setting\Repository\TableGateway\Setting;

class SettingTest extends PHPUnit_Framework_TestCase
{
    protected $setting;

    protected $mockAdapter;

    protected $tableName;

    protected function setUp () {
        $this->mockAdapter = $this->getMockBuilder('Zend\Db\Adapter\Adapter')
                                  ->setMethods(null)
                                  ->disableOriginalConstructor()
                                  ->getMock();

        $this->setting = new Setting($this->mockAdapter);
        $this->tableName = 'settings';
    }

    protected function tearDown () {
        $this->mockAdapter = null;
        $this->setting = null;
        $this->tableName = null;
    }

    public function testGetObjectPrototype(){
        $this->assertTrue($this->setting->getObjectPrototype() instanceof\Setting\Model\Setting);
    }

    public function testGetTableName () {
        $this->assertEquals($this->setting->getTableName(), $this->tableName);
    }

}
