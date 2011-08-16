<?php
function __autoload ($class_name)
{
    include 'clases/' . $class_name . '.php';
}

