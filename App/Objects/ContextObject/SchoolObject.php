<?php


namespace App\Objects\ContextObject;


/**
 * Class SchoolObject
 * @package App\Objects\ContextObject
 */
class SchoolObject
{
    /**
     * @var int
     */
    public $id;
    /**
     * @var string
     */
    public $name;
    /**
     * @var string
     */
    public $type;
    /**
     * @var int[]
     */
    public $groupIds;
}