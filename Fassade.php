<?php

/**
 * Usage: This example explains a complex interaction with many objects and its complex methods
 * To simplyfy the usage of all the objects the fassadepattern encapsulate all methodcalls to a simply call
 */


class FileSystemStorage {

    public function put($object)
    {
        //do stuff
    }

    public function post($expression)
    {
        //do stuff
    }

    public function get($expression)
    {
        //do stuff
    }

    public function delete($expression)
    {
        //do stuff
    }
}


class MetadataDatabase {
    public function put($object)
    {
        //do stuff
    }

    public function post($expression)
    {
        //do stuff
    }

    public function get($expression)
    {
        //do stuff
    }

    public function delete($expression)
    {
        //do stuff
    }
}

class MessageService {
    public function put($object)
    {
        //do stuff
    }

    public function post($expression)
    {
        //do stuff
    }

    public function get($expression)
    {
        //do stuff
    }

    public function delete($expression)
    {
        //do stuff
    }
}

class LogStash {
    public function put($object)
    {
        //do stuff
    }

    public function post($expression)
    {
        //do stuff
    }

    public function get($expression)
    {
        //do stuff
    }

    public function delete($expression)
    {
        //do stuff
    }
}

class RenderEngine {
    public function put($object)
    {
        //do stuff
    }

    public function post($expression)
    {
        //do stuff
    }

    public function get($expression)
    {
        //do stuff
    }

    public function delete($expression)
    {
        //do stuff
    }
}

class EmailEngine {
    public function put($object)
    {
        //do stuff
    }

    public function post($expression)
    {
        //do stuff
    }

    public function get($expression)
    {
        //do stuff
    }

    public function delete($expression)
    {
        //do stuff
    }
}


class HttpClient {
    public function put($object)
    {
        //do stuff
    }

    public function post($expression)
    {
        //do stuff
    }

    public function get($expression)
    {
        //do stuff
    }

    public function delete($expression)
    {
        //do stuff
    }
}



//normaly you would do somethong like that to edit a template file from a storage

$metadaObject = new MetadataDatabase();
$fileUrl = $metadaObject->get('/cqr//content/simplexx/');

$httpClient = new HttpClient();
$file = $httpClient->get($fileUrl);

$loggStash = new LogStash();
$loggStash->post("new file recied");

$file .="a simple textaddition";

$renderEngine = new RenderEngine();
$renderEngine->post($file);

$message = new MessageService();
$messageObject = new stdClass();
$messageObject->body = "here the messagebody";
$message->put($messageObject);

//and so on.

/**
 * Now we want to use this functionality in a fassade, so that we only have to call 1 method
 */

class DocumentFassade
{

    public $file;

    public $fileUrl;

    public function __construct($expression)
    {
        $metadaObject = new MetadataDatabase();
        $this->fileUrl = $metadaObject->get($expression);

        $httpClient = new HttpClient();
        $this->file = $httpClient->get($this->fileUrl);

        $loggStash = new LogStash();
        $loggStash->post("new file recied");

    }

    public function post($fileObject)
    {
        $renderEngine = new RenderEngine();
        $renderEngine->post($fileObject);

        $message = new MessageService();
        $messageObject = new stdClass();
        $messageObject->body = "a simple messagebody";
        $message->put($messageObject);

        //a worker checks incomming messages an processes them
    }
}



/**
 * Now we only have to instancia the fassade edit the document and post it back to storage
 */
$DocumentFassade = new DocumentFassade("/cqr//content/simplexx/");

//edit the document
$DocumentFassade->file.='add some text';

//save it back
$DocumentFassade->post($DocumentFassade->file);

//thats all







