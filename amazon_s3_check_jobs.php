<?php

require 'vendor/autoload.php';

use Aws\Credentials\CredentialProvider;

require 'includes/functions.php';
// Global Config
$inp['bucket'] = 'buckettranscode';
$trns['transcode_subdir'] = 'subdirtranscode/';


$cnx['auth'] = CredentialProvider::ini('default', '/home/isantolin/.aws/credentials');
$cnx['region'] = 'us-east-1';
$cnx['version'] = 'latest';


$job_data = elastictranscode_job($cnx, '1446756680959-wpig16');
echo "https://s3.amazonaws.com/".$inp['bucket']."/".$trns['transcode_subdir'].$job_data['Job']['Output']['Key'];
