# Mnemosine

## Instalación y requerimientos
Es necesario instalar:  
[nodejs](https://nodejs.org/es/)
[wkhtmltopdf](https://wkhtmltopdf.org/)

Después de clonar el repositorio, abrir la consola e ir a la carpeta:  
./public/admin  

Desde ella ejecutar el comando:  
`npm install`

Desde la raíz ejecutar el comando:  
`composer install`

Renombrar el archivo .env.example a .env

Desde la raíz ejecutar el comando:  
`php artisan key:generate`

Editar el archivo .env personalizando los datos de conexión a la base de datos y a Amazon Web Services S3

### Almacenamiento de archivos

Se está usando la clase incorporada a Laravel, para que funcione correctamente se debe crear un enlace símbolico con el comando:  
`php artisan storage:link`

Posteriormente, crear una carpeta llamada "fotosThumbnails" dentro de las carpetas:

./storage/app/public/inventario
./storage/app/public/investigacion
./storage/app/public/restauracion

### Permisos de archivos en linux

Asignar todos la propiedad al usuario del servidor web:  
`sudo chown -R www-data:www-data /path/to/your/laravel/root/directory`

Asignar nuestro usuario al grupo del usuario del servidor web:  
`sudo usermod -a -G www-data elusuario`

Cambiar permisos de archivos y carpetas:  
`sudo find /path/to/your/laravel/root/directory -type f -exec chmod 644 {} \;`  
`sudo find /path/to/your/laravel/root/directory -type d -exec chmod 755 {} \;`

## Permisos
Se pueden agregar nuevos tipos de permisos con el comando:  
`php artisan auth:permission [nombre_permiso]`

Por ejemplo:  
`php artisan auth:permission consultas`

Creará los permisos 'ver_consultas', 'agregar_consultas', 'editar_consultas', 'eliminar_consultas'

Para resetear manualmente el cache de los permisos ejecutar:  
`php artisan cache:forget spatie.permission.cache`
