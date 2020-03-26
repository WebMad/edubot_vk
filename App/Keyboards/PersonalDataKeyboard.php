<?php


namespace App\Keyboards;


use App\Models\User;
use App\Response;

class PersonalDataKeyboard extends AbstractKeyboard
{

    /**
     * @return mixed
     */

    private $user;

    public function __construct(Response $response, $message_object)
    {
        parent::__construct($response, $message_object);
        $this->user = getUser();
    }

    public function PersonalDataAccessButton()
    {
        $this->user->personal_data_access = User::DATA_ACCESSED;
        $this->user->save();
        $message = 'Вы разрешили доступ к своим персональным данным.';
        return $this->returnMessage($message);
    }

    public function PersonalDataRestrictAskButton()
    {
        $this->user->personal_data_access = User::DATA_RESTRICT_ASK;
        $this->user->save();
        $message = 'Мы спросим вас об этом позже.';
        return $this->returnMessage($message);
    }

    public function PersonalDataRestrictButton()
    {
        $this->user->personal_data_access = User::DATA_RESTRICT;
        $this->user->save();
        $message = 'Вы запретили доступ к вашим персональным данным.';
        return $this->returnMessage($message);
    }

    private function returnMessage($message)
    {

        return $this->getResponse()->addMessage([
            'peer_id' => $this->getMessageObject()['peer_id'],
            'message' => $message,
            'random_id' => rand(0, 100000),
        ]);
    }
}