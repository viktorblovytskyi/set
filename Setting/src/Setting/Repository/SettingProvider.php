<?php
/**
 * @package Setting
 * @author Viktor Blovytskyi <viktorb@templatemonster.me>
 */
namespace Setting\Repository;

use Application\Repository\QueryDesigner\ModificatorContainer\QuerySortersContainer;
use Application\Repository\QueryDesigner\ModificatorContainer\QueryParametersContainer;
use Application\Repository\QueryDesigner\QueryDesigner;
use Setting\Repository\ServiceEntity\SettingServiceEntity;
use Application\Repository\ServiceEntity\AbstractServiceEntity;
use Zend\Db\ResultSet\HydratingResultSet;
use Zend\Db\Sql\Predicate\Expression;
use Zend\Db\Sql\PreparableSqlInterface;
use Zend\Stdlib\Hydrator\ArraySerializable;
use Application\Repository\Provider;

/**
 * Провайдер для работы с данными
 * @package Setting\Repository
 */
class SettingProvider extends Provider {

    /**
     * Флаг возвращения результата в виде ассоциативного
     */
    CONST HYDRATE_ARRAY = 0;

    /**
     * Флаг возвращения результата в виде объекта
     */
    CONST HYDRATE_OBJECT = 1;

    /**
     * @var AbstractServiceEntity
     */
    private $serviceEntity;


    public function __construct (
        SettingServiceEntity $serviceEntity,
        QueryParametersContainer $parametersContainer,
        QuerySortersContainer $sortersContainer,
        QueryDesigner $queryDesigner
    ) {
        parent::__construct(
            $serviceEntity,
            $parametersContainer,
            $sortersContainer,
            $queryDesigner
        );
        $this->serviceEntity = $serviceEntity;
    }

    /**
     * Returns all prefixes
     * @param int $hydrationMode
     * @param int $total количество всех записей
     * @return array|\Zend\Db\Adapter\Driver\ResultInterface|\Zend\Db\ResultSet\ResultSet
     */
    public function getPrefixes($hydrationMode = self::HYDRATE_OBJECT, &$total = 0) {

        $query = $this->serviceEntity->getAllPrefix();

        $totalQuery = clone $query;
        $totalQuery->columns(
            array('total' => new Expression('COUNT(' . $this->serviceEntity->getTableName() . '.id)'))
        );

        $totalResult = $this->executeQuery($totalQuery);
        $total       = (int) $totalResult->current()['total'];

        if ($total > 0) {
            $query  = $this->processPagination($query);
            $result = $this->executeQuery($query);

            if ($hydrationMode == self::HYDRATE_OBJECT and $result->isQueryResult()) {
                $modelClassName = $this->serviceEntity->getEntityClassName();

                $resultSet = new HydratingResultSet(new ArraySerializable(), new $modelClassName());
                $result = $resultSet->initialize($result);
            }

            return $result;
        }

        return array();
    }

    /**
     * Выполняет запрос
     * @param PreparableSqlInterface $query
     * @return \Zend\Db\Adapter\Driver\ResultInterface
     */
    private function executeQuery(PreparableSqlInterface $query)
    {
        $statement = $this->serviceEntity->getQueryBuilder()->prepareStatementForSqlObject($query);
        return $statement->execute();
    }
}