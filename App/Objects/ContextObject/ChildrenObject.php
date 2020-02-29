<?php


namespace App\Objects\ContextObject;


/**
 * Class ChildrenObject
 * @package App\Objects\ContextObject
 */
class ChildrenObject
{
    /**
     * @var int
     */
    public $personId;
    /**
     * @var string
     */
    public $firstName;
    /**
     * @var string
     */
    public $lastName;
    /**
     * @var string
     */
    public $middleName;
    /**
     * @var string
     */
    public $shortName;

    /**
     * @var int[]
     */
    public $schoolIds;
    /**
     * @var int[]
     */
    public $groupIds;
}