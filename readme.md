# Lumen PHP Framework

[![Build Status](https://travis-ci.org/laravel/lumen-framework.svg)](https://travis-ci.org/laravel/lumen-framework)
[![Total Downloads](https://poser.pugx.org/laravel/lumen-framework/d/total.svg)](https://packagist.org/packages/laravel/lumen-framework)
[![Latest Stable Version](https://poser.pugx.org/laravel/lumen-framework/v/stable.svg)](https://packagist.org/packages/laravel/lumen-framework)
[![Latest Unstable Version](https://poser.pugx.org/laravel/lumen-framework/v/unstable.svg)](https://packagist.org/packages/laravel/lumen-framework)
[![License](https://poser.pugx.org/laravel/lumen-framework/license.svg)](https://packagist.org/packages/laravel/lumen-framework)

Laravel Lumen is a stunningly fast PHP micro-framework for building web applications with expressive, elegant syntax. We believe development must be an enjoyable, creative experience to be truly fulfilling. Lumen attempts to take the pain out of development by easing common tasks used in the majority of web projects, such as routing, database abstraction, queueing, and caching.

## Official Documentation

Documentation for the framework can be found on the [Lumen website](http://lumen.laravel.com/docs).
The Lumen framework is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT)

## Instalacion y configuracion XAMPP
descargar desde el siguiente [link](https://www.apachefriends.org/download.html)
iniciar el control panel de xampp y activar el modulo de Apache y MySQL.

## Instalacion Lumen Framework
composer global require "laravel/lumen-installer" 

Al finalizar crear el proyecto con la sentencia composer create-project --prefer-dist laravel/lumen code-promotions o
hacer la clonación en el IDE favorito desde GIT en la ruta [clone github](https://github.com/radamanthiss/code-promotions.git)

# RECOMENDACIONES IMPORTANTES !!

Antes de empezar asegurarse de tener las conexiones habilitadas para poder ejecutar el xampp control y asi obtener las variables de entorno
se puede crear directamente la base de datos en localhost/phpmyadmin o usando los archivos enviados en el proyecto que son los exportados de la base de datos usada durante la realización del proyecto, si se crea una nueva base de datos en el phpmyadmin se deben modificar las variables en el archivo .env para que se puedan ejecutar todas las pruebas de manera exitosa.

Para poder ejecutar la parte front del proyecto inicialmente se debe revisar los flujos, como recomendacion inicial si se requiere hacer las pruebas con un nuevo codigo promocional, ejecutar los diferentes metodos configurados en las rutas y llamar al api rest de cada peticion pasando por crear un nuevo codigo, un nuevo evento, asociar un usuario a un codigo, un viaje a un usuario etc. Importante asignar el codigo al usuario antes de poder ejecutar la aplicación web ya que como no se tiene la información del usuario, para fines practico se creo una funcion que crea usuarios y en la cual se puede asignar nombre, email, state, y payment_method según se requiera, se podria modificar para que se inserte el usuario si no existe al momento de enviar el formulario, pero los campos del usuario en la tabla no permiten nulos y por eso no se podria inicialmente dejar estos campos vacios. 

cualquier duda o pregunta se puede escribir al correo ulkevinb@gmail.com o al skype startek13

## Pasos iniciales para ejecutar el proyecto
Desde una terminal de linea de comando ubicarse sobre la carpeta del proyecto creado con el comando cd C:\Users\Usuario\\{espacio de trabajo}\code-promotions\, luego ejecutar el comando php -S localhost:8000 -t public para ejecutar el proyecto y poder visualizar y hacer los llamados del api desde el navegador o el entorno preferido para envio de peticiones HTTP REST. se verá de la siguiente manera:

![Inicio](https://media.giphy.com/media/4Hb9vRnDgkm2CR51M3/giphy.gif)

> ### Activar Eloquent y Facades

Despues de tener configurada la ruta del proyecto se debe ir al archivo app.php que se encuentra en bootstrap/app.php y descomentar la linea // app->withEloquent, adicionalmente también descomentar la linea //$app->withFacades(); que se encuentra en el mismo archivo

> ### Configurar base de datos

Para poder configurar la conexión a la base de datos modificar el archivo .env, en este archivo añadiremos las variables que necesitamos para que nuestro proyecto haga uso de MySQL y de la api de google maps. Se debe añadir lo siguiente:


![envconfig](https://user-images.githubusercontent.com/22681704/48908313-ea850c00-ee37-11e8-8df9-0c72418af9b1.PNG)

En la variable APP__TIMEZONE, configurar la de nuestra región actual. Después de tener nuestro archivo .env configurado podemos hacer uso de los comandos para crear las tablas en nuestra base de datos, con el siguiente comando php artisan make:migration create_codes_table, al hacer esto el archivo se creará en la ruta database/migrations, donde procederemos a modificar el archivo creado para configurar los campos que necesitamos en nuestra tabla. Se ve como el la siguiente imagen:

![migrations](https://user-images.githubusercontent.com/22681704/48908630-e1486f00-ee38-11e8-9930-bd6bd52d2a3a.PNG)


También podemos establecer las relaciones entre las diferentes tablas añadiendo un par de lineas en nuestro archivo de creacion de tabla por ejemplo para la tabla events volvemos a usar el comando para crearla desde nuestra interfaz de linea de comando, php artisan make:migration create_events_table y luego modificamos el archivo de la siguiente manera.

![relations](https://user-images.githubusercontent.com/22681704/48908838-89f6ce80-ee39-11e8-8830-417ae4ee5cc4.PNG)

Lo que se encuentra en el recuadro rojo es lo que debemos añadir a todas las tablas que necesitemos que estén relacionadas. Una vez tenemos todos nuestros archivos creados, procedemos a hacer uso del comando php artisan migrate. lo que nos creará las tablas en nuestra base de datos llamada promociones, por ultimo podemos comprobar que se hayan creado las tablas ingresando en el navegador la ruta localhost/phpmyadmin como lo vemos a continuación

![Database](https://media.giphy.com/media/7E8pu3FoKqwynvrr9G/giphy.gif)


> ### Configurar libreria de Maps

En la terminal de linea de comandos, procedemos a instalar una libreria que nos ayudará a lo largo del proyecto para poder obtener las coordenadas de determinados puntos, la libreria se llama geocode, y se instala con el siguiente comando composer require "jcf/geocode":"~1.3" luego usamos composer update, y ya tendremos instalada la librería, por ultimo vamos al archivo bootstrap/app.php y en la sección de providers añadimos $app->register(Jcf\Geocode\GeocodeServiceProvider::class); para poder utilizarlo en nuestro proyecto.

![providers](https://user-images.githubusercontent.com/22681704/48909374-181f8480-ee3b-11e8-9397-7491681329fc.PNG)



## Creación archivos necesarios para el proyecto

Una vez hemos configurado las variables y librerias necesarias procedemos a crear los archivos que nos permitiran crear la api restful para poder tener cada una de los metodos necesarios del proyecto, en la carpeta app creamos los modelos que necesitamos para la lógica, que son Code.php, Event.php, Travel.php, User.php estos nos permitiran comunicarnos a las tablas de la base de datos y traer la información que necesiten los controladores y que a su vez se comunican con el modelo.

Modelo Code.php

![code](https://user-images.githubusercontent.com/22681704/48909722-0094cb80-ee3c-11e8-8153-79d66febdc92.PNG)

Modelo Event.php

![event](https://user-images.githubusercontent.com/22681704/48909740-0e4a5100-ee3c-11e8-9063-4ca8bae8c4b8.PNG)


Aquí vemos unos ejemplos de como quedan los modelos y las distintas funciones que usaremos en el proyecto. Una vez creamos todos los modelos, podemos proceder a crear los diferentes Controllers de la aplicación, estos se ubicaran en la ruta app/Http/Controllers. Tendremos CodeController.php, EventsController.php, HomeController.php, TravelsController.php, UsersController.php, cada uno de estos controladores tendra la lógica necesaria para comunicarse con los models, obtener información de las tablas y devolver una respuesta según el tipo de petición ya sea POST, GET, PUT.
Aquí vemos un ejemplo de CodeController.php, explicaremos cada funcion de cada controlador adelante.

![codecontroller](https://user-images.githubusercontent.com/22681704/48910816-ef00f300-ee3e-11e8-9fdf-f7846fe90802.PNG)


> ### Configurar rutas para los metodos

Para cada metodo que vayamos a usar para realizar peticiones, hay que configurar la ruta para poder hacer el llamado, esto se hace en el archivo que se encuentra en routes/web.php, aquí configuramos el tipo de peticion y a que función de los controladores apuntará cada ruta.

![rutas_finales](https://user-images.githubusercontent.com/22681704/48950438-220fb900-ef09-11e8-8ed1-cfcb53bbca98.PNG)



> ## Explicacion funciones y rutas

> ### CodeController.php
Este controlador cuenta con las siguientes funciones

	+ {GET} localhost:8000/code/{id} => Funcion: getCode : Devuelve el codigo según el id que se le pasa en la peticion.
	+ {GET} localhost:8000/code  => Funcion: getallCode : Devuelve todos los codigos que se encuentra en la base de datos.
	+ {POST} localhost:8000/new-code => Funcion: createNewCode : Permite generar nuevos codigos segun los parametros ingresados que son:  

![create-code](https://user-images.githubusercontent.com/22681704/48920055-98a6ab00-ee64-11e8-8048-8900d8b5fd93.PNG)

	+ {GET} localhost:8000/code-actives => Funcion: getCodesActive : Permite obtener los codigos activos en la base de datos.
	+ {POST} localhost:8000/deactive-codes => Funcion: deactiveCodes : Permite cambiar el estado de un codigo de activo a inactivo según los parametros. que se le pasan:

![deactive](https://user-images.githubusercontent.com/22681704/48920158-41eda100-ee65-11e8-95d8-086c2a6e02eb.PNG)


> ### UsersController.php
  Este controlador tiene las siguientes funciones:

	+ {POST} localhost:8000/create-user => Funcion: createNewUser : permite generar un nuevo usuario según los parametros que se ingresan:
![new-user](https://user-images.githubusercontent.com/22681704/48920382-947b8d00-ee66-11e8-8ede-7c08668ab28e.PNG)

	+ {GET} localhost:8000/get-origin/{id} => Funcion: getOrigin : Se usa para obtener la ubicacion actual del usuario.
	+ {POST} localhost:8000/new-origin => Funcion: updateOrigin: Permite actualizar la ubicación del usuario por una nueva ingresada segun los parametros:

![new-origin](https://user-images.githubusercontent.com/22681704/48920504-4dda6280-ee67-11e8-8e1b-a09d470f8ea4.PNG)


> ### EventsController.php
 Tiene las siguientes funciones:
 
	+ {POST} localhost:8000/config-radius => Funcion: updateRadius : Permite configurar el radio en el que los codigos serán validos para ser usados segun los parametros que se envian en el json:
	
![update-raidus](https://user-images.githubusercontent.com/22681704/48920631-f25ca480-ee67-11e8-9d51-7f8bfdc0aff1.PNG)

	+ {POST} localhost:8000/validate-code => Funcion: validateCodeEvent : Valida el codigo y devuelve respuesta segun los parametros ingresados

![validate-code](https://user-images.githubusercontent.com/22681704/48920721-86c70700-ee68-11e8-8478-e57819b815b3.PNG)

	+ {POST} Funcion: validateCode : Es la funcion usada durante el envio de los datos desde la vista del usuario, se encarga de procesar la informacion, y devuelve las variables necesarias a la vista para que se pinte el mapa y los mensajes según cada opcion ingresada.
	

> ### HomeController.php
  Tiene las siguientes funciones:
  
	+ {GET} localhost:8000/home => Funcion: inicio : Devuelve la vista principal de la aplicacion

![home](https://user-images.githubusercontent.com/22681704/48920850-71061180-ee69-11e8-9e8a-ed9109a77b8d.png)

	+ {POST} localhost:8000/principal => Funcion: recibir : Es la vista encargada de recibir por post el formulario de home y hacer validaciones para los diferentes casos como pintar el mapa, devolver mensaje exitoso de validación, devolver mensaje de error etc.
	
![principal](https://user-images.githubusercontent.com/22681704/48920927-ee318680-ee69-11e8-9efd-ce1898fb7712.png)



### Logs de seguimiento

Podemos ver los diferentes logs generados al momento de hacer peticiones a la api, en la ruta storage/logs/lumen-fecha.log, aquí se loguea las respuestas y los errores que puedan suceder al momento de invocar la api.

![log](https://user-images.githubusercontent.com/22681704/48921049-f6d68c80-ee6a-11e8-9a77-02f9b7e5e748.PNG)



