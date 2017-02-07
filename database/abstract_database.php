<?
abstract class Core_Abstract_Database{
  
  abstract function query($sql);
  
  abstract function getQueryResult($sql);
  
  abstract function lastInsertID();
  
} 