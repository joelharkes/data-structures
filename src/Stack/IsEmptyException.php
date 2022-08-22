<?php
declare(strict_types=1);

namespace DataStructures\Stack;

class IsEmptyException extends \Exception
{
    public function __construct()
    {
        parent::__construct("Stack is empty");
    }
}
