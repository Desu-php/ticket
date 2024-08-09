## Установка

#### 2. Убедитесь, что вы не находитесь внутри docker контейнера, или у вас установлен корректный id_rsa в данном контейнере и выполните команду

    composer require maxdev/hde-tickets

#### 3. Выполните публикацию роут пакета

    php artisan vendor:publish --tag="max-tickets-routes"

#### 4. Подключите роут к проекту в RouteServiceProvider
1. Важно! Авторизация пользователя обязательно для всех роутов кроме webhook_client_route.php
```
$this->routes(function () {
            Route::middleware('api')[max_tickets.php](config%2Fmax_tickets.php)
                ->group(base_path('routes/admin_ticket_route.php'));
             Route::middleware('api')
                ->group(base_path('routes/client_ticket_route.php'));    
            ...
        });
```

#### 5. Выполните публикацию настроек пакета (Если надо что-то переопределить)
    php artisan vendor:publish --tag="max-tickets-config"

#### 6. Выполните миграции

    php artisan migrate

#### 7. Публикация файлов миграции (Если надо)
    php artisan vendor:publish --tag="max-tickets-migrations"

## Настройки файл max_tickets.php

1. Значении в .env
````
HDE_USERNAME=some value
HDE_PASSWORD=some value
HDE_BASE_URL=some value
HDE_CUSTOM_FIELD_PRODUCT_KEY=some value
HDE_CUSTOM_FIELD_PRODUCT_VALUE=some value
HDE_CUSTOM_FIELD_PROJECT_KEY=some value
HDE_CUSTOM_FIELD_PROJECT_VALUE=some value
````

## Модель User

По дэфолту связи к user работают через этот путь 

````App\Models\User````
Если надо переопределить 
```
   [
    'user' => User::class
   ] 
```
## Вебхуки

1. в .env значение для проверки ключа запросов 
  
```
TICKET_WEBHOOK_ACCESS_KEY=some value
 ```
2. Вебхук должен отправлять запрос с header:
````
ticket-access-key:access-key
````


### Создаем правила для работы вебхука


```
   https://www.supportcrew.top/ru/staff/dispatcher/10
```
#### Общие настройки
2. Дополнительные условия: Проект - Равно - ${HDE_CUSTOM_FIELD_PROJECT_VALUE} и Продукт - Равно - ${HDE_CUSTOM_FIELD_PRODUCT_VALUE}
3. Действия: отправить вебхук
    1. POST
    2. JSON
    3. Без авторизации
    4. Заголовок ticket-access-key:${TICKET_WEBHOOK_ACCESS_KEY}

#### Новое сообщение
1. Обязательные условия: Новый ответ в заявке
3. Действия: отправить вебхук
   1. url: ${baseUrl}/webhook/messages

Тело запроса:
```json
{
"ticket_id": "{ticket_id}",
"user_email": "{user_email}",
"message": "{answer_last_without_html}",
"files": "{last_answer_attachments_links}",
"project": "{custom_field_5}",
"product": "{custom_field_2}",
"user_id": "{user_id}",
"last_post_user_id": "{last_post_user_id}",
"status_id": "{status_id}",
"created_at": "{last_post_date}",
"creator_group": "{creator_group_name}",
"id": "{last_post_id}",
"owner_email": "{owner_email}",
"owner_name": "{owner_name}",
"owner_id": "{owner_id}"
}
```

#### Новый тикет
1. Обязательные условия: Новая заявка
3. Действия: отправить вебхук
    1. url: ${baseUrl}/webhook/new/ticket

Тело запроса:
```json
{
  "id": "{ticket_id}",
  "user_email": "{user_email}",
  "user_name": "{user_name}",
  "owner_email": "{owner_email}",
  "owner_name": "{owner_name}",
  "owner_id": "{owner_id}",
  "message": "{answer_last_without_html}",
  "files": "{last_answer_attachments_links}",
  "project": "{custom_field_5}",
  "product": "{custom_field_2}",
  "user_id": "{user_id}",
  "last_post_user_id": "{last_post_user_id}",
  "status_id": "{status_id}",
  "message_created_at": "{last_post_date}",
  "updated_at": "{date_update}",
  "creator_group": "{creator_group_name}",
  "message_id": "{last_post_id}",
  "subject": "{ticket_name}"
}
```

#### Изменение статуса тикета
1. Обязательные условия: Изменения в заявке
3. Действия: отправить вебхук
    1. url: ${baseUrl}/webhook/change/ticket/status

Тело запроса:
```json
{
  "id": "{ticket_id}",
  "project": "{custom_field_5}",
  "product": "{custom_field_2}",
  "status": "{status}"
}

```

#### Изменение исполнителя тикета
1. Обязательные условия: Изменения в заявке
2. Дополнительные условия
   1. Изменение исполнителя - с любой - на любой
3. Действия: отправить вебхук
    1. url: ${baseUrl}/webhook/change/ticket/status

Тело запроса:
```json
{
  "id": "{ticket_id}",
  "owner_email": "{owner_email}",
  "owner_name": "{owner_name}",
  "owner_id": "{owner_id}",
  "project": "{custom_field_5}",
  "product": "{custom_field_2}"
}

```
## Уведомление в телеграм

1. Основное

```
TICKET_NOTIFICATION_QUARTER_HOURLY_TOKEN=value
TICKET_NOTIFICATION_QUARTER_HOURLY_CHAT_ID=value

TICKET_NOTIFICATION_HOURLY_TOKEN=value
TICKET_NOTIFICATION_HOURLY_CHAT_ID=value

TICKET_NOTIFICATION_HALF_HOURLY_TOKEN=value
TICKET_NOTIFICATION_HALF_HOURLY_CHAT_ID=value
```
и команда для работы уведомлений:
```php
namespace Maxdev\Tickets\Enums\TicketNotificationTypeEnum;

case QuarterHourlyAlert = 'QuarterHourlyAlert';
case HalfHourlyAlert = 'HalfHourlyAlert';
case HourlyAlert = 'HourlyAlert';

php artisan tickets:notifications {type}
```

Настройка если последняя активность <= в минутах

```php
TICKET_NOTIFICATION_QUARTER_HOURLY_MINUTES= //15
TICKET_NOTIFICATION_HALF_HOURLY_MINUTES= //30
TICKET_NOTIFICATION_HOURLY_MINUTES= //60
```
## Команда для запуска заблокированных запросов (Если hde заблокировал)

```php
php artisan ticket:failed:requests
```

## Команда для синхронизации с hde
если аргумент не указать, будет использована дата последной активности
```php
php artisan tickets:sync:hde {from_date?} {to_date?}
```

## Настройки безопасной блокировки

```php
HDE_SAFE_LIMIT= //10 Запросов осталось в минуту пока HDE не заблокирует
HDE_BLOCK_DURATION= //5 seconds
```

## Логика валидатора тикетов

По дэфолту проверка идет через ${HDE_CUSTOM_FIELD_PROJECT_VALUE} и ${HDE_CUSTOM_FIELD_PROJECT_VALUE} но вы это можете переопределить

В AppServiceProvider
```php
use Maxdev\Tickets\Contracts\ProductAndProjectRuleContract;

public function register(): void
{
    $this->app->bind(ProductAndProjectRuleContract::class, YourClass::class)
}
```