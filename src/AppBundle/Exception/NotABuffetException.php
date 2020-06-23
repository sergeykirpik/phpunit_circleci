<?php

namespace AppBundle\Exception;

use Exception;

class NotABuffetException extends \Exception
{
    protected $message = 'Please do not mix carnivorous and non-carnivorous dinosaurs. It will be massacre!';
}
