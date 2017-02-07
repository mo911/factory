<?
class Core_Database_Factory extends Core_Factory{
  var $configmanager=DEPENDENCY; 
  
  protected function getConstructParams(array $construct_params = null) {
    $core_config = $this->configmanager->getConfig('core_config');
    return array(array('dbhost'=>$core_config['database']['params']['location'],'dbuser'=>$core_config['database']['params']['username'],'dbpass'=>$core_config['database']['params']['password'],'dbname'=>$core_config['database']['params']['database']));
  }
       
  protected function afterConstruction() {  
    $res = $this->instance->connect();
  }
}