<?php

/**
 * Usage: If you want to
 */


/**
 * Class FileObject
 */

class FileObject
{

    /**
     * @var MD5 Hash
     */
    private $_uuid;

    /**
     * @var string
     */
    private $_name;

    /**
     * @var string
     */
    private $_type;

    /**
     * @var string
     */
    private $_path;

    /**
     * @var string
     */
    private $_metadata;

    /**
     * set a uniqid
     */
    public function __construct()
    {
        $this->_uuid = md5(uniqid());
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->_name;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->_type;
    }

    /**
     * @return mixed
     */
    public function getPath()
    {
        return $this->_path;
    }

    /**
     * @return mixed
     */
    public function getMetadata()
    {
        return $this->_metadata;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->_name = $name;
    }


    /**
     * @param mixed $type
     */
    public function setType($type)
    {
        $this->_type = $type;
    }

    /**
     * @param mixed $path
     */
    public function setPath($path)
    {
        $this->_path = $path;
    }

    /**
     * @param mixed $metadata
     */
    public function setMetadata($metadata)
    {
        $this->_metadata = $metadata;
    }

    /**
     * @return MD5
     */
    public function getUuid()
    {
        return $this->_uuid;
    }
}

interface Ipublisher
{
    public function publish(FileObject $fileObject);
}

class CommonRepositoryPublisher implements Ipublisher
{
    public function publish(FileObject $fileObject)
    {
        echo PHP_EOL .' => '.get_class($this) . ' => ' . __METHOD__ .' => ' . $fileObject->getName();
    }
}


abstract class CommonPublisherDecorator implements Ipublisher
{
    public $_publisher;

    public function __construct(Ipublisher $publisher)
    {
        $this->_publisher = $publisher;
    }

    public function publish(FileObject $fileObject)
    {
        $this->_publisher->publish($fileObject);
    }
}

class MarsRepositoryPublisher extends CommonPublisherDecorator
{
    public function publish(FileObject $fileObject)
    {
        $fileObject->setName($fileObject->getName(). '->Mars');
        $this->_publisher->publish($fileObject);
        echo PHP_EOL .' => '.get_class($this->_publisher) . ' => ' . __METHOD__ .' => ' . $fileObject->getName();
    }
}

class CummulusRepositoryPublisher extends CommonPublisherDecorator
{
    public function publish(FileObject $fileObject)
    {
        $fileObject->setName($fileObject->getName().'->Cummulus');
        $this->_publisher->publish($fileObject);
        echo PHP_EOL .' => '.get_class($this->_publisher) . ' => ' . __METHOD__ .' => ' . $fileObject->getName();
    }
}

class AdobeRepositoryPublisher extends CommonPublisherDecorator
{
    public function publish(FileObject $fileObject)
    {
        $fileObject->setName($fileObject->getName().'->Adobe');
        $this->_publisher->publish($fileObject);
        echo PHP_EOL .' => '.get_class($this->_publisher) . ' => ' . __METHOD__ .' => ' . $fileObject->getName();
    }
}


class PictureParkRepositoryPublisher extends CommonPublisherDecorator
{
    public function publish(FileObject $fileObject)
    {
        $fileObject->setName($fileObject->getName().'->PicturePark');
        $this->_publisher->publish($fileObject);
        echo PHP_EOL .' => '.get_class($this->_publisher) . ' => ' . __METHOD__ .' => ' . $fileObject->getName();

    }
}




$fileObject = new FileObject();
$fileObject->setName("coolio_namus.pdf");

//first undecorated publish
$basePublisher = new CommonRepositoryPublisher();
$basePublisher->publish($fileObject);

//decorate the base published file
$picturePark = new PictureParkRepositoryPublisher($basePublisher);

//Call decorator on last decorator
$adobePublisher = new AdobeRepositoryPublisher($picturePark);

//Call decorator on last decorator
$cummulusRepositoryPublisher = new CummulusRepositoryPublisher($adobePublisher);

//Call decorator on last decorator
$marsRepositoryPublisher = new MarsRepositoryPublisher($cummulusRepositoryPublisher);
$marsRepositoryPublisher->publish($fileObject);

echo PHP_EOL;