<?php

namespace AntExample\Application\Services;

use Ant\Application\DI;
use AntExample\User\Services\UserService;

class MainStory
{
    /**
     * @var UserService
     */
    private $userService;

    /**
     * @var array
     */
    private $loggingActivities = [];

    /**
     * @var array
     */
    private $errors = [];

    public function __construct(DI $diContainer)
    {
        $defaultName = $diContainer->getContainer('default-user-name');
        $this->userService = new UserService($defaultName);
    }

    /**
     * @return bool
     */
    public function isUserAuthSuccess()
    {
        $isUserAuthenticated       = $this->userService->auth();
        $this->loggingActivities[] = 'User auth have ' . ($isUserAuthenticated ? 'success' : 'fail');
        return $isUserAuthenticated;
    }

    /**
     * @param string $name
     *
     * @return $this
     */
    public function createUser(&$name)
    {
        if (!$isCreated = $this->userService->createNew($name)) {
            $this->errors = array_merge($this->errors, $this->userService->getErrors());
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
        if (!$isUpdated = $this->userService->rewriteName($name)) {
            $this->errors = array_merge($this->errors, $this->userService->getErrors());
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