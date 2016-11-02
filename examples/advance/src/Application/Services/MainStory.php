<?php

namespace Application\Services;

use User\Services\UserStory;

class MainStory
{
    /**
     * @var UserStory
     */
    private $userStory;

    /**
     * @var array
     */
    private $loggingActivities = [];

    /**
     * @var array
     */
    private $errors = [];

    public function __construct()
    {
        $this->userStory = new UserStory();
    }

    /**
     * @return bool
     */
    public function isUserAuthSuccess()
    {
        $this->loggingActivities[] = 'User auth have ' . ($this->userStory->auth() ? 'success' : 'fail');
        return $this->userStory->auth();
    }

    /**
     * @param string $name
     *
     * @return $this
     */
    public function createUser($name)
    {
        if (!$isCreated = $this->userStory->createNew($name)) {
            $this->errors = array_merge($this->errors, $this->userStory->getErrors());
        }
        $this->loggingActivities[] = 'Creating of user have ' . ($isCreated ? 'success' : 'fail');
        return $this;
    }

    /**
     * @param string $name
     *
     * @return $this
     */
    public function rewriteUserName($name)
    {
        if (!$isUpdated = $this->userStory->rewriteName($name)) {
            $this->errors = array_merge($this->errors, $this->userStory->getErrors());
        }
        $this->loggingActivities[] = 'User name updating have ' . ($isUpdated ? 'success' : 'fail');
        return $this;
    }

    /**
     * @return string
     */
    public function getError()
    {
        return implode('; ', $this->errors);
    }

    /**
     * @return string
     */
    public function getLog()
    {
        return implode('; ', $this->loggingActivities);
    }
}