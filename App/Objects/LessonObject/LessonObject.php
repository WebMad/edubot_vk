<?php


namespace App\Objects\LessonObject;


use App\Objects\SubjectObject;
use App\Objects\WorkObject;

/**
 * Class LessonObject
 * @package App\Objects\LessonObject
 */
class LessonObject
{
    /**
     * @var
     */
    public $id;
    /**
     * @var
     */
    public $id_str;
    /**
     * @var
     */
    public $title;
    /**
     * @var
     */
    public $date;
    /**
     * @var
     */
    public $number;
    /**
     * @var SubjectObject
     */
    public $subject;
    /**
     * @var
     */
    public $group;
    /**
     * @var
     */
    public $status;
    /**
     * @var int
     */
    public $resultPlaceId;
    /**
     * @var WorkObject[]
     */
    public $works;
}