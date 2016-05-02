<?php
/**
 * This pattern solves the problem to save an objectstate without to show concrtete implementation to the saving layer
 *  a usage could be a siple implementation of a undo function
 */

/**
 * Class Originator
 */
class Originator
{
    /**
     * @var string
     */
    private $state = '';

    /**
     * @return string
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * @param string $state
     */
    public function setState($state)
    {
        $this->state = $state;
    }

    /**
     * @return Memento
     */
    public function createMemento()
    {
        return new Memento($this->state);
    }

    /**
     * @param Memento $memento
     */
    public function setMemento(Memento $memento)
    {
        $this->state = $memento->getState();
    }
}

/**
 * Class Memento
 */
class Memento
{
    /**
     * @var string
     */
    public $state = '';

    /**
     * @param String $state
     */
    public function __construct(String $state)
    {
        $this->state = $state;
    }

    /**
     * @return string
     */
    public function getState()
    {
        return $this->state;
    }
}

/**
 * Class CareTaker
 */
class CareTaker
{
    /**
     * @var Memento[]
     */
    public $mementos = array();

    /**
     * @param Memento $memento
     */
    public function addMemento(Memento $memento)
    {
        array_push($this->mementos, $memento);
    }

    /**
     * @return mixed
     */
    public function getLastMemento()
    {
        $lastResult = array_pop($this->mementos);

        $end = count($this->mementos);

        unset($this->mementos[$end]);

        return $lastResult;
    }
}

$careTaker = new CareTaker();
$originator = new Originator();

$originator->setState('a first state');
$careTaker->addMemento($originator->createMemento());

$originator->setState('a second state');
$careTaker->addMemento($originator->createMemento());

$originator->setState('a third state');


echo PHP_EOL . $originator->getState();
$originator->setMemento($careTaker->getLastMemento());

echo PHP_EOL . $originator->getState();
$originator->setMemento($careTaker->getLastMemento());

echo PHP_EOL . $originator->getState();








