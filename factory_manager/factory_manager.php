<?
class Core_Factory_Manager {

  const DEFAULT_FACTORY_CLASS = 'Core_Factory';
  const DEPENDENCY_KEYWORD = 'DEPENDENCY'; 
    
  private $global_objects_config;
  private $factory_config;
  private $global_objects_cache;
  private $factories_for_classes_cache;
  private $configmanager;

  static function newInstance(array $factory_config = null,array $global_objects_config = null) : Core_Factory_Manager
  {    
    return new self($factory_config,$global_objects_config);
  }
  
  final function __construct(array $factory_config = null, array $global_objects_config = null) 
  {
    $this->configmanager = Core_Config_Manager::newInstance();
    $this->factory_config = $factory_config ? (array) $factory_config : $this->configmanager->getConfig('factory_config');
    $this->global_objects_config = $global_objects_config ? (array) $global_objects_config : $this->configmanager->getConfig('global_objects_config'); 
  }
  
  final function getGlobalObject($name) 
  {       
    if (!isset($this->global_objects_cache[$name])) {     
      if (!$config = $factory_class = $this->global_objects_config[$name]) {
        throw new Core_Exception($name.' global object not found in config');  
      } 
      $factory_class = $config['factory'] ?: self::DEFAULT_FACTORY_CLASS;  
      $factory = new $factory_class($this,$config['class'],$config['construct_params']);
      $this->global_objects_cache[$name] = $factory->newInstance($args);
    }
    return $this->global_objects_cache[$name];
  }
  
  final function newObject($classname)
  {
    $args = func_get_args();
    unset($args[0]);
    $factory_class = $this->getFactoryClassForClass($classname);   
    $factory = new $factory_class($this,$classname,$args);    
    return $factory->newInstance($args);
  }
  
  final function getFactoryClassForClass($classname) 
  {
    $factory_class = $this->factory_config[$classname];
    
    if (empty($factory_class)) {
      $factory_class = self::DEFAULT_FACTORY_CLASS;        
    }

    if ($factory_class !== self::DEFAULT_FACTORY_CLASS and !is_subclass_of($factory_class, self::DEFAULT_FACTORY_CLASS)) {
      throw new Core_Class_Not_Instance_Exception($factory_class,self::DEFAULT_FACTORY_CLASS);
    }
    return $factory_class;
  }
  
  final function doInjections($obj) 
  {
    //$reflection = new ReflectionClass($obj);
    //$properties = $reflection->getProperties(ReflectionProperty::IS_PROTECTED | ReflectionProperty::IS_PRIVATE);
    /*foreach ($properties as $property) {
      $property->setAccessible(true);
      if ($property->getValue($obj) === self::DEPENDENCY_KEYWORD) {
        $name = $property->getName();
        if ($default_inject = $this->getDefaultDependency($name)) {
          //$property->setValue($obj,$default_inject);
          $obj->$name = $default_inject;
        } else {
          $dependency_object = $this->getGlobalObject($name);
          //$property->setValue($obj,$dependency_object);
          $obj->$name = $dependency_object;
        }      
      }	
    }*/
    $class_vars = get_class_vars(get_class($obj));
    foreach ($class_vars as $key=>$value) {
      if ($value === self::DEPENDENCY_KEYWORD) {
        if ($default_inject = $this->getDefaultDependency($key)) {          
          $obj->$key = $default_inject;
        } else {
          $dependency_object = $this->getGlobalObject($key);          
          $obj->$key = $dependency_object;
        }        
      }	 
    }
  } 
  
  final protected function getDefaultDependency(string $name) 
  {
    switch ($name) {
      case 'factorymanager': return $this;
      case 'configmanager': return $this->configmanager;
    } 
  }  

}