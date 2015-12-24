<?php

/**
 * @package Setting
 * @author Viktor Blovytskyi <viktorb@templatemonster.me>
 */

namespace test\SettingTest\Repository\ServiceEntity;

use PHPUnit_Framework_TestCase;
use Setting\Repository\ServiceEntity\SettingServiceEntity;

class SettingServiceEntityTest extends PHPUnit_Framework_TestCase
{
    protected $settingServiceEntity;

    protected $mockAbstractTableGateway;

    protected $mockSql;

    protected $parameters;

    protected $className;

    protected $mockSelect;

    protected function setUp () {

        $this->mockAbstractTableGateway = $this->getMockBuilder('Application\Repository\TableGateway\AbstractTableGateway')
            ->disableOriginalConstructor()
            ->getMock();

        $this->mockSql = $this->getMockBuilder('Zend\Db\Sql\Sql')
            ->disableOriginalConstructor()
            ->getMock();

        $this->mockSelect = $this->getMockBuilder('Zend\Db\Sql\Select')
            ->disableOriginalConstructor()
            ->getMock();

        $this->mockAbstractTableGateway->method('getQueryBuilder')
            ->willReturn($this->mockSql);

        $this->mockSql->method('select')
            ->willReturn($this->mockSelect);

        $this->mockSelect->method('from')
            ->willReturn('SELECT * FROM settings');

        $this->settingServiceEntity = new SettingServiceEntity($this->mockAbstractTableGateway, $this->mockSql);

        $this->parameters = array(
            'id'          => 'id',
            'name'        => 'name',
            'value'       => 'value',
            'description' => 'description',
            'type'        => 'type',
            'archive'     => 'archive',
            'prefix'      => 'prefix'
        );

        $this->className = 'Setting\Model\Setting';

    }

    protected function tearDown(){
        $this->settingServiceEntity = null;
        $this->mockSql = null;
        $this->mockAbstractTableGateway = null;
        $this->parameters = null;
        $this->className = null;
        $this->mockSelect = null;
    }

    public function testGetAvailParameters () {
        $this->assertEquals($this->settingServiceEntity->getAvailParameters(), $this->parameters);
    }

    public function testGetEntityClassName () {
        $this->assertEquals($this->settingServiceEntity->getEntityClassName(), $this->className);
    }

    public function testGetBaseQuery () {
        $this->assertEquals($this->settingServiceEntity->getBaseQuery(),'SELECT * FROM settings');
    }

}
