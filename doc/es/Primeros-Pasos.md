Primero descargamos el proyecto

    git clone git://github.com/chentepixtol/Bender2.git
    
Esto creara la carpeta Bender2, ingresamos a ella

    cd Bender2
    
Vamos a crear una base de datos y despues montamos el sql de prueba

    mysqladmin -umyuser -psecreto create bender_test
    mysql -umyuser -psecreto bender_test < dev_dump.sql 

Vamos a instalar las depencias con Composer

    php composer.phar install

Vamos a crear una carpeta para almacenar el cache

    mkdir cache
    
Ahora vamos a editar el usuario y password de la base de datos, editamos el archivo config/settings.yml 
y editamos los siguientes parametros   

    dbname:   bender_test
    user:     myuser
    password: secreto
    host:     localhost
    driver:   pdo_mysql
    
Si ejecutamos el siguiente comando nos va a generar el modelo necesario para una aplicacion

    php bender.php create zf2
    
Lo que sigue seria aprender a generar tus propios modulos, para que genere el codigo como tu lo utilizas ...