<?php
/**
 * @package Setting
 * @author Viktor Blovytskyi <viktorb@templatemonster.me>
 */

namespace Setting\Repository\ServiceEntity;

use Application\Repository\ServiceEntity\AbstractServiceEntity;
use Zend\Db\Sql\Predicate\Expression;

/**
 * ServiceEntity для se
 * @package Setting\Repository\ServiceEntity
 */
class SettingServiceEntity extends AbstractServiceEntity
{

    /**
     * Базовый запрос на выборку элементов
     * @return \Zend\Db\Sql\Select
     */
    public function getBaseQuery() {
        return $this
            ->getQueryBuilder()
            ->select()
            ->from($this->getTableName());
    }

    public function getAllPrefix() {
        return $this
            ->getQueryBuilder()
            ->select($this->getTableName())
            ->columns(array(new Expression('DISTINCT(prefix) as prefix')));
    }

    /**
     * Массив доступных параметров и привязанных к ним полей из запроса
     * @return array
     */
    public function getAvailParameters()
    {
        return array(
            'id'          => 'id',
            'name'        => 'name',
            'value'       => 'value',
            'description' => 'description',
            'type'        => 'type',
            'archive'     => 'archive',
            'prefix'      => 'prefix'
        );
    }

    /**
     * Возвращает имя класса главной модели entity
     * @return string
     */
    public function getEntityClassName()
    {
        return 'Setting\Model\Setting';
    }
}
