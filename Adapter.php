<?php

/**
 * Usage: If you implement a client that consumes webservices for instance and you want to have an easy way
 * to react on changes in the server you can use an adapter pattern. So you can guarantee that your clientcode will never change.
 * If Twitter changes a method you can simlle change the method in you adapter instead of changing clientcode
 */



interface ITwitter
{
    public function post($msg);
}

class Twitter implements ITwitter
{
    //this method will often changge
    public function post($msg)
    {
        echo $msg . PHP_EOL;
    }
}

class TwitterAdapter implements ITwitter
{
    public $twitter;

    public function __construct(Twitter $twitter)
    {
        $this->twitter = $twitter;
    }

    public function post($msg)
    {
        //here you can react on the change
        $this->twitter->post($msg);
    }
}

$cl = new TwitterAdapter(new Twitter());

//this call will never change
$cl->post("asda");