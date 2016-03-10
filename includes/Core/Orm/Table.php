<?php

namespace Core\Orm;

/**
 * Class Table
 * @package Core\Orm
 */
Class Table {

    /**
     * @return string
     */
    public function setSlug(){
        return $this->username.".".$this->id;
    }

}
