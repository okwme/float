<?php
App::uses('AwsModel', 'AwsDatasource.Model');

class AwsSnsModel extends AwsModel {
  public $useDbConfig = 'aws_sns';

  public function __call($method, $args) {
    switch (strtolower($method)) {
      case 'createplatformendpoint':
      case 'deleteplatformapplication':
      case 'getplatformapplicationattributes':
      case 'listendpointsbyplatformapplication':
      case 'setplatformapplicationattributes':
        $args[0] += array(
          'PlatformApplicationArn' => $this->getPlatformApplicationArn(),
        );
      break;
    }
    return call_user_func_array(array($this->getDataSource()->getConnection(), $method), $args);
  }

  public function SnsClient() {
    return $this->getDataSource()->getConnection();
  }

  public function getRegion() {
    return $this->getDataSource()->getConfig('region');
  }

  public function getPlatformApplicationArn() {
    return $this->getDataSource()->getConfig('platform_application_arn');
  }

  public function getType() {
    return $this->getDataSource()->getConfig('type');
  }

  public function getTopicPrefix() {
    return $this->getDataSource()->getConfig('topic_prefix');
  }
}
