<?php

namespace App;

use App\Models\Database;

class Bootstrap
{

    /**
     * Запущено ли приложение
     *
     * @var bool
     */
    private $is_init = false;

    /**
     * @var Database
     */
    private $database;

    /**
     * @return bool
     */
    public function init()
    {
        if ($this->isInit()) {
            return false;
        }
        $this->database = new Database();

        $this->is_init = true;
        $this->route();
    }

    /**
     * Роутинг
     */
    private function route()
    {
        $post = file_get_contents("php://input");
        if (!empty($post)) {
            $data = json_decode($post, true);
            if (!empty($data['type'])) {
                $action_full_name = 'App\actions\\' . str_replace('_', '', ucwords($data['type'], '_')) . 'Action';
                $action_file_name = APP_DIR . '/' . str_replace('\\', '/', $action_full_name) . '.php';
                if (file_exists($action_file_name)) {
                    require $action_file_name;
                    if (class_exists($action_full_name)) {
                        if (method_exists($action = new $action_full_name, 'execute')) {
                            /** @var AbstractAction $action */
                            ob_start();
                            echo $action->execute($data);
                            $content = ob_get_contents();
                            ob_end_clean();
                            echo $content;
                            header("Content-type:{$action->getContentType()};charset={$action->getCharset()}");
                            return true;
                        }
                    }
                }
            }
        }
        return false;
    }

    public function isInit()
    {
        return $this->is_init;
    }
}