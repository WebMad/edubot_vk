<?php


namespace App\Objects;


/**
 * Class WorkObject
 * @package App\Objects
 */
class WorkObject
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
    public $type;
    /**
     * @var
     */
    public $workType;
    /**
     * @var
     */
    public $markType;
    /**
     * @var
     */
    public $markCount;
    /**
     * @var
     */
    public $lesson;
    /**
     * @var
     */
    public $lesson_str;
    /**
     * @var
     */
    public $displayInJournal;
    /**
     * @var
     */
    public $status;
    /**
     * @var
     */
    public $eduGroup;
    /**
     * @var
     */
    public $eduGroup_str;
    /**
     * @var TaskObject[]
     */
    public $tasks;
    /**
     * @var
     */
    public $text;
    /**
     * @var
     */
    public $periodNumber;
    /**
     * @var
     */
    public $periodType;
    /**
     * @var
     */
    public $subjectId;
    /**
     * @var
     */
    public $isImportant;
    /**
     * @var
     */
    public $targetDate;
    /**
     * @var
     */
    public $sentDate;
    /**
     * @var
     */
    public $createdBy;
    /**
     * @var
     */
    public $files;
    /**
     * @var
     */
    public $oneDriveLinks;
}