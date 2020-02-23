<?php

namespace app;

use framework\AbstractAction;

class Bootstrap
{

    /**
     * Запущено ли приложение
     *
     * @var bool
     */
    private $is_init = false;

    /**
     * @return bool
     */
    public function init()
    {
        if ($this->isInit()) {
            return false;
        }
        $constants = parse_ini_file(APP_DIR . '/.env');
        foreach ($constants as $name_const => $val_const) {
            defined($name_const) or define($name_const, $val_const);
        }
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
                $action_full_name = 'app\actions\\' . ucfirst($data['type']) . 'Action';
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