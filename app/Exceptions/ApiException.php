<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/5/31
 * Time: 2:16
 */

namespace App\Exceptions;

use Exception;
use RuntimeException;

class ApiException extends RuntimeException
{
    public function __construct(array $apiErrConst, Exception $previous = null)
    {
        $code = $apiErrConst[0];
        $message = $apiErrConst[1];
        parent::__construct($message, $code, $previous);
    }
}