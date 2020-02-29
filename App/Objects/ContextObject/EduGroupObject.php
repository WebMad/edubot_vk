<?php

namespace App\Objects\ContextObject;

/**
 * Class EduGroupObject
 * @package App\Objects\ContextObject
 */
class EduGroupObject
{
    /**
     * @var int
     */
    public $id;
    /**
     * @var string
     */
    public $id_str;
    /**
     * @var int[]
     */
    public $parentIds;
    /**
     * @var string[]
     */
    public $parentIds_str;
    /**
     * @var string
     */
    public $type;
    /**
     * @var string
     */
    public $name;
    /**
     * @var string
     */
    public $fullName;
    /**
     * @var int
     */
    public $parallel;
    /**
     * @var int
     */
    public $timetable;
    /**
     * @var string
     */
    public $timetable_str;
    /**
     * @var string
     */
    public $status;
    /**
     * @var int
     */
    public $studyyear;
    /**
     * @var SubjectObject[]
     */
    public $subjects;
    /**
     * @var string
     */
    public $journaltype;
}