<?php

namespace App\Actions;

class ConfirmationAction extends AbstractAction
{

    /**
     * @inheritDoc
     */
    function execute($data)
    {
        return CONFIRMATION_STRING;
    }
}