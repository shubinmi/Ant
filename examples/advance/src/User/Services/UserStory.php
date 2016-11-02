<?php

namespace User\Services;

use User\Entities\UserStructure;
use User\Repositories\UserData;

class UserStory
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
     * @var array
     */
    private $errors = [];

    public function __construct()
    {
        $this->userData = new UserData();
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
    public function createNew($name)
    {
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