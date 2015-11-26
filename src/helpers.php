<?php

use Libtek\Jmes\Jmes;

if (!function_exists('jmes')) {
    /**
     * @param string $expression
     * @param mixed  $data
     *
     * @return mixed|null
     */
    function jmes($expression, $data)
    {
        return Jmes::search($expression, $data);
    }
}