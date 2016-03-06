<?php
/**
 * Use this pattern if you want to centralize your objectcalls to one point, so that you don't care about which specific object
 * can handle your request.
 *
 * The delegator doesn't know anything about the task and who can execute it. He only ask a list with workerclients, who can solve your problem.
 *
 * In this case we have combined the delegatorpattern with a facade, to hide the delegator configuration from the programmer,
 * so that he doesn't have to know anything about the workerclients
 */


/**
 * All clientobjects have to implement
 *
 * Interface IconvertInddToJpger
 */
interface IDelegatorClient
{
    /**
     * Return true if its able to handle the fileobject
     *
     * @param ITask $task
     * @return mixed
     */
    public function isAbleToHandle(ITask $task);

    /**
     * @param SplObjectStorage $splObjectStorage
     * @return mixed
     */
    public function setObjects(SplObjectStorage $splObjectStorage);

    /**
     * @param ITask $task
     * @return void
     */
    public function execute(ITask $task);

    /**
     * Each client has to set a taskresult.
     *
     * @param TaskResult $result
     * @return mixed
     */
    public function setTaskResult(TaskResult $result);

}

/**
 * Interface IDelegator will be implemented on all delegating classes
 */
interface IDelegator
{
    /**
     * All delegators are only able to delegate the task
     *
     * @return mixed
     */
    public function delegate();

    /**
     * All possible solvers have to be attached to an objectlist.
     *
     * @param IDelegatorClient $IDelegatorClient
     * @return mixed
     */
    public function attach(IDelegatorClient $IDelegatorClient);
}

/**
 * Main delegaterv whoch delegates tasks
 *
 * Class RepositoryDelegator
 */
class RepositoryDelegator implements IDelegator
{
    /**
     * @var SplObjectStorage
     */
    protected $_delegatorClientStorage;

    /**
     * @var ITask
     */
    private $_task;

    /**
     * @param ITask $task
     */
    public function __construct(ITask $task)
    {
        $this->_delegatorClientStorage = new SplObjectStorage();

        $this->_task = $task;
    }

    /**
     * @param IDelegatorClient $delegatorClient
     * @return void
     */
    public function attach(IDelegatorClient $delegatorClient)
    {
        $this->_delegatorClientStorage->attach($delegatorClient);
    }

    /**
     * delegates a task to a list of registered clients
     *
     * @return ITask
     */
    public function delegate()
    {
        $this->_delegatorClientStorage->rewind();

        Logger::log(get_class($this) . PHP_EOL . ' => ' . __FUNCTION__ . ' => task: ' . $this->_task->getMethodName() . ' succcessfully recieved. Now delegating'.PHP_EOL);

        while($this->_delegatorClientStorage->valid())
        {
            if ($this->_delegatorClientStorage->current()->isAbleToHandle($this->_task))
            {
                return $this->_delegatorClientStorage->current()->execute($this->_task);
            }

            $this->_delegatorClientStorage->next();
        }

        throw new InvalidArgumentException('no client could solve task:' . $this->_task->getMethodName());
    }
}

/**
 * Class MarsRepositoryconvertInddToJpger
 */
class MarsRepositoryDelegatorClient implements IDelegatorClient
{
    /**
     * @var SplObjectStorage
     */
    protected $_fileObjectList;

    /**
     * @var ITask
     */
    protected $_task;

    /**
     * Converts to jpg. Can implemenzt commandPattern
     */
    public function convertInddToSwf()
    {
        $this->_fileObjectList->rewind();

        $paths = array();

        $errors = array();

        while($this->_fileObjectList->valid())
        {
            try
            {
                $msg = get_class($this) . PHP_EOL . ' => ' . __FUNCTION__ . ' => ' . $this->_fileObjectList->current()->getName() . ' succcessfully converted ';

                $paths[$this->_fileObjectList->current()->getName()] = 'path/to/jpg';

                Logger::log($msg);
            }
            catch (Exception $e)
            {
                $errors[] = $e;

                Logger::log($e->getMessage().$e->getTraceAsString());
            }

            $this->_fileObjectList->next();
        }

        $taskResult = new TaskResult($this->_task);

        $taskResult->setResult(array($paths));

        $taskResult->setMessage($msg);

        $taskResult->setError(null);

        $this->setTaskResult($taskResult);

        return $taskResult;
    }

    /**
     * @param TaskResult $result
     * @return void
     */
    public function setTaskResult(TaskResult $result)
    {
        $this->_task->setTaskResult($result);
    }


    /**
     * Executes delegated method
     *
     * @param ITask $task
     */
    public function execute(ITask $task)
    {
        $this->_task = $task;

        $this->setObjects($task->getObjects());

        return $this->{$task->getMethodName()}();
    }

    /**
     * @param SplObjectStorage $splObjectStorage
     * @return mixed
     */
    public function setObjects(SplObjectStorage $splObjectStorage)
    {
        $this->_fileObjectList = $splObjectStorage;
    }


    /**
     * Return true if its able to handle the fileobject
     *
     * @param ITask $task
     * @return bool
     */
    public function isAbleToHandle(ITask $task)
    {
        if(!method_exists($this, $task->getMethodName())){

            Logger::log(get_class($this) . PHP_EOL . ' => ' . __FUNCTION__ . ' => task request: ' . $task->getMethodName() . ' recieved. but I cant handle'.PHP_EOL);


            return false;
        }

        Logger::log(get_class($this) . PHP_EOL . ' => ' . __FUNCTION__ . ' => task request: ' . $task->getMethodName() . ' recieved. Now telling the delegator, that I am able to handle'.PHP_EOL);

        return true;
    }
}



class AdobeRepositoryDelegatorClient implements IDelegatorClient
{
    /**
     * @var SplObjectStorage
     */
    protected $_fileObjectList;

    /**
     * @var ITask
     */
    protected $_task;

    /**
     * Converts to jpg. Can implemenzt commandPattern
     */
    public function convertInddToJpg()
    {
        $this->_fileObjectList->rewind();

        $paths = array();

        $errors = array();

        while($this->_fileObjectList->valid())
        {
            try
            {
                $msg = get_class($this) . PHP_EOL . ' => ' . __FUNCTION__ . ' => ' . $this->_fileObjectList->current()->getName() . ' succcessfully converted ';

                $paths[$this->_fileObjectList->current()->getName()] = 'path/to/jpg';

                Logger::log($msg);
            }
            catch (Exception $e)
            {
                $errors[] = $e;

                Logger::log($e->getMessage().$e->getTraceAsString());
            }

            $this->_fileObjectList->next();
        }

        $taskResult = new TaskResult($this->_task);

        $taskResult->setTaskResult(array($paths));

        $taskResult->setMessage($msg);

        $taskResult->setError(null);

        $this->setTaskResult($taskResult);

        return $taskResult;
    }

    /**
     * @param TaskResult $result
     * @return void
     */
    public function setTaskResult(TaskResult $result)
    {
        $this->_task->setTaskResult($result);
    }


    /**
     * Executes delegated method
     *
     * @param ITask $task
     */
    public function execute(ITask $task)
    {
        $this->_task = $task;

        $this->setObjects($task->getObjects());

        return $this->{$task->getMethodName()}();
    }

    /**
     * @param SplObjectStorage $splObjectStorage
     * @return mixed
     */
    public function setObjects(SplObjectStorage $splObjectStorage)
    {
        $this->_fileObjectList = $splObjectStorage;
    }


    /**
     * Return true if its able to handle the fileobject
     *
     * @param ITask $task
     * @return bool
     */
    public function isAbleToHandle(ITask $task)
    {
        if(!method_exists($this, $task->getMethodName())){

            Logger::log(get_class($this) . PHP_EOL . ' => ' . __FUNCTION__ . ' => task request: ' . $task->getMethodName() . ' recieved. but I cant handle'.PHP_EOL);


            return false;
        }

        Logger::log(get_class($this) . PHP_EOL . ' => ' . __FUNCTION__ . ' => task request: ' . $task->getMethodName() . ' recieved. Now telling the delegator, that I am able to handle'.PHP_EOL);

        return true;
    }
}

/**
 * Interface ITask
 */
interface ITask
{
    /**
     * @param SplObjectStorage $metadataList
     * @return void
     */
    public function setMetData(SplObjectStorage $metadataList);

    /**
     * Returns an object from the objectList.
     *
     * @param SplObjectStorage $splObjectStorage
     * @return SplObjectStorage
     */
    public function getMetaData(SplObjectStorage $splObjectStorage = null);

    /**
     * Set objectlist to be handled by client
     *
     * @param SplObjectStorage $taskObjects
     * @return void
     */
    public function setObjects(SplObjectStorage $taskObjects);

    /**
     * Returns an object by an object.
     *
     * @return SplObjectStorage
     */
    public function getObjects();

    /**
     * @param $methodName
     * @return void
     */
    public function setMethodName($methodName);

    /**
     * @return String
     */
    public function getMethodName();

    /**
     * @return mixed
     */
    public function getTaskResult();

    /**
     * @param TaskResult $result
     * @return mixed
     */
    public function setTaskResult(TaskResult $result);

    /**
     * @return mixed
     */
    public function setUuid($uuid);

    /**
     * @return mixed
     */
    public function getUuid();
}

/**
 * Class TaskResult
 */
class TaskResult
{
    private $_result;

    private $_message;

    private $_error;

    private $_uuid;

    /**
     * @var ITask
     */
    private $_task;

    public function __construct(ITask $task)
    {
        $this->setUuid($task->getUuid());

        $this->_task = $task;
    }



    /**
     * @param mixed $result
     */
    public function setTaskResult($result)
    {
        $this->_result = $result;
    }

    /**
     * @return mixed
     */
    public function getMessage()
    {
        return $this->_message;
    }

    /**
     * @param mixed $message
     */
    public function setMessage($message)
    {
        $this->_message = $message;
    }

    /**
     * @return mixed
     */
    public function getError()
    {
        return $this->_error;
    }

    /**
     * @param mixed $error
     */
    public function setError($error)
    {
        $this->_error = $error;
    }

    /**
     * @return mixed
     */
    public function getUuid()
    {
        return $this->_uuid;
    }

    /**
     * @param mixed $uuid
     */
    public function setUuid($uuid)
    {
        $this->_uuid = $uuid;
    }


}

interface IFileObject
{
    /**
     * @return String
     */
    public function getType();

    /**
     * @return String
     */
    public function getName();

    /**
     * Sets FileType
     *
     * @param String $name
     * @return void
     */
    public function setName($name);

    /**
     * @param string $type
     * @return void
     */
    public function setType($type);


}




//end of pattern
/***************************************************************************************/
/**
 * Class SplObjectManager
 */
class SplObjectManager
{
    /**
     * @var SplObjectStorage
     */
    private $_sourceObjectStorage;

    /**
     * @param SplObjectStorage $sourceObjectStorage
     */
    public function __construct(SplObjectStorage $sourceObjectStorage)
    {
        $this->_sourceObjectStorage = $sourceObjectStorage;
    }

    /**
     * Searches an object in an ojectstorage.
     *
     * @param SplObjectStorage $targetObjectStorage
     * @return SplObjectStorage
     */
    public function search(SplObjectStorage $targetObjectStorage)
    {
        $searchResult = new SplObjectStorage();

        $this->_sourceObjectStorage->rewind();

        while($this->_sourceObjectStorage->valid())
        {
            foreach($this->_sourceObjectStorage->current() as $sourceObject)
            {
                foreach($sourceObject as $sourceProperty => $sourceValue)
                {
                    foreach(self::toArray($targetObjectStorage) as $targetProperty=>$targetValue)
                    {
                        if($sourceProperty == $targetProperty && $sourceValue == $targetValue)
                        {
                            $searchResult->attach($sourceObject);
                        }
                    }
                }
            }

            $this->_sourceObjectStorage->next();
        }

        return $searchResult;
    }

    /**
     * Reduces an SplObjectStorage to array
     *
     * @param SplObjectStorage $targetObjectStorage
     * @return array
     */
    public static function toArray(SplObjectStorage $targetObjectStorage)
    {
        $array = array();

        $targetObjectStorage->rewind();

        while($targetObjectStorage->valid())
        {
            foreach($targetObjectStorage->current() as $property => $mixed)
            {
                $array[$property] = $mixed;
            }

            $targetObjectStorage->next();
        }

        return $array;
    }
}

/**
 * A class to handle tasks a delegator delegates to its clients
 *
 * Class Task
 */
class Task implements ITask
{
    /**
     * @var SplObjectStorage
     */
    private $_objectStorage;

    /**
     * @var SplObjectStorage
     */
    private $_taskMetadataStorage;

    /**
     * @var String
     */
    private $_methodName;

    /**
     * @var TaskResult
     */
    private $_taskResult;

    /**
     * @var string
     */
    private $_uuid;

    /**
     * Sets a new task.
     *
     * @param SplObjectStorage $objects
     * @param SplObjectStorage $taskMetadata
     * @param $clientServiceMethodName
     */
    public function __construct(SplObjectStorage $objects, SplObjectStorage $taskMetadata, $clientServiceMethodName)
    {
        $this->setObjects($objects);

        $this->setMetData($taskMetadata);

        $this->setMethodName($clientServiceMethodName);

        $this->setUuid(uniqid());
    }

    /**
     * @return mixed
     */
    public function setUuid($uuid)
    {
        $this->_uuid = $uuid;
    }

    /**
     * @return mixed
     */
    public function getUuid()
    {
        return $this->_uuid;
    }

    /**
     * Saves a list with objects the task handles.
     *
     * @param SplObjectStorage $objectStorage
     * @return void
     */
    public function setObjects(SplObjectStorage $objectStorage)
    {
        $this->_objectStorage = $objectStorage;
    }

    /**
     * Returns objectlist by an objectlist.
     *
     * @return SplObjectStorage
     */
    public function getObjects()
    {
        return $this->_objectStorage;
    }

    /**
     * Sets a list with metadataobjects.
     *
     * @param SplObjectStorage $metadataStorage
     * @return void
     */
    public function setMetData(SplObjectStorage $metadataStorage)
    {
        $this->_taskMetadataStorage = $metadataStorage;
    }

    /**
     * Returns an object from the objectList.
     *
     * @param SplObjectStorage $splObjectStorage
     * @return SplObjectStorage
     */
    public function getMetaData(SplObjectStorage $splObjectStorage = null)
    {
        return $this->_taskMetadataStorage;
    }

    /**
     * @param String $methodName
     * @return void
     */
    public function setMethodName($methodName)
    {
        $this->_methodName = $methodName;
    }

    /**
     * @return mixed
     */
    public function getMethodName()
    {
        return $this->_methodName;
    }

    /**
     * @return mixed
     */
    public function getTaskResult()
    {
        return $this->_taskResult;
    }

    /**
     * @param TaskResult $result
     * @return mixed
     */
    public function setTaskResult(TaskResult $result)
    {
        $this->_taskResult = $result;
    }
}


/**
 * FileObject
 */
class FileObject implements IFileObject
{

    /**
     * @var string
     */
    private $_name;

    /**
     * @var string
     */
    private $_type;

    /**
     * @return String
     */
    public function getType()
    {
        return $this->_type;
    }

    /**
     * @return String
     */
    public function getName()
    {
        return $this->_name;
    }

    /**
     * Sets FileType
     *
     * @param String $name
     * @return void
     */
    public function setName($name)
    {
        $this->_name = $name;
    }

    /**
     * @param String $type
     * @return void
     */
    public function setType($type)
    {
        $this->_type = $type;
    }
}

/**
 * Class Logger
 */
class Logger
{
    public static function log($message)
    {
        echo $message . PHP_EOL;
    }
}

/**
 * Imlements facadepattern to simplyfy delegationpattern
 *
 * Class DelegatorFacade
 */
class TaskDelegatorFacade
{

    private $_delegate;

    /**
     * Simplyfies
     *
     * @param $taskName
     * @param $taskDescription
     * @param $clientMethodName
     * @param SplObjectStorage $fileObjectList
     */
    public function __construct($taskName, $taskDescription, $clientMethodName, SplObjectStorage $fileObjectList)
    {

        //create Taskmetdata
        $taskMetadata = new stdClass();
        $taskMetadata->name = $taskName;
        $taskMetadata->description = $taskDescription;

        //add metadata to metadatalist
        $metadataStorage = new SplObjectStorage();
        $metadataStorage->attach($taskMetadata);

        //create the real taskobject
        $task = new Task($fileObjectList, $metadataStorage, $clientMethodName);

        /**
         * Prepare the delegator
         */
        $this->_delegator = new RepositoryDelegator($task);

        //add marsclient to delegatorclientlist
        $this->_delegator->attach(new MarsRepositoryDelegatorClient($fileObjectList));

        //add adobeclient to delegatorclientlist
        $this->_delegator->attach(new AdobeRepositoryDelegatorClient($fileObjectList));
    }

    public function delegate()
    {
        return $this->_delegator->delegate();
    }
}


echo PHP_EOL;

//get an object to decorate
$fileObject = new FileObject();
$fileObject->setName("a_fancy_layout.indd");

//add file to a fileList
$fileObjectList = new SplObjectStorage();
$fileObjectList->attach($fileObject);

//start the task now on a taskFacade
$delegator = new TaskDelegatorFacade('Formatconvertion task','Convert-task for a client', 'convertInddToJpg', $fileObjectList);

$taskResult = $delegator->delegate();

echo PHP_EOL . ' your result: '.PHP_EOL;

var_dump($taskResult);

echo PHP_EOL . ' done...'.PHP_EOL;