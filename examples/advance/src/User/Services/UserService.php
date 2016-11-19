<?php

namespace AntExample\User\Services;

use AntExample\User\Entities\UserStructure;
use AntExample\User\Repositories\UserData;

class UserService
{
    /**
     * @var UserData
     */
    private $userData;

    /**
     * @var UserStructure
     */
    private $user;

    /**
     * @var string
     */
    private $defaultName;

    /**
     * @var array
     */
    private $errors = [];

    /**
     * @param string $defaultName
     */
    public function __construct($defaultName)
    {
        $this->defaultName = (string)$defaultName;
        $this->userData    = new UserData();
    }

    /**
     * @return bool
     */
    public function auth()
    {
        if (!$user = $this->userData->get()) {
            return false;
        }
        $this->user = $user;
        return true;
    }

    /**
     * @param string $newName
     *
     * @return bool
     */
    public function rewriteName($newName)
    {
        if ($this->user->getName() == $newName || !$newName) {
            return true;
        }
        if (!$this->userData->update($newName)) {
            $this->errors[] = $this->userData->getLastError();
            return false;
        }
        return true;
    }

    /**
     * @param string $name
     *
     * @return bool
     */
    public function createNew(&$name)
    {
        if (!$name) {
            $name = $this->defaultName;
        }
        if (!$user = $this->userData->create(time(), $name)) {
            $this->errors[] = $this->userData->getLastError();
            return false;
        }
        return true;
    }

    /**
     * @return array
     */
    public function getErrors()
    {
        return $this->errors;
    }
}