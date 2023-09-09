<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

function j($data)
{
    header('Content-Type: application/json');
    echo json_encode($data);
}

function proper($text = '')
{
    if (strlen($text) > 1) {
        return strtoupper(substr($text, 0, 1)) . strtolower(substr($text, 1, strlen($text) - 1));
    } else {
        return strtoupper($text);
    }
}

function addQuotes($str)
{
    return "'$str'";
}
