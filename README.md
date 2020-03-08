# edubot_vk
Бот для работы с дневник.ру через VK

composer install

https://sergeivl.ru/phinx-migration.html - работа с миграциями

### основная информация о миграциях

"vendor/bin/phinx" create MyMigration -c config/config-phinx.php

"vendor/bin/phinx" migrate -c config/config-phinx.php

"vendor/bin/phinx" rollback -c config/config-phinx.php

# Структура приложения
```
EduBot
|- App - основное приложение
   |- API - контроллеры для API (для мини приложения)
   |- Bot - все связанное с чат-ботом
      |- actions - события, создаваемые API vk
      |- commands - классы команд, выполняемых ботом
   |- Exceptions - Исключения
   |- HttpRequestBuilder - конструктор запросов к API
   |- Models - модели для БД
   |- Objects - возвращаемые дневником объуекты (для структурирования)
   |- Operations - операции, связанные с дневник.пу
   |- Presenters - "представители" сущностей БД
|- config
   |- commands.php - список команд для бота с классами, описанием
   |- config.php - определение констант, выгрузка из .env
   |- config-phinx.php - конфигурация phinx
   |- db.php - конфигурация БД
   |- dic.php - словарь
   |- keyboards.php - клавиатуры для бота
|- helpers
   |- default.php - основные хелперы
|- migrations - миграции базы данных
|- public - общедостпная папка, index.php тут
|- .env.example
|- .gitignore
|- composer.json
|- composer.lock
|- README.md
```
