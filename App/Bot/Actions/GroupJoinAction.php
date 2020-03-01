<?php

namespace App\Bot\Actions;

class GroupJoinAction extends AbstractAction
{

    /**
     * @inheritDoc
     */
    function execute($data)
    {

//        $this->getVKApiClient()->messages()->;send(ACCESS_TOKEN, [
//            'peer_id' => $data['object']['peer_id'],
//            'message' => 'Команда не найдена',
//            'random_id' => rand(0, 100000)
//        ]);

        return 'ok';
    }
}