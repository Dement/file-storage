prototype amazonS3.dev
=======================

Тестировалось на php7.2, PostgreSQL 10.5, Redis server 3.0.6

Из консоли запустить скрипт для разворота bash clearCache.sh composer
Описание переменных можно посмотреть тут app/config/parameters.yml.dist

Примеры конфигов nginx amasonS3.dev, amazon.load.ddev

docs.amazonS3.ddev - документация
api.amazonS3.ddev - api
amazons3.ddev - скрипт для загрузки файлов с клиента (сделал пример с отправкой файла по частям)
В папке uploder лежат скрипты для amazons3.ddev

Запросы в api можно делать с auth-token: fake