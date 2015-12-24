<?php
/**
 * @package Setting
 * @author Viktor Blovytskyi <viktorb@templatemonster.me>
 */

namespace Setting\Controller;


/**
 * Контроллер для работы с settings
 * @package Setting\Controller
 */
class SettingController extends SettingRestfulController
{
    /**
     * @inheritdoc
     */
    public function getList()
    {
        return $this->getListActionHandle();
    }

    /**
     * This function returns all prefixes.
     * @return \Application\Response\ResponseContainer
     */
    public function getPrefixAction () {
        return $this->getPrefixesActionHandle();
    }

    /**
     * @inheritdoc
     */
    public function get($id)
    {
        return $this->getActionHandle($id);
    }

    /**
     * This function returns serialized or unserialized value.
     * @return \Application\Response\ResponseContainer
     */
    public function getSerializerAction () {
        $data = $this->params()->fromPost('value');
        $flag = $this->params()->fromPost('flag');
        return $this->getSerializerActionHandle($data, $flag);
    }

    /**
     * @inheritdoc
     */
    public function create($data)
    {
        return $this->createActionHandle($data);
    }

    /**
     * @inheritdoc
     */
    public function update($id, $data)
    {
        return $this->updateActionHandle($id, $data);
    }
}
