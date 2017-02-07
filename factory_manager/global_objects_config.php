<?

class Core_Global_Objects_Config extends Core_Abstract_Config {
  
  protected $config_data = array(
    'request_params' => array('factory'=>'Core_Request_Params_Factory','class'=>'Core_Request_Params')
    ,'lang' => array('factory'=>'Core_Lang_Manager_Factory','class'=>'Core_Lang_Manager')
    ,'po_manager' => array('factory'=>'Core_PObject_Factroy','class'=>'Core_PObject_Manager')
    ,'database' => array('factory'=>'Core_Database_Factory','class'=>'Core_Database')
    ,'relation_names' =>array('factory'=>'Core_PObject_Relation_Names_Factory','class'=>'Core_PObject_Relation_Names')
    ,'lang_relation_names' =>array('factory'=>'Core_PObject_Relation_Names_Factory','class'=>'Lang_Object_Relation_Names')
    ,'authentication' =>array('factory'=>'Core_Authentication_Factory',"class"=>"Core_Authentication")
    ,'query_builder' => array('factory'=>'Core_Factory',"class"=>"Core_Query_Builder")
    ,'core_session' => array('factory'=>'Core_Factory','class'=>'Core_Session')
    ,'date_utils' => array('factory'=>'Core_Factory', 'class'=> 'Date_Utils')
    ,'downloader' => array('factory'=>'Core_Factory', 'class'=>'Downloader')
    ,'lang_object_manager' => array('factory'=>'Core_PObject_Factroy','class'=>'Lang_Object_Manager')
    ,'lang' => array('factory'=>'Lang_Factory','class'=>'Lang')
    ,'site_title' => array('factory'=>'Core_Factory','class'=>'Core_CMS_Page_Site_Title')
  );
  
}