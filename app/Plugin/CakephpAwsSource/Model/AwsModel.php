<?php
class AwsModel extends AppModel {
  public $useDbConfig = 'aws';

  public function getService($name, $throwAway = false) {
    return ConnectionManager::getDataSource($this->useDbConfig)->get($name, $throwAway);
  }

  public function setService($key, $service) {
    return ConnectionManager::getDataSource($this->useDbConfig)->set($key, $service);
  }
}
