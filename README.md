# Микро-CRM для торговли

## Описание

Данный проект представляет собой систему управления торговлей с функционалом управления складами, товарами, заказами и движениями остатков.

## Что было реализовано:

- **Набор Rest-методов**:
    - Просмотр списка складов.
    - Просмотр списка товаров с их остатками по складам.
    - Получение списка заказов (с фильтрами и настраиваемой пагинацией).
    - Создание заказа (в заказе может быть несколько позиций с разным количеством).
    - Обновление заказа (данные покупателя и список позиций, но не статус).
    - Завершение заказа.
    - Отмена заказа.
    - Возобновление заказа (перевод из отмены в работу).
    - История движения товаров (любое изменение количества товара на остатке склада).
    - Наполнение готовыми тестовыми данными (справочники товаров, складов и остатков). Наполнение происходить путём вызова консольной команды.
- **Код по принципу MVC**.
- **Валидация входных данных**.
- **Swagger документация API**:
    - Документация для всех API-эндпоинтов с использованием Swagger.
- **Docker контейнеризация**:
    - Использование Docker для развертывания приложения и базы данных (nginx, php-fpm, mysql).

### Структура реализации:

- **SeedTestData.php** — Консольная команда для создания тестовых данных.
- **DTO** — для улучшения структуры кода и упрощения передачи данных.
- **Handler.php** — Внёс изменения для обработки исключений.
- **OrderController** — Контроллер для обработки запросов о заказах.
- **ProductController** — Контроллер для обработки запросов о продуктах.
- **StockMovementController** — Контроллер для обработки запросов о движении запасов.
- **WarehouseController** — Контроллер для обработки запросов о складах.
- **CreateOrderRequest** — Запрос create, валидация данных заказа.
- **UpdateOrderRequest** — Запрос update, валидация данных заказа.
- **Models** — Модели для работы с данными.
- **Service** — Логика работы.
- **Migrations** — Миграции для создания таблиц.
- **api.php** — Определения маршрутов для API.
- **Dockerfile** — Конфигурация для создания контейнера Docker.
- **docker-compose.yml** — Настройки для запуска контейнеров с помощью Docker Compose.
- **.env** — Файл с переменными окружения для настройки конфигураций.
- **README.md** — Описание проекта, инструкция по установке и запуску.


## Эндпоинты


### 1. Получение списка складов

- **GET** `http://localhost/api/v1/warehouses`

  **Ответ**:

- **Статус:** `200 OK`

  ```json
  {
    "current_page": 1,
    "data": [
        {
            "id": 3,
            "name": "Express Warehouse",
            "created_at": null,
            "updated_at": null
        },
        {
            "id": 1,
            "name": "Main Warehouse",
            "created_at": null,
            "updated_at": null
        },
        {
            "id": 2,
            "name": "Secondary Warehouse",
            "created_at": null,
            "updated_at": null
        }
    ],
    "first_page_url": "http://localhost/api/v1/warehouses?page=1",
    "from": 1,
    "last_page": 1,
    "last_page_url": "http://localhost/api/v1/warehouses?page=1",
    "links": [
        {
            "url": null,
            "label": "&laquo; Previous",
            "active": false
        },
        {
            "url": "http://localhost/api/v1/warehouses?page=1",
            "label": "1",
            "active": true
        },
        {
            "url": null,
            "label": "Next &raquo;",
            "active": false
        }
    ],
    "next_page_url": null,
    "path": "http://localhost/api/v1/warehouses",
    "per_page": 15,
    "prev_page_url": null,
    "to": 3,
    "total": 3
  }
  ```

  **Коды состояния**:
    - `200 OK`: Данные списка складов успешно получены.
  
  **А так же**:
    -  Получение списка складов с разбивкой по страницам:
    - **GET** `http://localhost/api/v1/warehouses?per_page=2`

### 2. Получение списка товаров с остатками

- **GET** `http://localhost/api/v1/products`

  **Ответ**:

- **Статус:** `200 OK`
  ```json
  {
    "current_page": 1,
    "data": [
        {
            "id": 10,
            "name": "External HDD",
            "price": 242,
            "created_at": null,
            "updated_at": null,
            "stocks": [
                {
                    "product_id": 10,
                    "warehouse_id": 1,
                    "stock": 41,
                    "created_at": "2024-12-02T06:31:51.000000Z",
                    "updated_at": "2024-12-02T06:31:51.000000Z"
                },
                {
                    "product_id": 10,
                    "warehouse_id": 2,
                    "stock": 58,
                    "created_at": "2024-12-02T06:31:51.000000Z",
                    "updated_at": "2024-12-02T06:31:51.000000Z"
                },
                {
                    "product_id": 10,
                    "warehouse_id": 3,
                    "stock": 79,
                    "created_at": "2024-12-02T06:31:51.000000Z",
                    "updated_at": "2024-12-02T06:31:51.000000Z"
                }
            ]
        },
        {
            "id": 7,
            "name": "Headphones",
            "price": 277,
            "created_at": null,
            "updated_at": null,
            "stocks": [
                {
                    "product_id": 7,
                    "warehouse_id": 1,
                    "stock": 64,
                    "created_at": "2024-12-02T06:31:51.000000Z",
                    "updated_at": "2024-12-02T06:31:51.000000Z"
                },
                {
                    "product_id": 7,
                    "warehouse_id": 2,
                    "stock": 49,
                    "created_at": "2024-12-02T06:31:51.000000Z",
                    "updated_at": "2024-12-02T06:31:51.000000Z"
                },
                {
                    "product_id": 7,
                    "warehouse_id": 3,
                    "stock": 53,
                    "created_at": "2024-12-02T06:31:51.000000Z",
                    "updated_at": "2024-12-02T06:31:51.000000Z"
                }
            ]
        },
        {
            "id": 5,
            "name": "Keyboard",
            "price": 118,
            "created_at": null,
            "updated_at": null,
            "stocks": [
                {
                    "product_id": 5,
                    "warehouse_id": 1,
                    "stock": 66,
                    "created_at": "2024-12-02T06:31:51.000000Z",
                    "updated_at": "2024-12-02T06:31:51.000000Z"
                },
                {
                    "product_id": 5,
                    "warehouse_id": 2,
                    "stock": 95,
                    "created_at": "2024-12-02T06:31:51.000000Z",
                    "updated_at": "2024-12-02T06:31:51.000000Z"
                },
                {
                    "product_id": 5,
                    "warehouse_id": 3,
                    "stock": 52,
                    "created_at": "2024-12-02T06:31:51.000000Z",
                    "updated_at": "2024-12-02T06:31:51.000000Z"
                }
            ]
        },
        {
            "id": 1,
            "name": "Laptop",
            "price": 972,
            "created_at": null,
            "updated_at": null,
            "stocks": [
                {
                    "product_id": 1,
                    "warehouse_id": 1,
                    "stock": 68,
                    "created_at": "2024-12-02T06:31:51.000000Z",
                    "updated_at": "2024-12-02T06:31:51.000000Z"
                },
                {
                    "product_id": 1,
                    "warehouse_id": 2,
                    "stock": 39,
                    "created_at": "2024-12-02T06:31:51.000000Z",
                    "updated_at": "2024-12-02T06:31:51.000000Z"
                },
                {
                    "product_id": 1,
                    "warehouse_id": 3,
                    "stock": 9,
                    "created_at": "2024-12-02T06:31:51.000000Z",
                    "updated_at": "2024-12-02T06:31:51.000000Z"
                }
            ]
        },
        {
            "id": 4,
            "name": "Monitor",
            "price": 439,
            "created_at": null,
            "updated_at": null,
            "stocks": [
                {
                    "product_id": 4,
                    "warehouse_id": 1,
                    "stock": 45,
                    "created_at": "2024-12-02T06:31:51.000000Z",
                    "updated_at": "2024-12-02T06:31:51.000000Z"
                },
                {
                    "product_id": 4,
                    "warehouse_id": 2,
                    "stock": 26,
                    "created_at": "2024-12-02T06:31:51.000000Z",
                    "updated_at": "2024-12-02T06:31:51.000000Z"
                },
                {
                    "product_id": 4,
                    "warehouse_id": 3,
                    "stock": 52,
                    "created_at": "2024-12-02T06:31:51.000000Z",
                    "updated_at": "2024-12-02T06:31:51.000000Z"
                }
            ]
        },
        {
            "id": 6,
            "name": "Mouse",
            "price": 53,
            "created_at": null,
            "updated_at": null,
            "stocks": [
                {
                    "product_id": 6,
                    "warehouse_id": 1,
                    "stock": 62,
                    "created_at": "2024-12-02T06:31:51.000000Z",
                    "updated_at": "2024-12-02T06:31:51.000000Z"
                },
                {
                    "product_id": 6,
                    "warehouse_id": 2,
                    "stock": 85,
                    "created_at": "2024-12-02T06:31:51.000000Z",
                    "updated_at": "2024-12-02T06:31:51.000000Z"
                },
                {
                    "product_id": 6,
                    "warehouse_id": 3,
                    "stock": 67,
                    "created_at": "2024-12-02T06:31:51.000000Z",
                    "updated_at": "2024-12-02T06:31:51.000000Z"
                }
            ]
        },
        {
            "id": 8,
            "name": "Printer",
            "price": 213,
            "created_at": null,
            "updated_at": null,
            "stocks": [
                {
                    "product_id": 8,
                    "warehouse_id": 1,
                    "stock": 91,
                    "created_at": "2024-12-02T06:31:51.000000Z",
                    "updated_at": "2024-12-02T06:31:51.000000Z"
                },
                {
                    "product_id": 8,
                    "warehouse_id": 2,
                    "stock": 46,
                    "created_at": "2024-12-02T06:31:51.000000Z",
                    "updated_at": "2024-12-02T06:31:51.000000Z"
                },
                {
                    "product_id": 8,
                    "warehouse_id": 3,
                    "stock": 74,
                    "created_at": "2024-12-02T06:31:51.000000Z",
                    "updated_at": "2024-12-02T06:31:51.000000Z"
                }
            ]
        },
        {
            "id": 9,
            "name": "Router",
            "price": 88,
            "created_at": null,
            "updated_at": null,
            "stocks": [
                {
                    "product_id": 9,
                    "warehouse_id": 1,
                    "stock": 93,
                    "created_at": "2024-12-02T06:31:51.000000Z",
                    "updated_at": "2024-12-02T06:31:51.000000Z"
                },
                {
                    "product_id": 9,
                    "warehouse_id": 2,
                    "stock": 71,
                    "created_at": "2024-12-02T06:31:51.000000Z",
                    "updated_at": "2024-12-02T06:31:51.000000Z"
                },
                {
                    "product_id": 9,
                    "warehouse_id": 3,
                    "stock": 46,
                    "created_at": "2024-12-02T06:31:51.000000Z",
                    "updated_at": "2024-12-02T06:31:51.000000Z"
                }
            ]
        },
        {
            "id": 2,
            "name": "Smartphone",
            "price": 754,
            "created_at": null,
            "updated_at": null,
            "stocks": [
                {
                    "product_id": 2,
                    "warehouse_id": 1,
                    "stock": 7,
                    "created_at": "2024-12-02T06:31:51.000000Z",
                    "updated_at": "2024-12-02T06:31:51.000000Z"
                },
                {
                    "product_id": 2,
                    "warehouse_id": 2,
                    "stock": 38,
                    "created_at": "2024-12-02T06:31:51.000000Z",
                    "updated_at": "2024-12-02T06:31:51.000000Z"
                },
                {
                    "product_id": 2,
                    "warehouse_id": 3,
                    "stock": 66,
                    "created_at": "2024-12-02T06:31:51.000000Z",
                    "updated_at": "2024-12-02T06:31:51.000000Z"
                }
            ]
        },
        {
            "id": 3,
            "name": "Tablet",
            "price": 321,
            "created_at": null,
            "updated_at": null,
            "stocks": [
                {
                    "product_id": 3,
                    "warehouse_id": 1,
                    "stock": 41,
                    "created_at": "2024-12-02T06:31:51.000000Z",
                    "updated_at": "2024-12-02T06:31:51.000000Z"
                },
                {
                    "product_id": 3,
                    "warehouse_id": 2,
                    "stock": 18,
                    "created_at": "2024-12-02T06:31:51.000000Z",
                    "updated_at": "2024-12-02T06:31:51.000000Z"
                },
                {
                    "product_id": 3,
                    "warehouse_id": 3,
                    "stock": 63,
                    "created_at": "2024-12-02T06:31:51.000000Z",
                    "updated_at": "2024-12-02T06:31:51.000000Z"
                }
            ]
        }
    ],
    "first_page_url": "http://localhost/api/v1/products?page=1",
    "from": 1,
    "last_page": 1,
    "last_page_url": "http://localhost/api/v1/products?page=1",
    "links": [
        {
            "url": null,
            "label": "&laquo; Previous",
            "active": false
        },
        {
            "url": "http://localhost/api/v1/products?page=1",
            "label": "1",
            "active": true
        },
        {
            "url": null,
            "label": "Next &raquo;",
            "active": false
        }
    ],
    "next_page_url": null,
    "path": "http://localhost/api/v1/products",
    "per_page": 15,
    "prev_page_url": null,
    "to": 10,
    "total": 10
  }
  ```

  **Коды состояния**:
    - `200 OK`: Данные списка товаров с остатками успешно получены.

  **А так же**:
    -  Получить товары с разбивкой по страницам:
    - **GET** `http://localhost/api/v1/products?per_page=2`
    -  Получить товары по складам:
    - **GET** `http://localhost/api/v1/products?warehouse_id=1`

### 3. Получение списка заказов с фильтрацией и пагинацией

- **GET** `http://localhost/api/v1/orders?status=active&customer=John&per_page=2`

  **Ответ**:

- **Статус:** `200 OK`
  ```json
  {
    "current_page": 1,
    "data": [
        {
            "id": 1,
            "customer": "John Doe",
            "status": "active",
            "created_at": "2024-03-20T10:00:00Z",
            "completed_at": null,
            "warehouse": {
                "id": 1,
                "name": "Main Warehouse"
            },
            "items": [
                {
                    "product_id": 1,
                    "count": 2,
                    "product": {
                        "name": "Laptop",
                        "price": 1200.00
                    }
                }
            ]
        }
    ],
    "first_page_url": "http://localhost/api/v1/orders?page=1",
    "from": null,
    "last_page": 1,
    "last_page_url": "http://localhost/api/v1/orders?page=1",
    "links": [
        {
            "url": null,
            "label": "&laquo; Previous",
            "active": false
        },
        {
            "url": "http://localhost/api/v1/orders?page=1",
            "label": "1",
            "active": true
        },
        {
            "url": null,
            "label": "Next &raquo;",
            "active": false
        }
    ],
    "next_page_url": null,
    "path": "http://localhost/api/v1/orders",
    "per_page": 2,
    "prev_page_url": null,
    "to": null,
    "total": 0
  }
  ```

  **Коды состояния**:
    - `200 OK`: Данные пользователя успешно получены.


### 4. Создание заказа

- **POST** `http://localhost/api/v1/orders`

  **Запрос**:

  ```json
  {
    "customer": "John Doe",
    "warehouse_id": 1,
    "items": [
        {
            "product_id": 1,
            "count": 2
        },
        {
            "product_id": 2,
            "count": 1
        }
    ]
  }
  ```

  **Ответ**:

- **Статус:** `201 Created`

  ```json
  {
    "customer": "John Doe",
    "warehouse_id": 1,
    "status": "active",
    "id": 2,
    "items": [
        {
            "id": 2,
            "order_id": 2,
            "product_id": 1,
            "count": 2,
            "created_at": null,
            "updated_at": null,
            "product": {
                "id": 1,
                "name": "Laptop",
                "price": 1309,
                "created_at": null,
                "updated_at": null
            }
        },
        {
            "id": 3,
            "order_id": 2,
            "product_id": 2,
            "count": 1,
            "created_at": null,
            "updated_at": null,
            "product": {
                "id": 2,
                "name": "Smartphone",
                "price": 594,
                "created_at": null,
                "updated_at": null
            }
        }
    ],
    "warehouse": {
        "id": 1,
        "name": "Main Warehouse",
        "created_at": null,
        "updated_at": null
    }
  }
  ```

  **Коды состояния**:
    - `201 Created`: Заказ успешно обновлен.
    - `422 Unprocessable Content`: Ошибка валидации данных.

### 5. Обновление заказа

- **PUT** `http://localhost/api/v1/orders/1`

  **Запрос**:

  ```json
  {
    "customer": "John Doe Updated",
    "items": [
        {
            "product_id": 1,
            "count": 1
        },
        {
            "product_id": 3,
            "count": 2
        }
    ]
  }
  ```

  **Ответ**:

- **Статус:** `200 OK`

  ```json
  {
    "id": 2,
    "customer": "John Doe Updated",
    "created_at": "2024-11-29 10:41:44",
    "completed_at": null,
    "warehouse_id": 1,
    "status": "active",
    "items": [
        {
            "id": 4,
            "order_id": 2,
            "product_id": 1,
            "count": 1,
            "created_at": null,
            "updated_at": null,
            "product": {
                "id": 1,
                "name": "Laptop",
                "price": 1309,
                "created_at": null,
                "updated_at": null
            }
        },
        {
            "id": 5,
            "order_id": 2,
            "product_id": 3,
            "count": 2,
            "created_at": null,
            "updated_at": null,
            "product": {
                "id": 3,
                "name": "Tablet",
                "price": 485,
                "created_at": null,
                "updated_at": null
            }
        }
    ],
    "warehouse": {
        "id": 1,
        "name": "Main Warehouse",
        "created_at": null,
        "updated_at": null
    }
  }
  ```

  **Коды состояния**:
  - `200 OK`: Заказ успешно обновлен.
  - `404 Not Found`: Заказ с указанным ID не найден.

### 6. Завершение заказа

- **POST** `http://localhost/api/v1/orders/1/complete`

  **Ответ**:

- **Статус:** `200 OK`

  ```json
  {
    "data": {
        "id": 1,
        "status": "completed",
        "completed_at": "2024-03-20T11:00:00Z"
    }
  }
  ```

  **Коды состояния**:
    - `200 OK`: Заказ успешно завершен.
    - `422 Unprocessable Content`: Ошибка валидации данных.

### 7. Отмена заказа

- **POST** `http://localhost/api/v1/orders/1/cancel`

  **Ответ**:

- **Статус:** `200 OK`

  ```json
  {
    "data": {
        "id": 1,
        "status": "canceled"
    }
  }
  ```

  **Коды состояния**:
    - `200 OK`: Заказ успешно отменен.
    - `422 Unprocessable Content`: Ошибка валидации данных.

### 8. Возобновление заказа

- **POST** `http://localhost/api/v1/orders/1/resume`

  **Ответ**:

- **Статус:** `200 OK`

  ```json
  {
    "data": {
        "id": 1,
        "status": "active"
    }
  }
  ```

  **Коды состояния**:
    - `200 OK`: Заказ успешно возобновлен.
    - `422 Unprocessable Content`: Ошибка валидации данных.

### 9. История движения товаров

- **GET** `http://localhost/api/v1/stock-movements?warehouse_id=1&product_id=1&date_from=2024-03-01&date_to=2024-03-20&per_page=2`

  **Ответ**:

- **Статус:** `200 OK`

  ```json
  {
    "current_page": 1,
    "data": [
        {
            "id": 1,
            "product_id": 1,
            "warehouse_id": 1,
            "quantity": -2,
            "type": "decrease",
            "created_at": "2024-03-20T10:00:00Z",
            "product": {
                "name": "Laptop"
            },
            "warehouse": {
                "name": "Main Warehouse"
            }
        },
        {
            "id": 2,
            "product_id": 1,
            "warehouse_id": 1,
            "quantity": 2,
            "type": "increase",
            "created_at": "2024-03-20T11:00:00Z",
            "product": {
                "name": "Laptop"
            },
            "warehouse": {
                "name": "Main Warehouse"
            }
        }
    ],
    "first_page_url": "http://localhost/api/v1/stock-movements?page=1",
    "from": null,
    "last_page": 1,
    "last_page_url": "http://localhost/api/v1/stock-movements?page=1",
    "links": [
        {
            "url": null,
            "label": "&laquo; Previous",
            "active": false
        },
        {
            "url": "http://localhost/api/v1/stock-movements?page=1",
            "label": "1",
            "active": true
        },
        {
            "url": null,
            "label": "Next &raquo;",
            "active": false
        }
    ],
    "next_page_url": null,
    "path": "http://localhost/api/v1/stock-movements",
    "per_page": 2,
    "prev_page_url": null,
    "to": null,
    "total": 0
  }
  ```

  **Коды состояния**:
    - `200 OK`: История движения товаров успешно получен.
    - `422 Unprocessable Content`: Ошибка валидации данных.

## Запуск приложения

1. Убедитесь, что у вас установлен Docker и Docker Compose.
2. Клонируйте репозиторий:

   ```bash
   git clone git@github.com:Adlerprogr/TestMicroCRM.git
   ```

3. Настройте файл `.env`:

   ```bash
   DB_CONNECTION=mysql
   DB_HOST=db
   DB_PORT=3306
   DB_DATABASE=my_database
   DB_USERNAME=user
   DB_PASSWORD=user
   ```

4. Запустите сервер:

   ```bash
   docker-compose up --build
   ```

5. Перейти в контейнер php-fpm:

   ```bash
   docker compose exec php-fpm bash
   ```

6. Из контейнера php-fpm установите зависимости:

   ```bash
   composer install
   ```

7. Из контейнера php-fpm запустите миграции:

   ```bash
   php artisan migrate
   ```

8. Из контейнера php-fpm запустите команду test-data:

   ```bash
   php artisan seed:test-data
   ```

9. После запуска сервера вы можете работать с API через такие сервисы как Postman.

Автор: [Aldar]
# TestMicroCRM
