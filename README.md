# apiUserResults
Proyecto Final  PHP API

## Symfony -v
Symfony CLI version v4.12.8 (c) 2017-2020 Symfony SAS
Symfony CLI helps developers manage projects, from local code to remote inf
rastructure


## php --version
PHP 7.2.12 (cli) (built: Nov  8 2018 05:47:36) ( ZTS MSVC15 (Visual C++ 2017) x64 )
Copyright (c) 1997-2018 The PHP Group
Zend Engine v3.2.0, Copyright (c) 1998-2018 Zend Technologies
    with Xdebug v2.6.1, Copyright (c) 2002-2018, by Derick Rethans


## Swagger pruebas
Se monta un cliente swagger para atacar al API: http://localhost:8000/api-docs/index.html#/
 
![](https://raw.githubusercontent.com/fatandazdba/apiUserResults/master/public/img/swagger%20url.PNG)

link de referencia: https://editor.swagger.io/

link de referencia formato json: https://petstore.swagger.io/v2/swagger.json


## Instalación de swagger en otro proyecto SYMFONY 

Para instalar el componente de swagger en otro proyecto Symfony lo que debemos de hacer es:

- **ejecuta** composer require zircote/swagger-php
- **ejecuta** composer global require zircote/swagger-php
- **copiamos** la carpeta llamada api-docs ubicada en la ruta 'apiUserResults/public/' para luego **pegarla** en la carpeta public del proyecto en la que lo vayas a usar.

```
Estos pasos fueron probados en un proyecto Symfony 5.2.6 con PHP 7.4
```
