<?php
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

/**
 * Add an interface to get the decorators consistent
 *
 * Interface Ipublisher
 */
interface Ipublisher
{
    public function publish(FileObject $fileObject);
}

/**
 * First class which executes the first required functionality
 *
 * Class CommonRepositoryPublisher
 */
class CommonRepositoryPublisher implements Ipublisher
{
    public function publish(FileObject $fileObject)
    {
        Logger::log(' => '.get_class($this) . ' => ' . __METHOD__ .' => ' . $fileObject->getName());
    }
}

/**
 * Now the customer wants a change on the designed behavior.
 * For that we create an abstract class, from which all further extensions can inherit
 *
 * This abstract class saves a decorating classobject and implements the same methods like the originally class
 *
 * Class CommonPublisherDecorator
 */
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

/**
 * Now we can extend the decorator to modify the originally object
 *
 * Class MarsRepositoryPublisher
 */
class MarsRepositoryPublisher extends CommonPublisherDecorator
{
    public function publish(FileObject $fileObject)
    {
        $fileObject->setName($fileObject->getName(). '->Mars');

        $this->_publisher->publish($fileObject);

        Logger::log(' => ' . get_class($this->_publisher) . ' => ' . __METHOD__ . ' => ' . $fileObject->getName());
    }
}

/**
 * Wen can even modify the modified object.
 * Simply create a new instance with the previous decorator instance
 *
 * Class CummulusRepositoryPublisher
 */
class CummulusRepositoryPublisher extends CommonPublisherDecorator
{
    public function publish(FileObject $fileObject)
    {
        $fileObject->setName($fileObject->getName().'->Cummulus');

        $this->_publisher->publish($fileObject);

        Logger::log(' => ' . get_class($this->_publisher) . ' => ' . __METHOD__ . ' => ' . $fileObject->getName());
    }
}

/**
 * Another decorator
 *
 * Class AdobeRepositoryPublisher
 */
class AdobeRepositoryPublisher extends CommonPublisherDecorator
{
    public function publish(FileObject $fileObject)
    {
        $fileObject->setName($fileObject->getName().'->Adobe');

        $this->_publisher->publish($fileObject);

        Logger::log(' => ' . get_class($this->_publisher) . ' => ' . __METHOD__ . ' => ' . $fileObject->getName());
    }
}

/**
 * And so on...
 *
 * Class PictureParkRepositoryPublisher
 */
class PictureParkRepositoryPublisher extends CommonPublisherDecorator
{
    public function publish(FileObject $fileObject)
    {
        $fileObject->setName($fileObject->getName().'->PicturePark');

        $this->_publisher->publish($fileObject);

        Logger::log(' => ' . get_class($this->_publisher) . ' => ' . __METHOD__ . ' => ' . $fileObject->getName());
    }
}

/**
 * Class Logger
 */
class Logger
{
    public static function log(String $message)
    {
        echo $message . PHP_EOL;
    }
}

//get an object to decorate
$fileObject = new FileObject();
$fileObject->setName("coolio_namus.pdf");

//first process an undecorated object (originally designed functionality)
$basePublisher = new CommonRepositoryPublisher();
$basePublisher->publish($fileObject);

//decorate the base object
$picturePark = new PictureParkRepositoryPublisher($basePublisher);

//modoify the modified object
$adobePublisher = new AdobeRepositoryPublisher($picturePark);

//adn so on
$cummulusRepositoryPublisher = new CummulusRepositoryPublisher($adobePublisher);

//finally decorate the last decorator..
$marsRepositoryPublisher = new MarsRepositoryPublisher($cummulusRepositoryPublisher);
$marsRepositoryPublisher->publish($fileObject);

echo PHP_EOL;