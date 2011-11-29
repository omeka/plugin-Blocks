<?php

class BlockConfigTable extends Omeka_Db_Table
{
    public $_alias = 'bct';

    /**
     * params keys:  module, controller, action, id_route
     * filters along the hierarchy of module, controller, action, id
     * if all three are there, do not return results that match only controller and action
     * similarly, if controller and action are there, do not return results that match only controller
     * @see application/libraries/Omeka/Db/Omeka_Db_Table::applySearchFilters()
     */
    
    public function findBy($params, $limit = null, $page = null)
    {
        $blockConfigs = parent::findBy($params, $limit, $page);
        if(isset($params['custom_route'])) {
            return $blockConfigs;
        }

        // where the config has non-null info that doesn't match the params, remove it
        foreach($blockConfigs as $index=>$blockConfig) {
            if(isset($params['controller']) && $blockConfig->controller && ($blockConfig->controller != $params['controller'])) {
                unset($blockConfigs[$index]);
                continue;
            }
            if(isset($params['action']) && $blockConfig->action && ($blockConfig->action != $params['action'])) {
                unset($blockConfigs[$index]);
                continue;
            }
            if(isset($params['id']) && $blockConfig->id_request && ($blockConfig->id_request != $params['id'])) {
                unset($blockConfigs[$index]);
                continue;
            }
        }

        return $blockConfigs;
    }
    
    public function applySearchFilters($select, $params)
    {
        if(isset($params['custom_route'])) {
            return $this->filterByCustomRoute($select, $params['custom_route']);
        } else if(isset($params['controller']) || isset($params['action']) || isset($params['id_request'])) {
            return $this->filterByRequest($select, $params);
        } else {
            foreach($params as $column=>$value) {
                $select->where("{$this->_alias}.$column = ?", $value);
                _log($select);
                return $select;
            }
            
        }
    }
    
    private function filterByCustomRoute($select, $params)
    {
        
        $select->where("{$this->_alias}.custom_route = ?", $params['custom_route']);
        return $select;
    }
    
    private function filterByRequest($select, $params)
    {
        $select->where("{$this->_alias}.controller IS NULL");
        $select->orWhere("{$this->_alias}.controller = ?", $params['controller']);
        $select->orWhere("{$this->_alias}.action = ?", $params['action']);
        $select->orWhere("{$this->_alias}.id_request = ?", $params['id']);
        return $select;
    }
    
}