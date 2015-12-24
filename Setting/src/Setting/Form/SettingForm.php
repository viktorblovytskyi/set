<?php
/**
 * @package Setting
 * @author Viktor Blovytskyi <viktorb@templatemonster.me>
 */

namespace Setting\Form;

use Application\Form\Filter\HtmlEntitiesWithTags;
use Zend\Form\Form;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Форма для setting
 * @package Setting\Form
 */
class SettingForm extends Form implements InputFilterProviderInterface
{
    protected $serviceFactory;

    public function __construct(ServiceLocatorInterface $serviceLocatorInterface) {
        parent::__construct();
        $this->serviceFactory = $serviceLocatorInterface;
    }

    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->add(array(
            'name' => 'id',
            'type' => 'text',
        ));
        
        $this->add(array(
            'name' => 'type',
            'type' => 'text',
        ));
        
        $this->add(array(
            'name' => 'name',
            'type' => 'text',
        ));
        
        $this->add(array(
            'name' => 'description',
            'type' => 'text',
        ));

        $this->add(array(
            'name' => 'value',
            'type' => 'text',
        ));

        $this->add(array(
            'name' => 'prefix',
            'type' => 'text',
        ));

        $this->add(array(
            'name' => 'archive',
            'type' => 'text'
        ));
    }

    /**
     * Should return an array specification compatible with
     * {@link Zend\InputFilter\Factory::createInputFilter()}.
     *
     * @return array
     */
    public function getInputFilterSpecification()
    {

        return array(
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
                            'adapter' => $this->serviceFactory->get('DbAdapter'),
                            'table'   => 'settings',
                            'field'   => 'name',
                            'exclude' => array(
                                'field' => 'id',
                                'value' => (!empty($this->data['id']) ? $this->data['id'] : -1),
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
}
