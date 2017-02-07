<?

class Core_Factory {

  protected $factorymanager;
  protected $classname;
  protected $construct_params;
  protected $instance;
  protected $configmanager;
  
  final function __construct(Core_Factory_Manager $factorymanager, string $classname, array $construct_params = null) 
  {
    $this->factorymanager = $factorymanager;
    $this->classname = $classname;
    $this->construct_params = $construct_params;
    $this->configmanager = Core_Config_Manager::newInstance();
  }
  
  protected function getNewInstance(string $classname, array $construct_params = null) 
  {
    $reflection = new ReflectionClass($classname);
    $construct_params = (array) $this->getConstructParams($construct_params);
    return $this->instance = $reflection->newInstanceArgs($construct_params); 
  }
  
  protected function afterConstruction() {
  } 
  
  protected function getConstructParams(array $construct_params = null) : array
  {
    return $construct_params;
  } 
  
  protected function getClassToCreate(string $classname) : string
  {
    return $classname;
  } 
  
  final function newInstance() 
  {
    $classname = $this->getClassToCreate($this->classname);
    if (!$classname) {
      throw new Core_Classname_Empty_Exception();
    }
    if (!class_exists($classname)) {
      throw new Core_Class_Not_Found_Exception($classname);
    }
    $instance = $this->getNewInstance($classname, $this->construct_params);
    $this->factorymanager->doInjections($instance); 
    $this->afterConstruction();
    return $instance;
  }
  
}