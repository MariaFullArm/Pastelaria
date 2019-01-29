<?php
namespace Pastelaria\Services;

interface AuthenticationInterface
{
    public function authenticate($token);
}
