<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

# 🖥 Backend - Gestión de Empleados (Laravel)

Este es el backend del sistema de gestión de empleados, desarrollado con **Laravel**.  
Incluye **autenticación con JWT**, un **CRUD de empleados** y **Swagger** para documentar la API.

---

## 🚀 **Instalación y Configuración**
Sigue estos pasos para instalar y ejecutar el backend en tu máquina local.

### **1️⃣ Requisitos previos**
Antes de comenzar, asegúrate de tener instalado:
- **PHP 8.1+** 👉 [Descargar PHP](https://www.php.net/downloads)
- **Composer** 👉 [Descargar Composer](https://getcomposer.org/)
- **MySQL 5.7+ o MariaDB** 👉 [Descargar MySQL](https://dev.mysql.com/downloads/)
- **Node.js (Opcional, para ejecutar frontend)** 👉 [Descargar Node.js](https://nodejs.org/)

### **2️⃣ Clonar este repositorio**

3️⃣ Instalar dependencias

composer install

4️⃣ Configurar variables de entorno
Renombra el archivo .env.example a .env y edítalo con los datos de tu base de datos:

cp .env.example .env

Dentro del archivo .env, configura:

APP_NAME=Laravel</br>
APP_ENV=local</br>
APP_KEY=base64:mgBgV5uEJHGkrUg43YcnKTBuJZdvZ8v1ZEGek0bzHCk=</br>
APP_DEBUG=true</br>
APP_TIMEZONE=UTC</br>
APP_URL=http://localhost</br>

APP_LOCALE=en</br>
APP_FALLBACK_LOCALE=en</br>
APP_FAKER_LOCALE=en_US</br>

APP_MAINTENANCE_DRIVER=file</br>
# APP_MAINTENANCE_STORE=database</br>

PHP_CLI_SERVER_WORKERS=4</br>

BCRYPT_ROUNDS=12</br>

LOG_CHANNEL=stack</br>
LOG_STACK=single</br>
LOG_DEPRECATIONS_CHANNEL=null</br>
LOG_LEVEL=debug</br>

DB_CONNECTION=mysql</br>
 DB_HOST=192.168.0.8</br>
 DB_PORT=3306</br>
 DB_DATABASE=talent</br>
 DB_USERNAME=nroot</br>
 DB_PASSWORD=12345678</br>

SESSION_DRIVER=database</br>
SESSION_LIFETIME=120</br>
SESSION_ENCRYPT=false</br>
SESSION_PATH=/</br>
SESSION_DOMAIN=null</br>

BROADCAST_CONNECTION=log</br>
FILESYSTEM_DISK=local</br>
QUEUE_CONNECTION=database</br>

CACHE_STORE=database</br>
CACHE_PREFIX=</br>

MEMCACHED_HOST=192.168.0.8</br>

REDIS_CLIENT=phpredis</br>
REDIS_HOST=192.168.0.8</br>
REDIS_PASSWORD=null</br>
REDIS_PORT=6379</br>

MAIL_MAILER=log</br>
MAIL_SCHEME=null</br>
MAIL_HOST=192.168.0.8</br>
MAIL_PORT=2525</br>
MAIL_USERNAME=null</br>
MAIL_PASSWORD=null</br>
MAIL_FROM_ADDRESS="hello@example.com"</br>
MAIL_FROM_NAME="${APP_NAME}"</br>

AWS_ACCESS_KEY_ID=</br>
AWS_SECRET_ACCESS_KEY=</br>
AWS_DEFAULT_REGION=us-east-</br>1
AWS_BUCKET=</br>
AWS_USE_PATH_STYLE_ENDPOINT=false</br>

VITE_APP_NAME="${APP_NAME}"</br>

JWT_SECRET=dO7LlaTcSr7nW1Y4xBLZNYj2T1wFTSAPJBSgE9QxibTiccBTFKHQQ9VCAyIJ1FRl</br>
AUTH_GUARD=web</br>
AUTH_PASSWORD_BROKER=user </br>
RESEND_API_KEY=re_VTEEr1Qu_JrAvoe3xAfUd2PbxQ1WN4Hwa</br>



MAIL_MAILER=smtp</br>
MAIL_HOST=smtp.gmail.com</br>
MAIL_PORT=587</br>
MAIL_USERNAME=pruebasenviocorreolaraveloscar@gmail.com</br>
MAIL_PASSWORD=jeieujzzyqvquyyj</br>
MAIL_ENCRYPTION=tls</br>
MAIL_FROM_ADDRESS=pruebasenviocorreolaraveloscar@gmail.com</br>
MAIL_FROM_NAME="Testing"</br>


5️⃣ Generar la clave de la aplicación

php artisan key:generate

6️⃣ Configurar JWT

php artisan jwt:secret

7️⃣ Ejecutar migraciones y seeders
Para crear las tablas y datos iniciales en la base de datos, ejecuta:

php artisan migrate --seed

8️⃣ Levantar el servidor

php artisan serve --host=localhost --port=8001

Ejecutar FRONTEND PARA HACER REGISTRO DE USUARIOS NUEVOS Y PODER INTERACTUAR.

Si van a quieren ver Swagger   [http://localhost:8001/](http://localhost:8001/api/documentation/)




