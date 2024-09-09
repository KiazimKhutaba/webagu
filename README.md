# WebAGU - Educational Material Management System

**WebAGU** is a web application designed for administering and managing educational materials. It supports adding, editing, and deleting subject-related topics and includes additional features for managing assignments, tests, and student reports.


<img src="https://i.imgur.com/tZruy4H.png" width="30%" alt="WebAgu login page" />
<img src="https://i.imgur.com/di1twjF.png" width="30%" alt="WebAgu lectures dashboard light theme" />
<img src="https://i.imgur.com/L1RtA40.png" width="30%" alt="WebAgu lectures dashboard dark theme" />

## Key Features

- **Subject Materials** — Ability to create and edit educational topics, displaying topic content with sources.
- **Assignments and Tests** — Manage assignments, tests, and their completion reviews.
- **Files and Students** — Upload educational files and view student lists.
- **Forms and Reports** — Add test forms and reports on learning outcomes.
- **Dark and Light Themes** — Supports toggling between light and dark mode for the interface.

## Technologies

- **Frontend**: Vanilla JS & Vue.js.
- **Backend**: Laravel 10 (PHP 10).
- **Database**: MySQL

## Installation and Setup

1. Clone the repository:
    ```bash
    git clone <repo-url>
    ```
2. Navigate to the project folder:
    ```bash
    cd WebAGU
    ```
3. Install dependencies:
    ```bash
    composer install
    ```
4. Configure the `.env` file for database connection.
5. Run container
    ```bash
    ./vendor/bin/sail up # in first console
    ./vendor/bin/sail bash -c "npm install" # install node deps
    ./vendor/bin/sail bash -c "npm run dev" # run Vite dev server
    ```
6. Run migrations to create the database tables:
    ```bash
    ./vendor/bin/sail bash -c "php artisan migrate"
    ```
7. Open app on browser:
    ```bash
    http://localhost
    ```

----------------

# WebAGU - Система управления образовательными материалами

**WebAGU** — это веб-приложение, предназначенное для администрирования и управления учебными материалами. Оно поддерживает функции добавления, редактирования и удаления материалов по предметам, а также включает дополнительные функции для взаимодействия с заданиями, тестами и отчетами студентов.

## Основные возможности

- **Материалы по предметам** — возможность создания и редактирования учебных тем, отображение содержания тем с источниками.
- **Задания и тесты** — управление заданиями, тестами и проверкой их выполнения.
- **Файлы и студенты** — загрузка учебных файлов и просмотр списка студентов.
- **Формы и отчеты** — возможность добавления тестовых форм и отчетов по результатам обучения.
- **Темная и светлая темы** — поддержка переключения между режимами отображения интерфейса.

## Технологии

- **Frontend**: Vanilla JS & Vue.js.
- **Backend**: Laravel 10 (PHP 8).
- **База данных**: MySQL

## Установка и запуск

Конечно, вот перевод на русский:

1. Клонируйте репозиторий:
   ```bash
   git clone <repo-url>
   ```
2. Перейдите в папку проекта:
   ```bash
   cd WebAGU
   ```
3. Установите зависимости:
   ```bash
   composer install
   ```
4. Настройте файл `.env` для подключения к базе данных.
5. Запустите контейнер:
   ```bash
   ./vendor/bin/sail up # в первой консоли
   ./vendor/bin/sail bash -c "npm install" # установите зависимости node
   ./vendor/bin/sail bash -c "npm run dev" # запустите dev-сервер Vite
   ```
6. Запустите миграции для создания таблиц базы данных:
   ```bash
   ./vendor/bin/sail bash -c "php artisan migrate"
   ```
7. Откройте приложение в браузере:
   ```bash
   http://localhost
   ```


