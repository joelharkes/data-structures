<?php
declare(strict_types=1);

namespace DataStructures\Stack;

use Exception;

class IsEmptyException extends Exception
{
    public function __construct()
    {
        parent::__construct("Stack is empty");
    }
}
