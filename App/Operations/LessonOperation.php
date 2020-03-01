<?php


namespace App\Operations;


use App\HttpRequestBuilder\HttpRequest;
use App\Objects\LessonObject\LessonObject;
use DateTime;

class LessonOperation
{
    /**
     * Возвращает урок по id
     *
     * @param $lesson_id
     * @return LessonObject
     */
    static public function getLessonById($lesson_id)
    {
        $user = getUser();
        return HttpRequest::init('lessons/:lesson_id', [
            'url_params' => [
                'lesson_id' => $lesson_id
            ],
            'args' => [
                'access_token' => $user->access_token
            ]
        ])->execute();
    }

    /**
     * @param $lessons_ids
     * @return LessonObject[]
     */
    static public function getLessonsByIds($lessons_ids)
    {
        $user = getUser();
        return HttpRequest::init('lessons/many', [
            'args' => [
                'lessons' => $lessons_ids
            ],
            'method' => HttpRequest::POST_METHOD
        ])->execute();
    }

    /**
     * @param int $edu_group_id
     * @param DateTime $date_from
     * @param DateTime $date_to
     * @return LessonObject[]
     */
    static public function getLessonsByDate($edu_group_id, $date_from, $date_to)
    {
        $user = getUser();
        return HttpRequest::init('edu-groups/:edu_group_id/lessons/:from/:to', [
            'url_params' => [
                'edu_group_id' => $edu_group_id,
                'from' => $date_from->format('Y-m-d'),
                'to' => $date_to->format('Y-m-d')
            ],
            'args' => [
                'access_token' => $user->access_token
            ]
        ])->execute();
    }

    /**
     * @param $edu_group_id
     * @param $subject_id
     * @param $date_from
     * @param $date_to
     * @return LessonObject
     */
    static public function getLessonsBySubject($edu_group_id, $subject_id, $date_from, $date_to)
    {
        $user = getUser();
        return HttpRequest::init('edu-groups/:edu_group_id/subjects/:subject_id/lessons/:from/:to', [
            'url_params' => [
                'edu_group_id' => $edu_group_id,
                'subject_id' => $subject_id,
                'from' => $date_from->format('Y-m-d'),
                'to' => $date_to->format('Y-m-d')
            ],
            'args' => [
                'access_token' => $user->access_token
            ]
        ])->execute();
    }
}