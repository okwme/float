<?php
App::uses('AwsModel', 'AwsDatasource.Model');

class AwsS3Model extends AwsModel {
  public $useDbConfig = 'aws_s3';

  public function __call($method, $args) {
    switch (strtolower($method)) {
      case 'abortmultipartupload':
      case 'completemultipartupload':
      case 'copyobject':
      case 'createbucket':
      case 'createmultipartupload':
      case 'deletebucket':
      case 'deletebucketcors':
      case 'deletebucketlifecycle':
      case 'deletebucketpolicy':
      case 'deletebuckettagging':
      case 'deletebucketwebsite':
      case 'deleteobject':
      case 'deleteobjects':
      case 'getbucketacl':
      case 'getbucketcors':
      case 'getbucketlifecycle':
      case 'getbucketlocation':
      case 'getbucketlogging':
      case 'getbucketnotification':
      case 'getbucketpolicy':
      case 'getbucketrequestpayment':
      case 'getbuckettagging':
      case 'getbucketversioning':
      case 'getbucketwebsite':
      case 'getobject':
      case 'getobjectacl':
      case 'getobjecttorrent':
      case 'headbucket':
      case 'listbuckets':
      case 'listmultipartuploads':
      case 'listobjectversions':
      case 'listobjects':
      case 'listparts':
      case 'putbucketacl':
      case 'putbucketcors':
      case 'putbucketlifecycle':
      case 'putbucketlogging':
      case 'putbucketnotification':
      case 'putbucketpolicy':
      case 'putbucketrequestpayment':
      case 'putbuckettagging':
      case 'putbucketversioning':
      case 'putbucketwebsite':
      case 'putobject':
      case 'putobjectacl':
      case 'restoreobject':
      case 'uploadpart':
      case 'uploadpartcopy':
      case 'waituntilbucketexists':
      case 'waituntilbucketnotexists':
      case 'waituntilobjectexists':
        $args[0] += array(
          'Bucket' => $this->getDataSource()->getBucket(),
        );
      break;
    }
    return call_user_func_array(array($this->getService('s3'), $method), $args);
  }

  public function S3Client() {
    return $this->getDataSource()->getS3Client();
  }

  public function getObjectUrl($key, $expires = null, $args = array()) {
    return $this->S3Client()->getObjectUrl($this->getDataSource()->getBucket(), $key, $expires, $args);
  }
}
