<?php
/**
 * Interface IRepositioryCommand
 *
 * Needed methods in commands
 */
interface IRepositioryCommand
{
    /**
     * Adds a command to the stack
     *
     * @param IRepositioryCommand $command
     * @return mixed
     */
    public function addCommandToStack(IRepositioryCommand $command);

    /**
     * Returns sortorder on stack
     *
     * @return mixed
     */
    public function getStackSortOrder();

    /**
     * Executes command
     *
     * @return mixed
     */
    public function execute();

    /**
     * Logs.
     *
     * @param String $message
     * @return mixed
     */
    public function log(String $message);
}

/**
 * Interface IContentRepository.
 *
 * Has to be implemented from all repositoryclients
 */
interface IContentRepository
{
    /**
     * @param SplObjectStorage $fileObjectList
     * @return mixed
     */
    public function postFileList(SplObjectStorage $fileObjectList);

    /**
     * @param String $expression
     * @return mixed
     */
    public function get(String $expression);

    /**
     * @param SplObjectStorage $fileObjectList
     * @return mixed
     */
    public function put(SplObjectStorage $fileObjectList);

    /**
     * @param SplObjectStorage $fileObjectList
     * @return mixed
     */
    public function delete(SplObjectStorage $fileObjectList);

    /**
     * @param String $message
     * @return mixed
     */
    public function log(String $message);
}

/**
 * Interface ICommandDataFacade
 */
interface ICommandDataFacade
{
    /**
     * @return mixed
     */
    public function getData();

    /**
     * Executes commandstack
     *
     * @return mixed
     */
    public function execute();
}

/**
 * Class ContentRepositoryCommandStack
 *
 * Implements commandstorage for later execution
 */
class ContentRepositoryCommandStack implements ICommandDataFacade
{
    /**
     * @var SplObjectStorage
     */
    private static $_stack;

    /**
     * @var ContentRepositoryCommandStack
     */
    protected static $_instance;

    /**
     * @var IRepositioryCommand
     */
    private $_command;

    /**
     * Returns instance
     *
     * @return ContentRepositoryCommandStack
     */
    public static function getInstance()
    {
        if(!self::$_instance)
        {
            self::$_instance =  new self;

            self::$_stack = new SplObjectStorage();
        }

        return self::$_instance;
    }

    /**
     * Adds a command
     *
     * @param IRepositioryCommand $command
     */
    public function addCommandToStack(IRepositioryCommand $command)
    {
        self::$_stack->attach($command);

        $this->log(PHP_EOL.__METHOD__ . PHP_EOL . '=> command: ' . $command->getId() . ' retrieved and added to stack');
    }

    /**
     * Executes commands
     */
    public function execute()
    {
        foreach (self::$_stack as $sortOrder => $command)
        {
            /*@var IRepositioryCommand*/
            $command->execute();
        }
    }

    /**
     * Logs comandstackactions
     *
     * @param String $message
     */
    public function log(String $message)
    {
        echo $message . PHP_EOL;
    }

    /**
     * Returns data
     */
    public function getData()
    {
        return self;
    }
}

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
 * Class AdobeCQAdapter
 *
 * Implememts an adapter for the concrete CQ implementation
 */
class AdobeCQAdapter implements IContentRepository
{
    /**
     * A way to inject new dependencies
     *
     * @var array
     */
    private $_dependencies;

    /**
     * Saves additional dependencies.
     *
     * @param SplObjectStorage $dependencies
     */
    public function __construct(SplObjectStorage $dependencies = null)
    {
        $this->_dependencies = $dependencies;
    }

    public function _logger()
    {
        return DI::get($this->_dependencies, 'logger');
    }

    /**
     * Posts Filelist to the server
     *
     * @param SplObjectStorage $fileObjectList
     * @return void
     */
    public function postFileList(SplObjectStorage $fileObjectList)
    {
        $this->log(PHP_EOL . __METHOD__);

        $fileObjectList->rewind();

        while($fileObjectList->valid())
        {
            $this->log('=> fileobject '.$fileObjectList->current()->getUuid() . ' retrieved successfully');

            $fileObjectList->next();
        }
    }

    public function get(String $expression)
    {
        echo __METHOD__ . PHP_EOL;
    }

    public function put(SplObjectStorage $fileObjectList)
    {
        echo __METHOD__ . PHP_EOL;
    }

    public function delete(SplObjectStorage $fileObjectList)
    {
        echo __METHOD__ . PHP_EOL;
    }

    public function log(String $message)
    {
        if(!$this->_Logger()) throw new LogicException('no logger available. Pass it as dependecy to the constructor');

        $this->_logger()->log($message);
    }

    public function getData()
    {

    }
}

/**
 * Class ContentRepositoryPostFileCommand
 *
 * Concrete implementation of generic command
 */
class ContentRepositoryPostFileListCommand implements IRepositioryCommand, ICommandDataFacade
{
    private $_id = __METHOD__;

    /**
     * @var FileObjectList
     */
    private $_fileObjectList;

    /**
     * @var IContentRepository
     */
    private $_contentRepositoryAdapter;

    /**
     * @var int
     */
    private $_stackSortOrder = 1;

    /**
     * @param IContentRepository $contentRepository
     * @param SplObjectStorage $fileObjectList
     */
    public function __construct(IContentRepository $contentRepository, SplObjectStorage $fileObjectList)
    {
        $this->_contentRepositoryAdapter = $contentRepository;

        $this->_fileObjectList = $fileObjectList;
    }

    /**
     * @return int
     */
    public function getStackSortOrder()
    {
        return $this->_stackSortOrder;
    }

    /**
     * Adds a command to the commandstack
     *
     * @param IRepositioryCommand $command
     * @return void
     */
    public function addCommandToStack(IRepositioryCommand $command)
    {
        ContentRepositoryCommandStack::getInstance()->addCommandToStack($command);
    }

    /**
     * Executes command
     */
    public function execute()
    {
        $this->_contentRepositoryAdapter->postFileList($this->_fileObjectList);

        $this->log('command: '.$this->_id . ' executed');
    }

    /**
     * @param String $message
     * @return void
     */
    public function log(String $message)
    {
        // TODO: Implement log() method.
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->_id;
    }

    /**
     * @return array
     */
    public function getData()
    {
        return array(array('name'=>$this->_name, 'fileObject'=>$this->_fileObject,'contentRepositoryAdapter'=>$this->_contentRepositoryAdapter));
    }
}

/**
 * Class AdobeCQ5Facade
 *
 * Implements a simplification to handle AdobeCQ client commands
 *
 */
class ContentRepositoryFacade
{
    /**
     * @var IContentRepository
     */
    private $_repository;

    /**
     * @var
     */
    private $_fileObjectList;

    /**
     * @var SplObjectStorage
     */
    private $_dependencies;

    /**
     *
     * @param IContentRepository $repository
     * @param SplObjectStorage $dependencies
     */
    public function __construct(IContentRepository $repository, SplObjectStorage $dependencies = null)
    {
        $this->_repository = $repository;

        $this->_dependencies = $dependencies;
    }

    /**
     * @param SplObjectStorage $fileObjectList
     * @return void
     */
    public function postFileList(SplObjectStorage $fileObjectList)
    {
        $this->_fileObjectList = $fileObjectList;

        $command = new ContentRepositoryPostFileListCommand($this->_repository, $fileObjectList);

        $this->log(PHP_EOL . __METHOD__ . PHP_EOL . '=> send command ' . $command->getId() . ' to commandstack');

        $command->addCommandToStack($command);

        return $this;
    }

    /**
     * Logger.
     *
     * @param String $message
     * @param String $code
     */
    public function log(String $message, String $code = '')
    {
        $logger = DI::get($this->_dependencies, 'logger');

        $logger->log($message);
    }

    /**
     * Executes commandStack
     */
    public function execute()
    {
        ContentRepositoryCommandStack::getInstance()->execute();
    }
}

/**
 * Class DI
 *
 * Resolves SplStprage based dependency objectlists
 *
 */
class DI
{
    /**
     * Returns object by info tag.
     *
     * @param SplObjectStorage $dependency
     * @param String $searchTerm
     * @return null|object
     */
    public static function get(SplObjectStorage $dependency, String $searchTerm)
    {
        $dependency->rewind();

        while($dependency->valid())
        {
            if($dependency->getInfo() == $searchTerm)
            {
                return $dependency->current();
            }

            $dependency->next();
        }

        return null;
    }
}

class Logger
{
    public function log(String $message, String $code='')
    {
        echo $message.' '.$code .PHP_EOL;
    }
}

class Mailer
{
    public function send(String $recipient, String $subject, String $message, $header)
    {

    }
}


/**
 * define a fileList
 */
$fileObject = new FileObject();
$fileObject->setType('node');
$fileObject->setMetadata(array('size'));
$fileObject->setName('a long name');
$fileObject->setPath('/a/long/path/');


$fileObject1 = new FileObject();
$fileObject1->setType('node1');
$fileObject1->setMetadata(array('size1'));
$fileObject1->setName('a long name1');
$fileObject1->setPath('/a/long/path/1');


$fileObject2= new FileObject();
$fileObject2->setType('node2');
$fileObject2->setMetadata(array('size2'));
$fileObject2->setName('a long name2');
$fileObject2->setPath('/a/long/path/2');

//attach files as objects to filelist
$fileObjectList = new SplObjectStorage();
$fileObjectList->attach($fileObject);
$fileObjectList->attach($fileObject1);
$fileObjectList->attach($fileObject2);


/**
 * add additional dependecies
 */
$dependencies = new SplObjectStorage();
$dependencies->attach(new Logger(), 'logger');
$dependencies->attach(new Mailer(), 'mailer');


$adobeCQFacade = new ContentRepositoryFacade(new AdobeCQAdapter($dependencies), $dependencies);

$adobeCQFacade->postFileList($fileObjectList)->execute();





