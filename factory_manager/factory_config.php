<?

class Core_Factory_Config extends Core_Abstract_Config {

  protected $config_data = array(
    'Core_CMS_Controller'=>'Core_CMS_Controller_Factory'
    ,'Core_PObject_Relation_Names' => 'Core_PObject_Relation_Names_Factory'
    ,'Lang_Object_Relation_Names' => 'Core_PObject_Relation_Names_Factory'   
  );
}