<?php

/**
 * Define a list with Obsering AsssetRepositories
 * This list of systems gets informed, when a new asset is available or i changed
 */


/**
 * Interface IObserver
 */
interface IObserver
{
    public function getUUid();
}

/**
 * Class AdobeCQObserver
 */
class AdobeCQObserver implements IObserver
{
    public $_uuid;

    public function getUUid()
    {
        return $this->_uuid;
    }

    public function __construct()
    {
        $this->_uuid = md5(uniqid());
    }
}

/**
 * Class CumulusObserver
 */
class CumulusObserver implements IObserver
{
    public $_uuid;

    public function getUUid()
    {
        return $this->_uuid;
    }

    public function __construct()
    {
        $this->_uuid = md5(uniqid());
    }
}

/**
 * Class MediaPoolObserver
 */
class MediaPoolObserver implements IObserver
{
    public $_uuid;

    public function getUUid()
    {
        return $this->_uuid;
    }

    public function __construct()
    {
        $this->_uuid = md5(uniqid());
    }
}

/**
 * Class Observer
 */
class Observer implements SplObserver
{
    /**
     * @var IObserver
     */
    public $observer;

    public function __construct(IObserver $observer)
    {
        $this->observer =  $observer;
    }

    /**
     * @param SplSubject $subject <p>
     * @return void
     */
    public function update(SplSubject $subject)
    {
        echo 'subject: '.$subject->getFileObject()->getUuid() .' was pubished to ' . $this->observer->getUuid() .' -> '.get_class($this->observer). PHP_EOL;
    }
}

/**
 * Class AssetSubject
 */
class AssetStorageSubject implements  SplSubject
{
    /**
     * @var SplObjectStorage
     */
    protected $_observers;

    /**
     * @var SplObjectStorage
     */
    private $_fileObject;

    /**
     * @param FileObject $fileObject
     */
    public function __construct(FileObject $fileObject)
    {
        $this->_fileObject = $fileObject;

        $this->_observers = new SplObjectStorage();
    }

    /**
     * Attaches Observer.
     *
     * @return void
     */
    public function attach(SplObserver $observer)
    {
        $this->_observers->attach($observer);
    }

    /**
     * Detach an observer
     *
     * @return void
     */
    public function detach(SplObserver $observer)
    {
        $this->_observers->detach($observer);
    }

    /**
     * Notify an observer
     * @return void
     */
    public function notify()
    {
        $this->_observers->rewind();

        while ($this->_observers->valid())
        {
            $this->_observers->current()->update($this);

            $this->_observers->next();
        }
    }

    /**
     * @return SplObjectStorage
     */
    public function getFileObject()
    {
        return $this->_fileObject;
    }
}


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


//create a filelist
$fileObject2= new FileObject();
$fileObject2->setName('a long name2');


//add fileobject to AssetPublisher
$assetPublisher = new AssetStorageSubject($fileObject2);

//create Observers who are interested on the  new asset
$AdobeCQ = new Observer(new AdobeCQObserver());
$Cumulus = new Observer(new CumulusObserver());
$MediaPool = new Observer(new MediaPoolObserver());

//register observers on assetpublisher
$assetPublisher->attach($AdobeCQ);
$assetPublisher->attach($Cumulus);
$assetPublisher->attach($MediaPool);

//publish new asset information
$assetPublisher->notify();