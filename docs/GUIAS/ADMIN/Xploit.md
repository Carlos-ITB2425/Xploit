# Documentación funcional e instalación del entorno XPLOIT

## Visión general

El entorno XPLOIT está planteado como una plataforma web de entrenamiento en ciberseguridad con una página principal centralizada, tres niveles prácticos y un sistema común de autenticación, puntuación y ranking.[1] La arquitectura visible en el código se apoya en PHP con sesiones para controlar el acceso, PostgreSQL para almacenar usuarios y flags resueltas, y varias interfaces visuales diferenciadas por color y temática según el nivel.[1]

A nivel funcional, la aplicación se divide en dos bloques principales: el **hub principal** (`index.php`) y los **entornos de validación** de cada nivel (`nivel1.php`, `nivel2.php` y `nivel3.php`).[1] El hub hace de panel de acceso, resumen de progreso y ranking global, mientras que cada nivel actúa como zona operativa donde el usuario introduce flags, recibe feedback y suma experiencia si la validación es correcta.[1]

Este documento no solo explica cómo funciona el sistema, sino también cómo debería desplegarse o recrearse en un servidor nuevo manteniendo la misma lógica de usuarios, niveles, validación y conexión con los laboratorios externos.[1]

## Objetivo del despliegue

Para recrear el servicio hay que entender que realmente existen dos capas.[1] La primera es la **plataforma web principal**, donde viven la home, el login, el registro, la validación de flags y el ranking; la segunda son los **laboratorios o entornos objetivo**, que están fuera de la lógica principal pero conectados visualmente desde cada nivel mediante URLs web o acceso SSH.[1]

Por tanto, una instalación completa no consiste solo en copiar archivos PHP.[1] También hay que levantar la base de datos, configurar el servidor web, verificar las sesiones, asegurar la conexión con PostgreSQL y dejar accesibles los laboratorios de Nivel 1, Nivel 2 y Nivel 3 en sus puertos correspondientes.[1]

## Estructura general del servicio

La estructura lógica del proyecto puede resumirse así:[1]

- `index.php`: hub principal, navegación, modales de login/registro, tarjetas de niveles y ranking.[1]
- `auth.php`: punto central de autenticación para login y registro mediante peticiones POST y respuesta JSON.[1]
- `db.php`: conexión PDO con PostgreSQL.[1]
- `logout.php`: destrucción de sesión y redirección a la home.[1]
- `nivel1.php`: validador del laboratorio Apache.[1]
- `nivel2.php`: validador del laboratorio MySQL/SQLi.[1]
- `nivel3.php`: validador del laboratorio Linux/SSH.[1]

Desde un punto de vista de recreación, esto significa que el servicio principal puede desplegarse como una aplicación PHP bastante clásica en `/var/www/html`, siempre que el servidor tenga acceso a PostgreSQL y que los laboratorios externos estén accesibles desde donde se ejecuta el sistema.[1]

## Requisitos para recrearlo

Para montar el servicio en otro servidor, el mínimo razonable sería este conjunto de componentes:[1]

- Linux como sistema base, preferiblemente Ubuntu o Debian para simplificar el stack LAMP/LEMP.[1]
- Apache o Nginx con soporte para PHP, aunque por la estructura de archivos el despliegue natural parece orientado a Apache/PHP clásico.[1]
- PHP con soporte para sesiones, PDO y el driver `pdo_pgsql`, porque la aplicación trabaja con PostgreSQL mediante PDO.[1]
- PostgreSQL con una base de datos llamada `xploitdb` o equivalente.[1]
- Acceso de red a los laboratorios externos: puertos 8081, 8082 y 8083 para Nivel 1; puerto 6969 para Nivel 2; y acceso SSH al puerto 2222 para Nivel 3.[1]

## Instalación base recomendada

Si se quisiera recrear el servicio desde cero en un Ubuntu limpio, una base razonable sería instalar Apache, PHP y PostgreSQL.[1] El código usa sesiones, `password_hash`, `password_verify` y PDO con PostgreSQL, así que esos módulos son imprescindibles.[1]

Ejemplo mínimo de instalación del stack:

```bash
sudo apt update
sudo apt install -y apache2 php libapache2-mod-php php-pgsql postgresql
```

Ese paso no aparece literalmente en tu código, pero es coherente con la forma en que el proyecto está implementado: PHP servido desde web, conexión PDO a PostgreSQL y ejecución directa de archivos `.php` como `index.php`, `auth.php` y los niveles.[1]

## Preparación de la base de datos

La conexión de la aplicación está definida en `db.php` con host `127.0.0.1`, puerto `5432`, base `xploitdb` y usuario `postgres`.[1] Por tanto, al recrear el servicio hay que levantar esa base o adaptar los parámetros de conexión a los del nuevo entorno.[1]

El documento de código deja clara la existencia de dos tablas funcionales: `usuarios` y `flags_resueltas`.[1] `usuarios` almacena identidad, credenciales hash y puntuación; `flags_resueltas` registra qué flags ha reclamado ya cada usuario para evitar duplicados y poder pintar el progreso en la home.[1]

Un esquema mínimo para recrear el servicio podría ser este:

```sql
CREATE DATABASE xploitdb;
\c xploitdb;

CREATE TABLE usuarios (
    id SERIAL PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    apellido VARCHAR(100) NOT NULL,
    fechanacimiento DATE NOT NULL,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(150) NOT NULL UNIQUE,
    passwordhash VARCHAR(255) NOT NULL,
    puntos INTEGER NOT NULL DEFAULT 0
);

CREATE TABLE flags_resueltas (
    id SERIAL PRIMARY KEY,
    user_id INTEGER NOT NULL REFERENCES usuarios(id) ON DELETE CASCADE,
    flag_id VARCHAR(50) NOT NULL,
    UNIQUE (user_id, flag_id)
);
```

Ese SQL es una recreación práctica basada en cómo el código consulta e inserta datos, ya que en `auth.php`, `index.php` y los niveles se observa el uso directo de `usuarios` y `flags_resueltas` con esos campos funcionales.[1]

## Configuración de conexión

Una vez creada la base de datos, hay que ajustar `db.php` para que el servicio pueda conectarse correctamente.[1] En el código que has pasado, la conexión está embebida directamente en el archivo, con PDO, modo excepción y `FETCH_ASSOC` como comportamiento por defecto.[1]

Ejemplo mínimo de configuración equivalente:

```php
<?php
$host = '127.0.0.1';
$port = '5432';
$db   = 'xploitdb';
$user = 'postgres';
$pass = 'TU_PASSWORD';

$dsn = "pgsql:host=$host;port=$port;dbname=$db";
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

$pdo = new PDO($dsn, $user, $pass, $options);
```

Para una memoria o despliegue serio conviene explicar que esta configuración debería migrarse a variables de entorno o a un archivo no versionado, porque en el estado actual el código deja visibles las credenciales de conexión dentro del propio proyecto.[1]

## Despliegue de la aplicación web

La parte principal del servicio debería copiarse al directorio público del servidor web, por ejemplo `/var/www/html/xploit` o directamente `/var/www/html`.[1] Después hay que garantizar permisos correctos de lectura para Apache y confirmar que PHP interpreta los archivos del proyecto.[1]

Un despliegue sencillo podría seguir esta lógica:

```bash
sudo mkdir -p /var/www/html/xploit
sudo cp *.php /var/www/html/xploit/
sudo chown -R www-data:www-data /var/www/html/xploit
```

Con eso la aplicación quedaría accesible desde el navegador, siempre que el virtual host apunte a esa ruta y que el servicio Apache esté levantado.[1] Si se quiere mantener una instalación ordenada, lo ideal sería usar un virtual host propio y no mezclar este proyecto con otros servicios del mismo servidor.[1]

## Página principal

La web principal se construye en `index.php` y cumple varias funciones a la vez: presentación del proyecto, entrada al sistema, acceso a los niveles y visualización del ranking global.[1] Visualmente usa una estética “terminal / sci-fi” con fondo oscuro, tipografía técnica, colores neón y una estructura por tarjetas, lo que da una sensación de panel de operaciones o centro de mando.[1]

En la parte superior aparece una barra de navegación que cambia según el estado de sesión del usuario.[1] Si no hay sesión iniciada, muestra los botones de login y registro mediante ventanas modales; si el usuario ya está autenticado, la navegación enseña su nombre de usuario y un botón de desconexión (`logout.php`).[1]

En el centro de la home aparece un bloque hero con el nombre XPLOIT y el subtítulo de “sistema de entrenamiento en ciberseguridad”.[1] Debajo se muestran tres tarjetas, una por nivel, con nombre del reto, una breve descripción y un botón para iniciar misión; además, cuando el usuario está autenticado, cada tarjeta puede mostrar internamente el estado de las flags de ese nivel, de forma que el hub también funciona como panel de progreso.[1]

## Cómo se controla el acceso

El acceso a los niveles no es libre: la aplicación comprueba primero si existe una sesión válida de usuario.[1] En la home esto se hace en cliente con la variable `isLoggedIn` y la función `checkAuthAndGo(url)`, que redirige al nivel si hay sesión o abre el modal de login si el usuario aún no se ha identificado.[1]

Ese primer control es visual y de experiencia de usuario, pero no es la protección real.[1] La protección definitiva está en el backend, porque `nivel1.php`, `nivel2.php` y `nivel3.php` arrancan con `session_start()` y verifican si existe `$_SESSION['user_id']`; si no existe, redirigen directamente a `index.php`.[1]

Eso significa que ningún usuario puede validar flags, sumar puntos o abrir el flujo normal del nivel sin haberse autenticado antes.[1] En otras palabras, la plataforma combina una validación cómoda en frontend con una validación obligatoria en servidor para impedir accesos directos por URL sin sesión.[1]

## Registro y login

El sistema de autenticación se concentra en `auth.php`, que actúa como punto central para registro y login y responde en formato JSON.[1] La home invoca este archivo mediante `fetch()` desde JavaScript cuando el usuario envía uno de los formularios modales.[1]

### Registro

En el modal de registro se piden nombre, apellido, fecha de nacimiento, username, email y contraseña.[1] Antes de enviar los datos, el frontend obliga a rellenar correctamente día, mes y año de nacimiento y marca en rojo los selectores si falta alguna parte de la fecha.[1]

Cuando el formulario llega a `auth.php`, el backend recoge los datos, genera un hash seguro de la contraseña con `password_hash(..., PASSWORD_BCRYPT)` e inserta el nuevo usuario en la tabla `usuarios` con los puntos iniciales a 0.[1] De esta forma, la contraseña nunca se guarda en texto plano y cada nuevo registro entra limpio en el sistema de ranking.[1]

### Login

En el login, el usuario puede identificarse tanto con `username` como con `email`, ya que la consulta busca por ambos campos.[1] Si encuentra un usuario, `auth.php` compara la contraseña introducida con el `passwordhash` almacenado usando `password_verify()`.[1]

Si la validación es correcta, el sistema guarda en sesión `$_SESSION['user_id']` y `$_SESSION['username']`.[1] A partir de ese momento, toda la aplicación reconoce al usuario como autenticado y desbloquea la navegación a los niveles, la personalización visual del hub y la capacidad de validar flags.[1]

### Logout

El cierre de sesión es deliberadamente simple.[1] `logout.php` destruye la sesión actual con `session_destroy()` y redirige de nuevo al hub principal, dejando al usuario fuera del área validada.[1]

## Modelo interno de usuarios, flags y puntos

La lógica del sistema gira sobre dos tablas que aparecen claramente reflejadas por el código: `usuarios` y `flags_resueltas`.[1] `usuarios` almacena la identidad del jugador, su hash de contraseña y su puntuación acumulada; `flags_resueltas` guarda qué flag concreta ha resuelto cada usuario para evitar puntuaciones duplicadas.[1]

La consecuencia práctica es importante: el valor total de puntos del usuario no se recalcula en tiempo real a partir de todas sus flags, sino que se va incrementando cuando una flag se valida por primera vez.[1] La tabla `flags_resueltas` actúa como registro de integridad y como fuente para pintar el progreso visual dentro del hub.[1]

## Sistema de niveles

El proyecto tiene tres entornos principales, cada uno con su propia identidad visual y con su propia lista de flags internas.[1] Aunque el diseño gráfico cambia entre niveles, la lógica de validación es prácticamente la misma en los tres archivos, lo que da coherencia al sistema y facilita su mantenimiento.[1]

| Nivel | Archivo | Tema visual | Tipo de reto | Acceso técnico |
|---|---|---|---|---|
| 1 | `nivel1.php` | Verde neón | Apache / inspección web | URLs independientes en puertos 8081, 8082 y 8083.[1] |
| 2 | `nivel2.php` | Naranja neón | MySQL / SQLi | Entorno web en puerto 6969.[1] |
| 3 | `nivel3.php` | Morado neón | Linux / contenedor | Acceso SSH al host `172.31.70.203` por el puerto `2222`.[1] |

### Cómo recrear los accesos de los niveles

Para que la plataforma funcione igual que en tu entorno original, no basta con subir los validadores PHP.[1] También hay que reproducir o sustituir los accesos externos que cada nivel anuncia en sus modales de conexión.[1]

A nivel práctico, habría que preparar lo siguiente:

- Nivel 1: tres objetivos HTTP accesibles en los puertos `8081`, `8082` y `8083`, ya que `nivel1.php` enlaza directamente a tres URLs distintas.[1]
- Nivel 2: un entorno accesible por navegador en el puerto `6969`, porque `nivel2.php` construye dinámicamente la URL final usando el hostname actual y ese puerto fijo.[1]
- Nivel 3: un servicio SSH escuchando en el puerto `2222`, con un usuario válido para que el alumno pueda entrar al contenedor o laboratorio Linux.[1]

Desde una perspectiva de recreación, eso implica que los laboratorios pueden vivir en la misma máquina o en máquinas separadas.[1] Lo importante es que la web principal apunte a rutas reales y accesibles, ya que la plataforma no ejecuta los retos dentro de sí misma, sino que funciona como panel de acceso y verificación.[1]

## Nivel 1: Apache

`nivel1.php` está orientado a análisis de contenido web, rutas y ficheros en objetivos expuestos por HTTP.[1] Visualmente emplea color verde lima, cajas de validación con borde lateral y una zona de pistas en acordeón, manteniendo la misma estética de panel técnico que el resto de la plataforma.[1]

Internamente define tres flags del nivel con sus puntuaciones asociadas: 100 XP, 350 XP y 500 XP.[1] La interfaz incluye un gran botón de “iniciar misión” que abre un modal con tres URLs distintas, lo que refuerza la idea de que este nivel se distribuye en varios objetivos separados, uno por cada parte del reto.[1]

Desde la perspectiva funcional, el usuario accede a un entorno Apache externo, obtiene la flag y luego vuelve a la pantalla de validación para introducirla manualmente.[1] Ese diseño convierte `nivel1.php` en una especie de consola de verificación más que en el propio laboratorio, y eso es coherente con una arquitectura de prácticas desacopladas.[1]

## Nivel 2: MySQL

`nivel2.php` mantiene la misma base estructural, pero adapta tanto los colores como la narrativa visual al escenario de inyección SQL.[1] El tono naranja, las pistas específicas y el texto del botón principal presentan el nivel como un grid de ataque a base de bypass y extracción de datos.[1]

Internamente, este nivel define tres flags con valores de 100 XP, 250 XP y 500 XP.[1] Las pistas ayudan a entender el enfoque técnico esperado, ya que se mencionan explícitamente conceptos como `admin`, comentarios SQL con `--`, `UNION SELECT`, la tabla `archivosconfidenciales` y la tabla `xploitvaulttopsecret`, lo que revela que el entorno está pensado como un laboratorio guiado de explotación SQL.[1]

A nivel de conexión, el modal no usa una IP fija codificada, sino que construye la URL final con el protocolo actual y el hostname del navegador, forzando el puerto `6969`.[1] Eso hace que el despliegue sea algo más flexible, porque el mismo código puede abrir el laboratorio en distintos hosts sin tocar la plantilla mientras el entorno SQL siga escuchando en ese puerto.[1]

## Nivel 3: Linux

`nivel3.php` corresponde al laboratorio de exploración Linux y búsqueda avanzada de ficheros en contenedor.[1] Su identidad visual usa neón morado y una presentación más orientada a terminal, incluyendo una ventana modal que enseña directamente el comando SSH y la contraseña de acceso al entorno.[1]

Este nivel maneja cuatro flags en vez de tres, con recompensas de 150 XP, 300 XP, 600 XP y 1000 XP.[1] Las pistas mencionan directamente acciones como localizar un archivo `hola`, buscar una cadena junto a `millionth` en `data.txt`, localizar ficheros por owner y revisar rutas menos evidentes, lo que lo posiciona como el nivel más avanzado y más orientado a investigación en sistema de archivos.[1]

Desde el punto de vista operativo, el usuario no entra a un entorno web sino a un contenedor por SSH mediante el comando `ssh xploit1@172.31.70.203 -p 2222`, con una contraseña mostrada en la misma interfaz.[1] La plataforma no ejecuta la práctica por él; solo suministra el acceso, registra el resultado y recompensa la validación si la flag introducida coincide con la definida internamente.[1]

## Validación de flags

La validación funciona igual en todos los niveles y es uno de los puntos clave del sistema.[1] Cada archivo define un array de `flagsreales` donde cada identificador contiene al menos tres piezas: la cadena válida, los puntos asignados y un título descriptivo.[1]

Cuando el usuario envía el formulario por POST, el nivel recorre todas las flags configuradas y comprueba si se ha introducido algún valor en cada input correspondiente.[1] Si una caja se ha dejado vacía, la lógica simplemente la omite; si se ha rellenado, compara el valor introducido con la cadena real almacenada en el backend.[1]

El comportamiento final puede resumirse así:[1]

- Si la flag no coincide, el estado visual pasa a error y el usuario recibe el mensaje “FLAG INCORRECTA”.[1]
- Si la flag coincide pero ya estaba resuelta antes por ese mismo usuario, la validación responde con “FLAG YA RECLAMADA - NO SUMA XP”.[1]
- Si la flag coincide y no constaba en `flags_resueltas`, el sistema suma los puntos y registra la resolución como nueva.[1]
- Si ocurre un fallo durante la operación de base de datos, el sistema hace rollback y muestra un error de sincronización.[1]

Ese diseño evita fraudes simples y, sobre todo, evita que un usuario gane puntos infinitos repitiendo la misma flag.[1] También mantiene una traza clara de qué retos concretos ha superado cada cuenta.[1]

## Cómo se otorgan los puntos

La suma de puntos no se hace de forma aislada, sino dentro de una transacción de base de datos.[1] Primero se comprueba si la flag ya existe para ese usuario en `flags_resueltas`; si no existe, se abre una transacción, se actualizan los puntos en `usuarios` y luego se inserta la relación usuario-flag en `flags_resueltas`.[1]

Este orden tiene valor administrativo porque mantiene consistencia entre el marcador y el historial de flags.[1] Si el `UPDATE` de puntos funciona pero el `INSERT` fallase, el rollback evita que el usuario se quede con XP sumada sin constancia de haber resuelto la flag.[1]

Las recompensas que se observan en el código son las siguientes:[1]

| Nivel | Flags y puntuación |
|---|---|
| Nivel 1 | 100 XP, 350 XP y 500 XP.[1] |
| Nivel 2 | 100 XP, 250 XP y 500 XP.[1] |
| Nivel 3 | 150 XP, 300 XP, 600 XP y 1000 XP.[1] |

Este reparto sugiere una progresión razonable de dificultad.[1] El Nivel 3 concentra las recompensas más altas, lo que encaja con su complejidad técnica y con el hecho de requerir acceso por terminal en lugar de una simple interacción web.[1]

## Cómo se pinta el progreso en la home

El hub no solo muestra accesos; también representa el estado del usuario dentro de cada nivel.[1] Para ello, `index.php` crea un array inicial de estados de flags y, si existe sesión, consulta en `flags_resueltas` todas las flags que el usuario ha resuelto.[1]

Con ese resultado, cada tarjeta de nivel puede marcar sus mini-flags como completadas, cambiar el color y mostrar pequeñas barras de progreso visuales dentro del bloque “INTEL RECOPILADA”.[1] Esto hace que la página principal funcione como un panel de campaña: el usuario ve de un vistazo qué partes del sistema ya tiene superadas y cuáles le quedan pendientes.[1]

## Ranking

El ranking también se genera en `index.php` y toma sus datos directamente de la tabla `usuarios`.[1] La consulta principal obtiene el top 10 ordenando por `puntos DESC` y usando `id ASC` como criterio de desempate, lo que produce un listado estable cuando dos jugadores tienen la misma puntuación.[1]

Además del top 10 general, el sistema calcula el puesto exacto del usuario autenticado mediante una consulta con `RANK() OVER (ORDER BY puntos DESC, id ASC)`.[1] Si ese usuario no entra en el top 10, la interfaz lo añade igualmente al final de la tabla destacado visualmente, para que siempre vea su propia posición en el sistema.[1]

Una consulta equivalente para reconstruir el top sería esta:

```sql
SELECT username, puntos
FROM usuarios
ORDER BY puntos DESC, id ASC
LIMIT 10;
```

Y para obtener el rango individual, el código sigue una lógica equivalente a esta:[1]

```sql
SELECT rank, puntos
FROM (
  SELECT id, puntos, RANK() OVER (ORDER BY puntos DESC, id ASC) AS rank
  FROM usuarios
) AS sub
WHERE id = ?;
```

## Flujo completo de uso

Si el servicio se recrea correctamente, la experiencia esperada sería esta:[1]

1. El usuario accede a la home principal.[1]
2. Se registra o inicia sesión mediante los modales conectados a `auth.php`.[1]
3. La sesión queda abierta y el hub pasa a mostrar nombre de usuario, progreso y ranking.[1]
4. El usuario elige un nivel y la plataforma permite el acceso solo si está autenticado.[1]
5. Dentro del nivel, abre el laboratorio real mediante URL web o SSH.[1]
6. Obtiene una flag y vuelve al formulario de validación del nivel.[1]
7. El backend comprueba si la cadena es correcta y si ya había sido reclamada.[1]
8. Si es válida y nueva, suma XP en `usuarios`, registra la flag en `flags_resueltas` y el cambio se refleja después en el ranking y en la home.[1]

## Recomendaciones de recreación segura

Aunque el sistema es funcional, para reinstalarlo o publicarlo conviene corregir varios aspectos sensibles que en el código original aparecen resueltos de manera muy directa.[1] Estas mejoras no cambian el funcionamiento, pero sí hacen que el servicio sea más mantenible y más seguro.[1]

- Sacar la contraseña de PostgreSQL fuera de `db.php` y usar variables de entorno.[1]
- No dejar flags reales dentro del código PHP en un entorno de producción o compartido.[1]
- No mostrar credenciales SSH en texto visible si el laboratorio va a estar expuesto fuera de un contexto controlado.[1]
- Añadir validaciones más estrictas en el registro para evitar duplicados, entradas vacías o formatos no deseados.[1]
- Separar CSS y JavaScript en archivos propios para que el mantenimiento del proyecto sea más limpio.[1]
- Crear un panel de administración específico para revisar usuarios, progreso y ranking sin depender de consultas manuales a base de datos.[1]

## Resumen final

XPLOIT puede entenderse como una plataforma PHP de control y gamificación para laboratorios de ciberseguridad.[1] Para recrearla correctamente hay que desplegar el frontend/backend principal con PHP y PostgreSQL, restaurar las tablas `usuarios` y `flags_resueltas`, configurar las sesiones, copiar los validadores de cada nivel y dejar operativos los laboratorios externos a los que esos niveles apuntan por HTTP o SSH.[1]

Su valor no está solo en la parte visual, sino en cómo conecta identidad, progreso, validación y ranking dentro de una misma experiencia.[1] Precisamente por eso, una documentación útil del proyecto debe mezclar explicación funcional con pasos de instalación, porque ambas partes forman el servicio real.[1]