<?php


namespace App\Objects\ContextObject;


/**
 * Class ContextObject
 * @package App\Objects
 *
 * Права доступа: CommonInfo, FriendsAndRelatives, EducationalInfo
 *
 */
class ContextObject
{
    /**
     * @var int id пользователя в дневник ру
     */
    public $userId;
    /**
     * @var string url аватарки пользователя в дневник ру
     */
    public $avatarUrl;
    /**
     * @var string[] роли пользователя
     */
    public $roles;

    /**
     * @var ChildrenObject[] дети пользователя
     */
    public $children;

    /**
     * @var SchoolObject[]
     */
    public $schools;

    /**
     * @var EduGroupObject[]
     */
    public $eduGroups;

    /**
     * @var string
     */
    public $splitId;
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