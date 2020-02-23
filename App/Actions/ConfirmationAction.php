<?php

namespace App\Actions;

use App\AbstractAction;

class ConfirmationAction extends AbstractAction
{

    /**
     * @inheritDoc
     */
    function execute($data)
    {
        if ($data['group_id'] == GROUP_ID) {
            if ($data['secret'] == SECRET_KEY) {
                return CONFIRMATION_STRING;
            }
            return $this->asJson([
                'error' => true,
                'message' => 'Secret key is not valid'
            ]);
        }
        return $this->asJson([
            'error' => true,
            'message' => 'Group id is not valid'
        ]);
    }
}