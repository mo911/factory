<?
class Core_Database extends Core_Abstract_Database {

    private $config = array();
    private $dblink;
    static $mysqli;
    
    static protected $query_count;
    
    static protected $db,$conn;

    final function __construct($params) {
      $this->config = $params;
    }

    protected function escape($param) {
        if (is_string($param)) {
            return "'" . mysql_real_escape_string($param, $this->dblink) . "'";
        }
        return mysql_real_escape_string($param, $this->dblink);
    }
    
    function connect(){
      if( ! self::$mysqli = new mysqli($this->config['dbhost'], $this->config['dbuser'], $this->config['dbpass'], $this->config['dbname'])){
        throw new Core_Exception("Database connect error");
        die("Nem sikerült csatlakozni az adatbázishoz!");
      }else{
        mysqli_set_charset(self::$mysqli, "utf8");       
        mysqli_query(self::$mysqli,"SET NAMES 'utf8'");
      } 
    }
    
    /**
     * Lefuttatja a beállított SQL parancsot
     */ 
    function query($sql){  
      if ($result = mysqli_query(self::$mysqli,$sql)) {
        self::$query_count++;
        return $result;
      }
      return null;
    }

    function getQueryResult($sql){
      $result = array();
      if($query = $this->query($sql)){                       
        while ($row = mysqli_fetch_assoc($query))
        {                    
          $result[] = $row;
        }
      }      
      return $result;
    }
    
    function lastInsertID(){ 
      return mysqli_insert_id( self::$mysqli );
    }
  
    function getColumnsFromTable($table_name){
      $sql = "SHOW COLUMNS FROM ".$table_name;
      $result = $this->getQueryResult($sql);
      foreach ($result as $value) {
        $return[] = $value['Field'];	
      }
      return $return;
    }
    
    function loadList($sql,$azonosito){
      $res = $this->getQueryResult($sql);
      $ret = array(); 
      if(isset($res)){
        foreach ($res as $key=>$value) {
      	 $ret[] = $value[$azonosito];
        }
      }       
      return $ret;
    }
    
    function getQueryCount(){
      return self::$query_count;
    }

}