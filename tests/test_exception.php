<?php

function customExceptionHandle($e)
{
    echo $e->getMessage();
}
set_exception_handler('customExceptionHandle');
throw new Exception('custom exception');