<?php
/**
 * @package Setting
 * @author Viktor Blovytskyi <viktorb@templatemonster.me>
 */

namespace test\SettingTest\Form;

use Application\Form\Filter\HtmlEntitiesWithTags;
use PHPUnit_Framework_TestCase;
use Setting\Form\SettingForm;

class SettingFormTest extends PHPUnit_Framework_TestCase
{

    protected $setting;

    protected $filterArray;

    protected $serviceFactory;

    protected function setUp(){
        $this->serviceFactory = $this->getMockBuilder('Zend\ServiceManager\ServiceLocatorInterface')
                                     ->disableOriginalConstructor()
                                     ->getMock();

        $this->setting = new SettingForm($this->serviceFactory);

        $this->filterArray = array(
            'prefix' => array(
                'required' => false,
            ),

            'id' => array(
                'required' => false,
                'filters'  => array(
                    array('name' => 'Int'),
                ),
            ),

            'type'   => array(
                'required' => true,
            ),

            'name'  => array(
                'required' => false,
                'filters' => array(HtmlEntitiesWithTags::getDefaultFilterConfig()),
                'validators' => array(
                    array(
                        'name' => 'Zend\Validator\Db\NoRecordExists',
                        'options' => array(
                            'adapter' => null,
                            'table'   => 'settings',
                            'field'   => 'name',
                            'exclude' => array(
                                'field' => 'id',
                                'value' => -1,
                            ),
                        ),
                    ),
                ),
            ),

            'description' => array(
                'required' => false,
            ),

            'value' => array(
                'required' => false,
            ),
        );
    }

    protected function tearDown(){
        $this->setting = null;
    }

    public function testGetInputFilterSpecification(){
        $this->assertEquals($this->setting->getInputFilterSpecification(),$this->filterArray);
    }

}
