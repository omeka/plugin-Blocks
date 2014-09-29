<?php
/**
 * @package Blocks\models\Table
 */
class Table_BlockConfig extends Omeka_Db_Table
{
    /**
     * @param array $params Keys are: module, controller, action, id_route.
     * Filters along the hierarchy of module, controller, action, id
     * If all three are there, do not return results that match only controller
     * and action similarly, if controller and action are there, do not return
     * results that match only controller.
     *
     * @see application/libraries/Omeka/Db/Omeka_Db_Table::applySearchFilters()
     */
    public function findBy($params, $limit = null, $page = null)
    {
        $blockConfigs = parent::findBy($params, $limit, $page);

        if (isset($params['custom_route'])) {
            return $blockConfigs;
        }

        // Where the config has non-null info that doesn't match the params, remove it
        foreach ($blockConfigs as $index => $blockConfig) {
            if (isset($params['controller']) && $blockConfig->controller && ($blockConfig->controller != $params['controller'])) {
                unset($blockConfigs[$index]);
                continue;
            }
            if (isset($params['action']) && $blockConfig->action && ($blockConfig->action != $params['action'])) {
                unset($blockConfigs[$index]);
                continue;
            }
            if (isset($params['id']) && $blockConfig->id_request && ($blockConfig->id_request != $params['id'])) {
                unset($blockConfigs[$index]);
                continue;
            }
        }
        return $blockConfigs;
    }

    public function applySearchFilters($select, $params)
    {
        $alias = $this->getTableAlias();
        if (isset($params['custom_route'])) {
            $this->filterByCustomRoute($select, $params['custom_route']);
        } elseif (isset($params['controller']) || isset($params['action']) || isset($params['id_request'])) {
            $this->filterByRequest($select, $params);
        } else {
            parent::applySearchFilters($select, $params);
        }
    }

    private function filterByCustomRoute($select, $params)
    {
        $alias = $this->getTableAlias();
        $select->where("`$alias`.`custom_route` = ?", $params['custom_route']);
    }

    private function filterByRequest($select, $params)
    {
        $alias = $this->getTableAlias();
        $select->where("`$alias`.`controller` IS NULL");
        $select->orWhere("`$alias`.`controller` = ?", $params['controller']);
        $select->orWhere("`$alias`.`action` = ?", $params['action']);
        if (isset($params['id'])) {
            $select->orWhere("`$alias`.`id_request` = ?", $params['id']);
        }
    }
}
