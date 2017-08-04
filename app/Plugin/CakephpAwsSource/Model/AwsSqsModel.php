<?php
App::uses('AwsModel', 'AwsDatasource.Model');

class AwsSqsModel extends AwsModel {
  public $useDbConfig = 'aws_sqs';

  public function __call($method, $args) {
    switch (strtolower($method)) {
      case 'addpermission':
      case 'changemessagevisibility':
      case 'changemessagevisibilitybatch':
      case 'deletemessage':
      case 'deletequeue':
      case 'getqueueattributes':
      case 'listdeadlettersourcequeues':
      case 'receivemessage':
      case 'removepermission':
      case 'sendmessage':
      case 'sendmessagebatch':
      case 'setqueueattributes':
        if (strlen($this->getDataSource()->getConfig('queue_url'))) {
          $queue_url = array(
            'QueueUrl' => $this->getDataSource()->getConfig('queue_url'),
          );
          if (isset($args[0])) {
            $args[0] += $queue_url;
          } else {
            $args[0] = $queue_url;
          }
        }
      break;
    }
    return call_user_func_array(array($this->getDataSource()->getConnection(), $method), $args);
  }

  public function sqsClient() {
    return $this->getDataSource()->getConnection();
  }

}
