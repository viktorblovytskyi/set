<?php
/**
 * @package Setting
 * @author Viktor Blovytskyi <viktorb@templatemonster.me>
 */

namespace test\SettingTest\Controller;

use PHPUnit_Framework_TestCase;
use Setting\Controller\SettingRestfulController;

class SettingRestfulTest extends PHPUnit_Framework_TestCase
{

    protected $settingRestfulController;

    protected $serialize;

    protected $object;

    protected function setUp () {
        $this->settingRestfulController = new SettingRestfulController();
        $this->serialize = 'a:7:{s:25:"template_type_description";s:2:"35";s:15:"categoriesNames";s:2:"35";s:12:"package_name";s:2:"20";s:12:"search_words";s:1:"0";s:17:"type_search_words";s:2:"99";s:21:"category_search_words";s:2:"99";s:23:"properties_search_words";s:2:"99";}';
        $this->object = '[{"key":"template_type_description","type":"s","value":"35"},{"key":"categoriesNames","type":"s","value":"35"},{"key":"package_name","type":"s","value":"20"},{"key":"search_words","type":"s","value":"0"},{"key":"type_search_words","type":"s","value":"99"},{"key":"category_search_words","type":"s","value":"99"},{"key":"properties_search_words","type":"s","value":"99"}]';
    }

    protected function tearDown () {
        $this->settingRestfulController = null;
        $this->serialize = null;
    }

    public function testSerializeData () {
        $this->assertEquals($this->settingRestfulController->serializeData($this->object),$this->serialize);
    }

    public function testUnserializeData () {
        $this->assertEquals($this->settingRestfulController->unserializeData($this->serialize),$this->object);

    }

}
