<?
class Core_Query_Builder{
  var $database=DEPENDENCY;
  var $relation_names=DEPENDENCY;
  var $po_manager=DEPENDENCY;
  
  private $core_pobject_relations = 'core_pobject_relations';
  
  static protected $operatorok = array(
                        'eq' => ' = '
                        ,'noteq' => ' <> '
                        ,'gteq' => ' >= '
                        ,'lteq' => ' <= '
                        ,'gt' => '>'
                        ,'lt' => '<'
                        ,'in' => ' IN '
                        ,'notin' => ' NOT IN '
                        ,'like' => ' LIKE '
                        ,'notlike' => ' NOT LIKE '
                );
  
  function __construct(){
  }

  function query($class = array(), $fields = array(), $condition = array(),$order = array(),$limit_min = null, $limit_max = null){
    $sql = $this->buildSQL($class,$fields,$condition,$order,$limit_min,$limit_max);   
    return $this->database->getQueryResult($sql);
  }
  
  function buildSQL($class,$fields,$conditions,$order,$limit_min,$limit_max){
    $sql = "SELECT ";
    $count = 0;
    foreach ($fields as $key=>$field) {     
      if($count > 0)
        $sql .= " , ";
      $explode = explode(".", $field);
      if($explode[1] === "*"){
        $sql .= " `".$this->po_manager->getTableName($explode[0])."`.".$explode[1]." ";
      }else{
        $sql .= " `".$this->po_manager->getTableName($explode[0])."`.`".$explode[1]."` ";
      }
      if(is_string($key)){
        $sql .= " AS ".$key." \n";
      }else{
        $sql.= " \n";
      }
      $count++;
    }
     
    $fo_class = current($class);
    if (strpos($fo_class,'/') !== false) {
        $fo_class_explode = explode("/", $fo_class);
        $sql .= "FROM `".$this->po_manager->getTableName($fo_class_explode[0])."` \n";
    }else{
      $sql .= "FROM `".$this->po_manager->getTableName($fo_class)."` \n";
    }
    
    $elozo_class = null;
    while(next($class) != false){
      $tabla = empty($elozo_class) ? $fo_class : $elozo_class;
      if (strpos($tabla,'/') !== false) {
        $tabla_explode = explode("/", $tabla);
        $sql .= "STRAIGHT_JOIN `".$this->po_manager->getTableName($tabla_explode[1])."` ON `".$this->po_manager->getTableName($tabla_explode[0])."`.`id` = `".$this->po_manager->getTableName($tabla_explode[1])."`.`id` \n";
        $tabla = $tabla_explode[0];
      }
      if(current($class) != false){
        $kapcsolat = current($class);
        $kapcsolat_nev = trim($kapcsolat,".");
        $irany = $kapcsolat[0] !== '.' ? 'b_id' : 'a_id';
      }else{
        break;
      }
      next($class);
      $tabla2 = current($class); 
      $irany2 = $irany === 'b_id' ? 'a_id' : 'b_id';
        $sql .= "STRAIGHT_JOIN `".$this->core_pobject_relations."` ".$kapcsolat_nev."_kapcs
                  ON `".$kapcsolat_nev."_kapcs`.`".$irany."` = `".$this->po_manager->getTableName($tabla)."`.`id`
                    AND `".$kapcsolat_nev."_kapcs`.`relation_id` = '".$this->relation_names->getRelationID($kapcsolat_nev)."'
                 STRAIGHT_JOIN `".$this->po_manager->getTableName($tabla2)."`
                  ON `".$kapcsolat_nev."_kapcs`.`".$irany2."` = `".$this->po_manager->getTableName($tabla2)."`.`id`
        ";
      $elozo_class = $tabla2; 
     
    }
    
    if(!empty($conditions)){
      $sql .= " WHERE ";
      $count = 0;
      foreach ($conditions as $condition_key=>$condition) {
        $condition_key_explode = explode(".",$condition_key);
        foreach ($condition as $operator=>$value) {
          if($operator === 'in' or $operator === 'notin'){
            $sql .= " `".$this->po_manager->getTableName($condition_key_explode[0])."`.`".$condition_key_explode[1]."` ".self::$operatorok[$operator]." (".implode(", ", $value).")";	  
          }else{
            if(is_numeric($value)){
              $jobb_oldal = $value;
            }else{
              $jobb_oldal = " '".$value."' ";
            }
            if($count>0){
              $sql .= " AND ";
            }
            $sql .= " `".$this->po_manager->getTableName($condition_key_explode[0])."`.`".$condition_key_explode[1]."` ".self::$operatorok[$operator].$jobb_oldal  ;	
          }
          
        } 
        $count++; 
      }
    }
    
    if(!empty($order)){
      $sql .= " ORDER BY ";
      $count = 0;
      foreach ($order as $order_key=>$value) {
        if($count > 0)
          $sql .= " , "; 
        $order_key_explode = explode(".",$order_key); 
        $sql .= " `".$this->po_manager->getTableName($order_key_explode[0])."`.`".$order_key_explode[1]."` ".$value;   	
        $count++;
      }
    }
    if(isset($limit_min) and isset($limit_max)){
      $sql .= " LIMIT ".$limit_min.", ".$limit_max;
    }
     return $sql;  
  }
}