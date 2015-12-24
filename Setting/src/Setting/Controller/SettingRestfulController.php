<?php
/**
 * @package Setting
 * @author Viktor Blovytskyi <viktorb@templatemonster.me>
 */
namespace Setting\Controller;

use Application\Controller\ARestfulController;
use Application\Form\Filter\HtmlEntitiesDecode;
use Application\Repository\QueryDesigner\ModificatorFormatStrategy\ExtJSorterStrategy;
use Application\Repository\QueryDesigner\ModificatorContainer\QueryParametersContainer;
use Application\Repository\QueryDesigner\ModificatorContainer\QuerySortersContainer;
use Application\Repository\QueryDesigner\QueryDesigner;
use Application\Repository\ServiceEntity\AbstractServiceEntity;
use Application\Response\ResponseContainer;
use Application\Repository\QueryDesigner\ModificatorFormatStrategy\ExtJsFilterStrategy;
use Setting\Repository\ServiceEntity\SettingServiceEntity;
use Setting\Repository\SettingProvider;

/**
 * Абстрактный RESTFUL контроллер с выводом результатов
 * @package Setting\Controller
 */
class SettingRestfulController extends ARestfulController {

    /**
     * @inheritdoc
     */
    public function getPrefixesActionHandle()
    {
        $sorters = new QuerySortersContainer(
            $this->getServiceEntity(),
            new ExtJSorterStrategy(),
            $this->params()->fromQuery('sort')
        );

        $parameters = new QueryParametersContainer(
            $this->getServiceEntity(),
            new ExtJsFilterStrategy(),
            $this->params()->fromQuery('filter'),
            $this->params()->fromQuery('query')
        );

        $provider = new SettingProvider($this->getServiceEntity(), $parameters, $sorters, new QueryDesigner());

        $result   = $provider->getPrefixes($provider::HYDRATE_ARRAY, $total);
        $filter   = new HtmlEntitiesDecode();
        $result   = $filter->filter($result);

        return new ResponseContainer(array('response' => $result, 'total' => $total));
    }

    /**
     * Возвращает ServiceEntity
     * @return AbstractServiceEntity
     */
    public function getServiceEntity()
    {
        return new SettingServiceEntity(
            $this->getServiceLocator()->get('SettingGateway'),
            $this->getServiceLocator()->get('QueryBuilder')
        );
    }

    /**
     * Возвращает название класса формы для управления entity
     * @return string
     */
    protected function getFormName()
    {
        return '\Setting\Form\SettingForm';
    }

    /**
     * This function chooses what to do, serialise or unserialise?
     * @param $data
     * @param $flag - boolean - true    - to serialize,
     *                          false   - to unserialize
     * @return ResponseContainer
     */
    public function getSerializerActionHandle ($data, $flag) {
        return new ResponseContainer(array('response' => $flag === 'true' ? $this->serializeData($data) : $this->unserializeData($data)));
    }

    /**
     * This function serialise data.
     * @param $data - Mixed
     * @return string
     */
    function serializeData ($data) {
        $obj = json_decode($data, true);
        $newStructure = $this->buildStructureFromObj($obj);
        return serialize($newStructure);
    }

    /**
     * This function unserialise data.
     * @param $data - String
     * @return string
     */
    function unserializeData ($data) {
        $newStructure = unserialize($data);
        return $this->buildResponseFromObject($newStructure);
    }

    /**
     * This function  pushes data in assoc array.
     * @param $array - Array
     * @param $key - String or Int
     * @param $value - Mixed
     * @return string
     */
    function array_push_assoc($array, $key, $value) {
        $array[$key] = $value;
        return $array;
    }

    /**
     * This function pushes object in array.
     * @param $array - Array
     * @param $value - Mixed
     * @return string
     */
    function array_push_object($array, $value) {
        $array[] = $value;
        return $array;
    }

    /**
     * This function builds new structure from object.
     * @param $object - Object
     * @param $array - Array
     * @return string
     */
    function buildStructureFromObj ($object, $array = array()) {
        for ($i = 0; $i < count($object); $i++) {
            foreach ($object[$i] as $key => $value) {
                switch ($key) {
                    case 'type':
                        switch ($value) {
                            case 'a':
                                $array = $this->array_push_assoc($array, $object[$i]['key'], $this->buildStructureFromObj($object[$i]['value'], array()));
                                break;
                            case 'o':
                                $array = $this->array_push_object($array, (object) $this->buildStructureFromObj($object[$i]['value'], array()));
                                break;
                            case 'i':
                                $array[trim($object[$i]['key'], '"')] = (int) $object[$i]['value'];
                                break;
                            case 'd':
                                $array[trim($object[$i]['key'], '"')] = (double) $object[$i]['value'];
                                break;
                            case 'b':
                                $array[trim($object[$i]['key'], '"')] = (boolean) $object[$i]['value'];
                                break;
                            case 's':
                                $array[trim($object[$i]['key'], '"')] = (string) $object[$i]['value'];
                                break;
                            case 'n':
                                $array[trim($object[$i]['key'], '"')] = null;
                                break;
                            default:
                                break;
                        }
                        break;
                    default:
                        break;
                }
            }
        }

        return $array;
    }

    /**
     * This function builds new array structure from object.
     * @param $obj - Object
     * @param $newString - String
     * @return string - result
     */
    function buildResponseFromObject ($obj, $newString = '') {
        foreach ($obj as $key => $value) {
            switch (gettype($value)) {
                case 'double':
                    $newString .= '{"key":"' . trim($key,'"') . '","type":"d","value":' . $value . '},';
                    break;
                case 'integer':
                    $newString .= '{"key":"' . trim($key,'"') . '","type":"i","value":' . $value . '},';
                    break;
                case 'string':
                    $newString .= '{"key":"' . trim($key,'"') . '","type":"s","value":"' . $value . '"},';
                    break;
                case 'boolean':
                    $newString .= '{"key":"' . trim($key,'"') . '","type":"b","value":"' . $value . '"},';
                    break;
                case 'array':
                    $newString .= '{"key":"' . trim($key,'"') . '","type":"a","value":[' . $this->buildResponseFromObject($value, '') . ']},';
                    break;
                case 'object':
                    $newString .= '{"key":"' . trim($key,'"') . '","type":"o","value":[' . $this->buildResponseFromObject((array) $value, '') . ']},';
                    break;
                case 'NULL':
                    $newString .= '{"key":"' . trim($key,'"') . '","type":"n","value":"' . null . '"},';
                    break;
                default:
                    break;
            }
        }

        return str_replace(',]', ']', '[' . $newString . ']');
    }

}