<?php

/**
 * Usage if you want to change the technique to process data depending on your needs
 * FI. You have a file wich can be uploaded via HTTP cause its small so you can use a simple httpclient
 *
 * And you have a uge file wich has to be junked. So you have to use guzzle or so.
 *
 */

interface ClientInterface
{
    public function upload($data);
}

/**
 * Class SimpleClient
 * Strategy 1
 */
class SimpleClient implements ClientInterface
{
    public function upload($data)
    {
        echo "Simple upload:" . $data . PHP_EOL;
    }
}

/**
 * Class Guzzle
 *
 * Strategy 2
 */
class Guzzle implements ClientInterface
{
    public function upload($data)
    {
        echo "guzzle post:" . $data . PHP_EOL;
    }
}

/**
 * Class UploadStategy
 */
class UploadStrategy implements ClientInterface
{

    function upload($message)
    {
        if(strlen($message) > 134)
        {
            $obj = new SimpleClient();
        }
        else
        {
            $obj = new Guzzle();
        }

        $obj->upload($message);

    }
}

$uploadStrategy = new UploadStrategy;

$uploadStrategy->upload("huge post data");
