<?php


namespace App\Commands;


use App\HttpRequestBuilder\HttpRequest;
use App\Objects\ContextObject\ContextObject;
use App\Operations\ContextOperation;
use DateTime;

class NewsCommand extends AbstractCommand
{

    /**
     * @inheritDoc
     */
    public function execute()
    {
        $user = getUser();
        $context = ContextOperation::me();

        $school_posts = HttpRequest::init('posts/topic/school_:school_id', [
            'url_params' => [
                'school_id' => $context->schoolIds[0]
            ],
            'args' => [
                'take' => '4'
            ],
            'base_url' => 'https://dnevnik.ru/api/',
            'curl_options' => [
                CURLOPT_COOKIEFILE => $user->cookie_file,
            ]
        ])->execute();

        $edu_group_posts = HttpRequest::init('posts/topic/school_:school_id_group_:edu_group_id', [
            'url_params' => [
                'school_id' => $context->schoolIds[0],
                'edu_group_id' => $context->eduGroups[0]->id_str,
            ],
            'args' => [
                'take' => '4'
            ],
            'base_url' => 'https://dnevnik.ru/api/',
            'curl_options' => [
                CURLOPT_COOKIEFILE => $user->cookie_file,
            ]
        ])->execute();

        $dic = getDic();

        $posts = [];
        $posts_api = $school_posts->posts + $edu_group_posts->posts;
        foreach ($posts_api as $post) {
            $posts[$post->createdDate] = $post;
        }
        ksort($posts);
        $posts = array_reverse($posts);

        array_slice($posts, 0, 3, true);

        $result = "{$dic['icons']['success']} Последние 4 новости класса и школы: \n\n";
        foreach ($posts as $post) {
            $text = strip_tags($post->text);
            $date = date('d.m.Y H:i:s', $post->createdDate);
            $result .= "{$dic['icons']['megaphone']} {$post->topicName} \n";
            $result .= "{$text} \n";
            $result .= "{$dic['icons']['user']} {$post->author->name} \n";
            $result .= "{$dic['icons']['calendar']} {$date} \n\n";
        }

        return $this->getResponse()->addMessage([
            'peer_id' => $this->getMessageObject()['peer_id'],
            'message' => $result,
            'random_id' => rand(0, 100000),
        ]);
    }
}