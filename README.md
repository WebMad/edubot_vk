# edubot_vk
Бот для работы с дневник.ру через VK

composer install

"vendor/bin/phinx" migrate -c config/config-phinx.php

"vendor/bin/phinx" rollback -c config/config-phinx.php
