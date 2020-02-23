<?php


namespace app\actions;


use framework\AbstractAction;

class MessageNewAction extends AbstractAction
{

    /**
     * @inheritDoc
     */
    function execute($data)
    {
        $this->getVKApiClient()->messages()->send(ACCESS_TOKEN, [
            'user_id' => $data['from_id'],
            'message' => $data['text']
        ]);
    }
}