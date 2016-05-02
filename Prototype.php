<?php

class ClientConfig
{
    /**
     * @var string
     */
    private $_url;

    /**
     * @var string
     */
    private $_username;

    /**
     * @var string
     */
    private $_password;

    /**
     * @var integer
     */
    private $_port;

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->_url;
    }

    /**
     * @param string $url
     */
    public function setUrl($url)
    {
        $this->_url = $url;
    }

    /**
     * @return string
     */
    public function getUsername()
    {
        return $this->_username;
    }

    /**
     * @param string $username
     */
    public function setUsername($username)
    {
        $this->_username = $username;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->_password;
    }

    /**
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->_password = $password;
    }

    /**
     * @return int
     */
    public function getPort()
    {
        return $this->_port;
    }

    /**
     * @param int $port
     */
    public function setPort($port)
    {
        $this->_port = $port;
    }


}


class Connection
{

}


class Query
{

}

interface IQueue
{
    public function attach(IAsyncTask $task);

    public function detach(IAsyncTask $task);

    public function acknowledge(IAsyncTask $task);
}


Class Queue implements IQueue
{

    /**
     * @var SplObjectStorage
     */
    private $_queue;

    public function __construct()
    {
        $this->_queue = new SplObjectStorage();
    }

    /**
     * @param IAsyncTask $task
     */
    public function attach(IAsyncTask $task)
    {
        return $this->_queue->attach($task);
    }

    public function detach(IAsyncTask $task)
    {
        $this->_queue->detach($task);
    }

    public function acknowledge(IAsyncTask $task)
    {

    }
}

interface IAsyncTask
{

    /**
     * @param SplObjectStorage $taskObjects
     * @return mixed
     */
    public function setObjects(SplObjectStorage $taskObjects);

    /**
     * @return SplObjectStorage
     */
    public function getObjects();

    /**
     * @param String $identifier
     * @return mixed
     */
    public function setQueue(String $identifier);

    /**
     * @return string
     */
    public function getQueue();

    /**
     * @param ITask
     */
    public function __construct(ITask $task);
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
    public function setTaskMetadata(SplObjectStorage $metadataList);

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

    /**
     * @return Closure
     */
    public function setCallBack(Closure $fn);

    /**
     * @return Closure
     */
    public function getCallBack();

}


Class Task implements ITask
{

    /**
     * @var string
     */
    private $_name;

    /**
     * @var string
     */
    private $_uuid;

    /**
     * @var Closure
     */
    private $_callBack;

    /**
     * @var TaskResult
     */
    private $_taskResult;

    /**
     * @var SplObjectStorage
     */
    private $_taskObjects;

    /**
     * @param SplObjectStorage $metadataList
     * @return void
     */
    public function setTaskMetadata(SplObjectStorage $metadataList)
    {
        // TODO: Implement setTaskMetadata() method.
    }

    /**
     * Returns an object from the objectList.
     *
     * @param SplObjectStorage $splObjectStorage
     * @return SplObjectStorage
     */
    public function getMetaData(SplObjectStorage $splObjectStorage = null)
    {
        // TODO: Implement getMetaData() method.
    }

    /**
     * Set objectlist to be handled by client
     *
     * @param SplObjectStorage $taskObjects
     * @return void
     */
    public function setObjects(SplObjectStorage $taskObjects)
    {
        $this->_taskObjects = $taskObjects;
    }

    /**
     * Returns an object by an object.
     *
     * @return SplObjectStorage
     */
    public function getObjects()
    {
        return $this->_taskObjects;
    }

    /**
     * @param $methodName
     * @return void
     */
    public function setMethodName($methodName)
    {
        // TODO: Implement setMethodName() method.
    }

    /**
     * @return String
     */
    public function getMethodName()
    {
        // TODO: Implement getMethodName() method.
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
     * @return Closure
     */
    public function setCallBack(Closure $fn)
    {
        $this->_callBack = $fn;
    }

    /**
     * @return Closure
     */
    public function getCallBack()
    {
        return $this->_callBack;
    }
}

Class AsyncTask implements IAsyncTask
{

    private $_objects;

    private $_queue;

    private $_callBack;

    public function __construct(ITask $task)
    {
        $this->setObjects($task->getObjects());
    }

    /**
     * @param SplObjectStorage $taskObjects
     * @return void
     */
    public function setObjects(SplObjectStorage $taskObjects)
    {
        $this->_objects = $taskObjects;
    }

    /**
     * @return mixed
     */
    public function getObjects()
    {
        return $this->_objects;
    }

    /**
     * @param String $queue
     * @return mixed
     */
    public function setQueue(String $queue)
    {
        $this->_queue = $queue;
    }

    /**
     * @return mixed
     */
    public function getQueue()
    {
        return $this->_queue;
    }

    /**
     * @return Closure
     */
    public function setCallBack(Closure $callBack)
    {
        $this->_callBack = $callBack;
    }

    /**
     * @return Closure
     */
    public function getCallBack()
    {
        return $this->_callBack;
    }
}


interface IUser
{
    public function getName();

    public function setName(String $name);

    public function setUuid(String $uuid);

    public function getUuid();
}

class User implements IUser
{

    /**
     * @var string
     */
    private $_name;

    /**
     * @var string
     */
    private $_uuid;

    /**
     * @param array $userdata
     */
    public function __construct(array $userdata)
    {
        foreach ($userdata as $key => $value) {

            $method = 'set' . ucfirst($key);

            if (method_exists($this, $method)) {

                $this->{$method}($value);
            }
        }
    }

    public function getName()
    {
        return $this->_name;
    }

    public function setName(String $name)
    {
        $this->_name = $name;
    }

    public function setUuid(String $uuid)
    {
        $this->_uuid = $uuid;
    }

    public function getUuid()
    {
        return $this->_uuid;
    }
}

interface IAsset
{

    public function setUuid(String $uuid);

    public function setName(String $name);

    public function getNam();

    public function getUuid();

}

class Asset implements IAsset
{
    private $_uuid;

    private $_name;

    public function setUuid(String $uuid)
    {
        $this->_uuid = $uuid;
    }

    public function setName(String $name)
    {
        $this->_name = $name;
    }

    public function getUuid()
    {
        return $this->_uuid;
    }

    public function getNam()
    {
        return $this->_name;
    }


}


interface IClientPrototype
{
    public function __clone();

    public function setServiceAdminUser(IUser $adminuser);

    public function addUser(IAsyncTask $asyncTask);

    public function auth(IAsyncTask $asyncTask);

    public function setSession(String $session);

    public function getSession();

    public function configure(ClientConfig $config);

    public function connect();

    public function query(IAsyncTask $asyncTask);

    public function write(IAsyncTask $asyncTask);

    public function setQueue(IQueue $queue);
}

interface IMAMClient
{
    public function getProxyAsset(IAsset $asset);

    public function getAsset(IAsset $asset);
}

abstract class ClientPrototyp implements IClientPrototype
{
    public function __clone()
    {
        // TODO: Implement __clone() method.
    }
}


class MAMClientPrototyp extends ClientPrototyp implements IMAMClient
{

    private $_adminUser;

    /**
     * @var ITask
     */
    private $_user;

    /**
     * @var
     */
    private $_session;

    /**
     * @var ClientConfig
     */
    private $_config;

    /**
     * @var IQueue
     */
    private $_taskQueue;

    /**
     * @param IAsyncTask $addUserTask
     * @param ClientConfig $clientConfig
     */
    public function __construct(IAsyncTask $addUserTask, ClientConfig $clientConfig)
    {
        echo PHP_EOL . 'DAM Prototype initiated' . PHP_EOL;

        $this->setQueue(new Queue());

        $this->setServiceAdminUser(new User([$clientConfig->getUsername(), $clientConfig->getPassword()]));

        $this->configure($clientConfig);

        $this->auth($this->_getAuthTask());

        $this->addUser($addUserTask);

        $this->connect();
    }

    /**
     * @return AsyncTask|Task
     */
    protected function _getAuthTask()
    {
        $authTask = new Task();

        $authStorage = new SplObjectStorage();

        $authStorage->attach($this->_config);

        $authTask->setObjects($authStorage);

        $authTask = new AsyncTask($authTask);

        return $authTask;
    }

    public function setServiceAdminUser(IUser $adminuser)
    {
        echo 'successfully configured adminuser: '.$adminuser->getName();

        $this->_adminUser = $adminuser;
    }

    public function addUser(IAsyncTask $addUserTask)
    {
        $promise = $this->_taskQueue->attach($addUserTask);

        return $promise;
    }

    public function auth(IAsyncTask $authUserTask)
    {
        $this->_taskQueue->attach($authUserTask);
    }

    public function configure(ClientConfig $config)
    {
        echo PHP_EOL . ' successfully configured client';

        $this->_config = $config;
    }

    public function connect()
    {
        echo PHP_EOL . ' successfully connected to:' . $this->_config->getUrl() ;
    }

    public function query(IAsyncTask $asyncTask)
    {
        // TODO: Implement query() method.
    }

    public function write(IAsyncTask $asyncTask)
    {
        $this->_taskQueue->attach($asyncTask);
    }

    public function getProxyAsset(IAsset $asset)
    {
        // TODO: Implement getProxyAsset() method.
    }

    public function getAsset(IAsset $asset)
    {
        // TODO: Implement getAsset() method.
    }

    public function setSession(string $session)
    {
        // TODO: Implement setSession() method.
    }

    public function getSession()
    {
        // TODO: Implement getSession() method.
    }

    public function setQueue(IQueue $queue)
    {
        $this->_taskQueue = $queue;
    }
}


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


class PictureParkFacade
{

    /**
     * @var MAMClientPrototyp
     */
    private $_pictureParkClient;


    public function __construct(User $user, ClientConfig $clientConfig)
    {
        $task = new Task();
        $task->setUuid(uniqid());
        $task->setMethodName('User.add');

        //add user to a list
        $userObjects = new SplObjectStorage();
        $userObjects->attach($user);

        //assign userList to task
        $task->setObjects($userObjects);

        //add callback to simlpy print out added user
        $callBack = function(ITask $task) use ($task) {

            $task->getObjects()->rewind();

            while ($task->getObjects()->valid())
            {
                echo PHP_EOL . ' user: '.$task->getObjects()->current()->getUuid() . ' recieved';

                $task->getObjects()->next();
            }
        };

        $task->setCallBack($callBack);

        //make task an async task
        $addUserTask = new AsyncTask($task);

        //get an mam client prototype
        $damPrototype = new MAMClientPrototyp($addUserTask, $clientConfig);

        //clone a picturepark client of a mam client
        $this->_pictureParkClient = clone $damPrototype;
    }


    public function write(IAsyncTask $task)
    {
        $this->_pictureParkClient->write($task);
    }
}

$clientConfig = new ClientConfig(new stdClass());
$clientConfig->setUrl('http://a-fancy.service');
$clientConfig->setPort(80);
$clientConfig->setUsername('admin');
$clientConfig->setPassword(uniqid());


$pictureParkFacade = new PictureParkFacade(new User(['name'=>'firstname','uuid'=>uniqid()]), $clientConfig);


//add an asset to to the picturepark man{
$asset = new Asset();
$asset->setUuid(uniqid());
$asset->setName('a worthfull asset');

//add callback to simlpy print out added user
$callBack = function(IAsset $asset) use ($asset) {
    echo PHP_EOL . ' asset: '.$asset->getName() . ' recieved';
};


//add asset to list
$assetObjects = new SplObjectStorage();
$assetObjects->attach($asset);

//add asset to an async task
$assetTask = new Task();
$assetTask->setUuid(uniqid());
$assetTask->setMethodName('Asset.add');
$assetTask->setObjects($assetObjects);


$assetTask->setCallBack($callBack);

$addAssetTask = new AsyncTask($assetTask);

$pictureParkFacade->write($addAssetTask);

echo (PHP_EOL . 'Book 1 topic: ');