<?php

namespace Xima\RestApiBundle\Model\VO;

class Query {

    const SORT_DIRECTION_ASC = 'asc';
    const SORT_DIRECTION_DESC = 'desc';

    /**
     * @var integer;
     */
    protected $limit = 10;

    /**
     * @var integer;
     */
    protected $offset = 0;

    /**
     * @var string;
     */
    protected $sortBy;

    /**
     * @var string;
     */
    protected $sortDirection;

    /**
     * @var string;
     */
    protected $lang = 'de';
    
    /**
     *  @var string
     */
    protected $generatedSql;
    
    public function bind($params) {
        if (isset($params['limit'])) {
            $this -> limit = $params['limit'];
        }
        if (isset($params['offset'])) {
            $this -> offset = $params['offset'];
        }
        if (isset($params['lang'])) {
            $this -> lang = $params['lang'];
        }
    }
    
    protected function string2DateTime($date, $setToEndOfDay = false)
    {
        if (!$date) return FALSE;
    
        $date = str_replace('-', '.', $date);
        $dateArray = explode('.', $date);
    
        if (isset($dateArray[2]) && trim($dateArray[2]) == '') unset ($dateArray[2]);
    
        $count = count($dateArray);
    
        switch ($count)
        {
        	case 2:
        	    $dateTime = new \DateTime(date ('Y', time()) .'-' . $dateArray[1] . '-' . $dateArray[0]);
        	    break;
        	case 3:
        	    $dateTime = new \DateTime($dateArray[2] . '-' . $dateArray[1] . '-' . $dateArray[0]);
        	    break;
        	default:
        	    $dateTime = null;
        }
        
        if ($dateTime && $setToEndOfDay) {
            $dateTime->setTime ( 23, 59, 59);
        }
    
        return $dateTime;
    }

    public function getLimit() {
        return $this -> limit;
    }

    public function setLimit($limit) {
        $this -> limit = $limit;
    }

    public function getOffset() {
        return $this -> offset;
    }

    public function setOffset($offset) {
        $this -> offset = $offset;
    }

    public function getLang() {
        return $this -> lang;
    }

    public function setLang($lang) {
        $this -> lang = $lang;
    }

    public function getSortBy() {
        return $this -> sortBy;
    }

    public function setSortBy($sortBy) {
        $this -> sortBy = $sortBy;
    }

    public function getSortDirection() {
        return $this -> sortDirection;
    }

    public function setSortDirection($sortDirection) {
        $this -> sortDirection = $sortDirection;
    }

    public function getGeneratedSql(){
        return $this->generatedSql;
    }
    
    public function setGeneratedSql($generatedSql){
        $this->generatedSql = $generatedSql;
    }

}
?>