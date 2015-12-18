<?php

use Aws\S3\S3Client;
use Aws\S3\Exception\S3Exception;
use Aws\ElasticTranscoder\ElasticTranscoderClient;

function s3_upload($conn, $input) {
    $S3Upload = S3Client::factory(array(
                'credentials' => $conn['auth'],
                "region" => $conn['region'],
                "version" => $conn['version'],
    ));

    try {
        // Upload data.
        $result = $S3Upload->putObject(array(
            'Bucket' => $input['bucket'],
            'Key' => $input['upload_file'],
            'SourceFile' => $input['source_file'],
        ));

        return $result;
    } catch (S3Exception $e) {
        echo $e->getMessage() . "\n";
    }
}

function elastictranscode_transcode($conn, $transcode) {
    $elasticTranscoder = ElasticTranscoderClient::factory(array(
                'credentials' => $conn['auth'],
                'region' => $conn['region'], // dont forget to set the region
                'version' => $conn['version'],
    ));

    $job = $elasticTranscoder->createJob(array(
        'PipelineId' => $transcode['pipeline_id'],
        'OutputKeyPrefix' => $transcode['transcode_subdir'],
        'Input' => array(
            'Key' => $transcode['upload_file'],
            'FrameRate' => 'auto',
            'Resolution' => 'auto',
            'AspectRatio' => 'auto',
            'Interlaced' => 'auto',
            'Container' => 'auto',
        ),
        'Outputs' => array(
            array(
                'ThumbnailPattern' => $transcode['thumbnail_subdir'] . $transcode['thumbnail_filename'] . 'thumb{count}',
                'Key' => $transcode['conversion_filename'] . '.' . $transcode['extension'],
                'Rotate' => 'auto',
                'PresetId' => $transcode['transcode_preset'],
            ),
        ),
    ));
    return $job;
}

function elastictranscode_job($conn, $jobId) {
    $elasticTranscoder = ElasticTranscoderClient::factory(array(
                'credentials' => $conn['auth'],
                'region' => $conn['region'], // dont forget to set the region
                'version' => $conn['version'],
    ));

    $job = $elasticTranscoder->readJob(array('Id' => $jobId));
    return $job;
}
