<?php
/**
 * @package Setting
 * @author Viktor Blovytskyi <viktorb@templatemonster.me>
 */

namespace Setting\Repository\TableGateway;

use Application\Repository\TableGateway\AbstractTableGateway;

class Setting extends AbstractTableGateway
{
    /**
     * Возвращает прототип объекта модели
     * @return object
     */
    public function getObjectPrototype()
    {
        return new \Setting\Model\Setting();
    }

    /**
     * Возвращает имя таблицы
     * @return string
     */
    public function getTableName()
    {
        return 'settings';
    }
}
