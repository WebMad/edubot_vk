<?php

namespace App\Actions;

use App\Response;

class ConfirmationAction extends AbstractAction
{

    /**
     * @inheritDoc
     */
    function execute($data)
    {
        return new Response(CONFIRMATION_STRING);
    }
}