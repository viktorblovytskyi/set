<?php
/**
 * @package Setting
 * @author Viktor Blovytskyi <viktorb@templatemonster.me>
 */

namespace SettingTest\Model;


use PHPUnit_Framework_TestCase;
use Setting\Model\Setting;

class SettingTest extends PHPUnit_Framework_TestCase
{
    protected $setting;

    protected function setUp () {
        $this->setting = new Setting();

    }

    protected function tearDown () {
        $this->setting = null;
    }

    public function testGetId () {
        $this->setting->setId(1);
        $this->assertEquals($this->setting->getId(),1);
    }

    public function testGetPrefix () {
        $this->setting->setPrefix('OTHER');
        $this->assertEquals($this->setting->getPrefix(),'OTHER');
    }

    public function testGetName () {
        $this->setting->setName('Test');
        $this->assertEquals($this->setting->getName(),'Test');
    }

    public function testGetType () {
        $this->setting->setType('text');
        $this->assertEquals($this->setting->getType(),'text');
    }

    public function testGetArchive () {
        $this->setting->setArchive(true);
        $this->assertTrue($this->setting->getArchive());
        $this->setting->setArchive(false);
        $this->assertFalse($this->setting->getArchive());
    }

    public function testGetDescription () {
        $this->setting->setDescription('test test');
        $this->assertEquals($this->setting->getDescription(),'test test');
    }

    public function testGetValue () {
        $this->setting->setValue('test');

        $this->assertEquals($this->setting->getValue(), 'test');
    }
}
