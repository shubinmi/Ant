<?php

namespace User\Repositories;

use Common\Helpers\Cookies;
use User\Entities\UserStructure;

class UserData
{
    /**
     * @var string
     */
    private $lastError;

    /**
     * @return bool
     */
    public function delete()
    {
        if (!Cookies::delete(UserStructure::STRUCTURE_NAME)) {
            $this->lastError = 'Can\'t clear cookie with key: ' . UserStructure::STRUCTURE_NAME;
        }
        return empty($this->lastError);
    }

    /**
     * @param string $id
     * @param string $name
     *
     * @return UserStructure|null
     */
    public function create($id, $name)
    {
        $user = new UserStructure();
        $user->setId($id)->setName($name);
        if (!Cookies::set(UserStructure::STRUCTURE_NAME, $user->toJson())) {
            $this->lastError = 'Can\'t set cookie with key: ' . UserStructure::STRUCTURE_NAME;
            return null;
        }
        return $user;
    }

    /**
     * @param string $name
     *
     * @return bool
     */
    public function update($name)
    {
        if (!$user = $this->get()) {
            $this->lastError = 'Can\'t get cookie with key: ' . UserStructure::STRUCTURE_NAME;
            return false;
        }
        $user->setName($name);
        if (!Cookies::set(UserStructure::STRUCTURE_NAME, $user->toJson())) {
            $this->lastError = 'Can\'t set cookie with key: ' . UserStructure::STRUCTURE_NAME;
            return false;
        }
        return true;
    }

    /**
     * @return UserStructure|null
     */
    public function get()
    {
        if (!$data = Cookies::get(UserStructure::STRUCTURE_NAME)) {
            return null;
        }
        return new UserStructure($data);
    }

    /**
     * @return string
     */
    public function getLastError()
    {
        return $this->lastError;
    }
}