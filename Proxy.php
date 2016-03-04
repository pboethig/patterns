<?php

/**
 * Use this pattern to handle huge objects during runtime
 */


/**
 * Interface IProxy
 */
interface IProxy
{
    public function render();

    public function load();

    public function export();
}


/**
 * Class AdobeIndesign
 */
class AdobeIndesign implements IProxy
{

    /**
     * Contains fileObject metadata
     *
     * @var IndesignFileObject
     */
    protected $_fileObject;

    /**
     * Loads object and creates proxydata
     *
     * @param IndesignFileObject $fileObject
     */
    public function __construct(IndesignFileObject $fileObject)
    {
        $this->_fileObject = $fileObject;

        $this->load($fileObject);

        //create fake object data
        $this->_createProxyImage();
    }

    /**
     * load the heavy file from disk
     */
    public function load()
    {
        echo PHP_EOL . 'heavyfileobject: '. $this->_fileObject->getUuid(). ' is loaded from service';
    }

    /**
     * Creates proxyimage to reduce runtime
     */
    protected function _createProxyImage()
    {
        $this->_fileObject->setProxyImage('/path/to/small/proxyImage');

        echo PHP_EOL . 'proxy image created: ' . $this->_fileObject->getProxyImage();
    }


    /**
     * Renders original baseobject
     *
     * @return mixed
     */
    public function render()
    {
        echo PHP_EOL . 'originalfile rendered: ' . $this->_fileObject->getUuid();
    }

    /**
     * export original baseObject
     */
    public function export()
    {
        echo PHP_EOL . 'originalfile exported: ' . $this->_fileObject->getUuid();
    }
}

/**
 * Class proxyobject
 */
class AdobeIndesignProxy implements IProxy
{
    public $_fileObject;

    /**
     * @var IndesignFileObject
     */
    protected $_baseObject = null;

    /**
     * Create original baseObject
     *
     * @param IndesignFileObject $fileObject
     */
    public function __construct(IndesignFileObject $fileObject)
    {
        $this->_fileObject = $fileObject;

        if(null === $this->_baseObject)
        {
            $this->_baseObject = new AdobeIndesign($fileObject);
        }
    }

    /**
     * Renders proxyimage
     */
    public function render()
    {
        echo PHP_EOL . 'proxyobject: ' . $this->_fileObject->getProxyImage() . ' rendered';
    }

    /**
     * Loads abstract fileobject data representation
     */
    public function load()
    {
        echo PHP_EOL . 'proxyobject: ' . $this->_fileObject->getXmlRepresentation() . ' loaded';
    }

    /**
     * Exports original baseObject
     */
    public function export()
    {
        $this->_baseObject->export();
    }
}


/**
 * Class IndesignFileObject. A metadata representation
 */
class IndesignFileObject
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
     * @var binray
     */
    private $_proxyImage;


    private $_xmlRepresentation;
    /**
     * set a uniqid
     */
    public function __construct()
    {
        $this->_uuid = md5(uniqid());
    }

    /**
     * @param mixed $xmlRepresentation
     */
    public function setXmlRepresentation($xmlRepresentation)
    {
        $this->_xmlRepresentation = $xmlRepresentation;
    }

    /**
     * @return string
     */
    public function getXmlRepresentation()
    {
        return $this->_xmlRepresentation;
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

    /**
     * @return binray
     */
    public function getProxyImage()
    {
        return $this->_proxyImage;
    }

    /**
     * @param binray $proxyImage
     */
    public function setProxyImage($proxyImage)
    {
        $this->_proxyImage = $proxyImage;
    }


}


//create a filelist
$fileObject2= new IndesignFileObject();
$fileObject2->setName('a very heavy file');

/**
 * Get Proxy and do some actions on it
 */
$adobeIndesignProxy = new AdobeIndesignProxy($fileObject2);

//heavy initial load
$adobeIndesignProxy->load();

//actions on a fakeobject
$adobeIndesignProxy->render();

//nother action on the original object
$adobeIndesignProxy->export();

echo PHP_EOL;
