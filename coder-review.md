### Миграции

1. В SQL ключевые слова принято писать капсом.
2. is_active можно уточнить длину tinyint(1) чтобы не тратить лишнее место.
3. Чтобы избежать проблем с округлениями при пересчёте цены лучше хранить и считать цену (стоимость, подитог и тп)
в минимальных единицах (центах, копейках). Переделать тип на INTEGER.
4. Убрать индекс с is_active, он будет давать больше проблем при операциях изменения данных, чем пользы при селектах,
так как большинство товаров скорее всего будут активными, и запросы выборки с фильтром is_active=1 будут иметь низкую
селективность.
5. Следует создать индекс по uuid товара, чтобы искать по нему.

### Контроллеры
1. Проект реализует JSON REST API, следовательно, получать и отправлять данные мы будем однообразно. Будет удобно добавить
несколько методов для получения данных запроса и формирования ответа в абстрактный контроллер и отнаследовать все 
контроллеры от него. Так мы избежим дублирования кода и сделаем форматы ответов стандартными. Придётся немного изменить 
контракты ответов, это можно сделать если разработчики клиента ещё не начали его реализацию.
2. Выносим всю бизнес-логику действий контроллеров в отдельные классы. Назовём их UseCase. Так наши контроллеры станут
тонкими, а бизнес-логика перестанет зависеть от http и станет более абстрактной.
3. 'status' => 'success' в ответе указывать бессмысленно, для этого есть http статус коды.
4. Представления не нужно получать как зависимость в конструкторе контроллера, оно всегда конкретное для этого 
контроллера и не должно подменяться во время работы программы. Поэтому можно создавать его через new.
5. AddToCartController и GetCartController добавим в конструктор Customer чтобы мы знали с чьей корзиной работать.

### Представления
1. В представлениях не должно быть никакой бизнес-логики, никаких зависимостей. Данные для представления получать из
юз-кейсов в виде DTO. Так мы можем легко добавить любые данные в представление, используя любые зависимости и выполнив 
любые расчёты.

### Модели
1. Product это модель предметной области, она должна быть в пространстве имён Domain, там же, где остальные модели.
2. У Product цену сделать типа integer, чтобы не было проблем с округлением.
3. CartItem зачем-то свойства класса объявлены публичными, исправить на приватные.
4. CartItem удобно если будет содержать целиком объект Product, а не только его uuid. Это спорный момент: каждый раз
вытаскивать объект Продукта из БД при десериализации корзины из Редиса или вытаскивать по надобности во всяком месте где
он может понадобиться. Возможен и третий вариант: сохранять Продукт целиком вместе с CartItem в Редисе, чтобы у нас был
доступ к корзине и всем данным для её отрисовки без запросов в Mysql.
5. Cart не хорошо когда тип оплаты это просто строка, сделать её перечислением.

### Репозитории
1. ProductRepository sql запросы реализованы без биндинга параметров, это плохая практика и путь к sql-инъекции.
2. Нежелательно обозначать выбираемые поля звёздочкой, лучше перечислить их конкретно: не каждый результат запроса
требует всех полей. На таблице с большим количеством полей это может быть заметно по производительности.

### Работа с Корзиной
1. Логирование не понятное, сделать на базе исключений.
2. CartManager нпо сути является репозиторием, переименовать и перенести.
3. Работает напрямую с session_id(), это не гибко. Сделать ключом id Клиента.
4. Репозиторию не обязательно знать что он работает с Редисом, можно сделать абстрактнее.
5. Connector здесь не место для сериализации/десериализации. Так же не место для указания времени жизни корзины, это
требование бизнеса и оно должно исходить из юз-кейса.

### Остальное
1. Логируются не все ошибки, в ТЗ непонятно нужно ли логировать все ошибки или только Редиса. Сделал логирование всех,
при необходимости можно будет часть отключить по типу.
2. Коннектор к Redis должен уметь проверять возможность записи и доступность сервиса - это не было сделано, я добавил.