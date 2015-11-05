<?php

require 'vendor/autoload.php';

use Aws\Credentials\CredentialProvider;

require 'includes/functions.php';
// Global Config
$cnx['auth'] = CredentialProvider::ini('default', '/home/isantolin/.aws/credentials');
$cnx['region'] = 'us-east-1';
$cnx['version'] = 'latest';

$inp['bucket'] = 'buckettranscode';

// Upload Side
$inp['upload_file'] = 'lisa.avi';

// Elastic Transcode Side
$trns['upload_file'] = 'lisa.avi';
$trns['extension'] = 'webm';
$trns['pipeline_id'] = '1446733011329-hkh6d3';
$trns['transcode_subdir'] = 'subdirtranscode/';
$trns['transcode_preset'] = '1351620000001-100240';
$trns['conversion_filename'] = microtime(true);


$upload = s3_upload($cnx, $inp);

if ($upload) {
    $transcode = elastictranscode_transcode($cnx, $trns);
    var_dump($transcode);
}
