# XPLOIT - Guía de administración del Nivel 1 Apache

## Visión general
El Nivel 1 de XPLOIT corresponde al laboratorio Apache y está planteado como un reto web progresivo orientado a la inspección de contenido servido por Apache y PHP. Aunque internamente se divide en tres subretos, a nivel de administración conviene tratarlo como una única unidad porque comparte la misma lógica pedagógica, el mismo patrón de validación y una estructura de despliegue homogénea. [1]

En la plataforma principal, este nivel aparece en `index.php` como la tarjeta **WEB_INSPECTOR**, identificada como `NIVEL 1: APACHE`, y enlaza a `nivel1.php` como panel de validación y acceso operativo. Además, la home muestra el progreso del usuario autenticado para este nivel mediante una lista de flags completadas y una barra visual por objetivo resuelto. [1]

## Objetivo pedagógico
El objetivo del reto es enseñar al alumnado a observar cómo está expuesta una aplicación web, qué información deja visible el servidor y cómo pequeños fallos de lógica o configuración pueden convertirse en vectores de ataque. No se busca solo que el alumno encuentre una cadena concreta, sino que entienda por qué ese dato estaba accesible y qué mala práctica lo ha permitido. [1]

En este laboratorio se trabajan ideas básicas pero muy importantes: inspección de código fuente, descubrimiento de rutas secundarias, revisión de contenido servido por Apache y análisis de validaciones inseguras en PHP. Por eso es un nivel apropiado como puerta de entrada al resto del sistema. [1]

## Estructura del reto
Administrativamente, este nivel debe mantenerse como un laboratorio compuesto por tres servicios o tres objetivos diferenciados. En el código analizado, el modal de conexión de `nivel1.php` presenta tres accesos independientes, uno por cada subnivel interno, lo que encaja con el planteamiento de tres contenedores o tres publicaciones separadas bajo el mismo bloque temático. [1]

Los tres subniveles responden a ideas distintas. El primero se centra en **View Source**, el segundo en **Directory Listing** o descubrimiento de contenido expuesto, y el tercero en un **PHP Bypass** basado en una comparación débil o una validación insegura. Esta separación es útil porque permite ajustar dificultad, contenido y pistas sin romper el resto del laboratorio. [1]

## Subniveles internos

### Subnivel 1: View Source
Este subnivel debe construirse para que el alumno aprenda a revisar el HTML servido, buscar comentarios, metadatos y fragmentos que no se aprecian a simple vista en la interfaz. La idea no es esconder la flag de forma arbitraria, sino mostrar cómo una web puede filtrar información sensible en el propio código fuente. [1]

Desde el punto de vista administrativo, este primer objetivo debe ser el más directo del bloque Apache. Conviene que la información esté disponible con una inspección razonable del código y que sirva para introducir al usuario en la dinámica del laboratorio. [1]

### Subnivel 2: Directory Listing
Este subnivel debe enseñar descubrimiento de contenido mal publicado, ya sea por un listado de directorio activo, una ruta secundaria accesible o un archivo dejado en una ubicación poco evidente. La parte importante es que el alumno relacione el hallazgo con un problema real de exposición de recursos web. [1]

A nivel de despliegue, este objetivo debe publicarse de forma separada o suficientemente desacoplada para poder modificar rutas, nombres de directorio y material expuesto sin afectar a los otros dos subniveles. Esto también facilita rotar contenido o sustituirlo por variantes futuras. [1]

### Subnivel 3: PHP Bypass
El tercer subnivel debe centrarse en una validación débil en PHP. El objetivo formativo es que el alumnado entienda el riesgo de comparaciones inseguras, conversión implícita de tipos o uso incorrecto de operadores como `==` frente a `===`, siempre presentado de forma controlada y pedagógica. [1]

Este es el subnivel más conceptual del bloque Apache, porque ya no depende solo de observar contenido, sino de comprender el comportamiento de la aplicación. Por eso conviene que la pista técnica sea algo más guiada que en los dos primeros objetivos. [1]

## Qué aprende el alumno
Este laboratorio debe reforzar cuatro aprendizajes principales:
- inspección del código fuente de una aplicación web,
- enumeración de rutas y directorios,
- análisis de validaciones inseguras en PHP,
- e identificación de fallos lógicos en formularios o accesos web. [1]

Desde la perspectiva docente, el nivel funciona bien si cada subreto representa una idea clara y aislada. La meta no es saturar al estudiante con complejidad técnica, sino hacer visibles patrones de exposición y validación deficiente que luego volverán a aparecer en retos más avanzados. [1]

## Integración en la plataforma XPLOIT
Este nivel está integrado con la lógica general del sistema igual que el resto de laboratorios. `index.php` muestra la tarjeta del nivel, comprueba si el usuario tiene sesión iniciada y, si la sesión existe, consulta `flags_resueltas` para pintar el progreso en la tarjeta principal mediante los identificadores de flag del Nivel 1. [1]

En el código se detecta que este bloque usa tres identificadores de progreso para el laboratorio Apache, asociados a tres objetivos distintos dentro del mismo nivel. En la documentación administrativa conviene mantener un naming coherente y estable para que el panel principal, el validador y la base de datos hablen el mismo idioma. [1]

## Validación de acceso
El panel `nivel1.php` no debe considerarse solo una pantalla estética, sino un validador protegido por sesión. En el código actual, al cargar el archivo se comprueba que exista `$_SESSION['user_id']`, y si no existe se redirige al usuario a `index.php`. Eso significa que ningún alumno debería poder validar flags o sumar puntos si no ha iniciado sesión antes en la plataforma. [1]

Este patrón debe mantenerse en cualquier recreación futura del servicio. La navegación del frontend puede ser amigable, pero la seguridad real tiene que seguir resolviéndose en servidor, con verificación de sesión antes de permitir acceso al validador. [1]

Un esquema mínimo de protección sería este: [1]

```php
session_start();
require_once 'db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}
```

## Conectividad con los objetivos
En el código existente, el modal de conexión del Nivel 1 presenta tres accesos separados para los tres objetivos internos, publicados en puertos distintos y descritos como entornos independientes. Para una guía de administración correcta, no conviene dejar IPs fijas escritas en la documentación final, sino referirse al dominio del proyecto, por ejemplo `xploit.cat`, y a los puertos o subdominios que el equipo decida publicar. [1]

Una forma razonable de recrearlo sería usar tres contenedores Apache/PHP y exponerlos como tres servicios separados, por ejemplo mediante puertos distintos o subdominios dedicados. Lo importante no es la ruta exacta, sino que cada subnivel sea accesible de forma independiente y que el panel `nivel1.php` pueda presentar claramente al alumno dónde debe conectarse para resolver cada objetivo. [1]

## Despliegue recomendado
El laboratorio está bien planteado para desplegarse con Docker Compose, manteniendo tres contenedores independientes. Ese enfoque permite aislar configuraciones, rotar contenido de un subnivel sin tocar los demás y simplificar las tareas de mantenimiento. Además, encaja con la propia lógica del reto, que ya se concibe como tres objetivos separados bajo una sola familia temática. [1]

Una estructura orientativa de servicios podría ser la siguiente: [1]

```yaml
services:
  apache_lvl1:
    image: php:apache
    ports:
      - "8081:80"
  apache_lvl2:
    image: php:apache
    ports:
      - "8082:80"
  apache_lvl3:
    image: php:apache
    ports:
      - "8083:80"
```

Este bloque es solo una plantilla conceptual. El equipo administrador deberá adaptar nombres, rutas, volúmenes y publicación al dominio `xploit.cat`, ya sea con proxy inverso, subdominios o puertos publicados. [1]

## Pistas para estudiantes
El panel del Nivel 1 ya incorpora una zona de pistas tipo `ARCHIVOS CON PISTAS`, estructurada en bloques desplegables por objetivo. Esto es útil porque permite guiar al alumnado sin regalar directamente la solución. Sin embargo, en el código actual algunas pistas del Nivel 1 aparecen como pendientes o no definidas, por lo que la guía de administración debe contemplar que el profesorado o equipo técnico complete este apartado antes de publicar el laboratorio. [1]

Para que el reto funcione bien, cada subnivel debería tener al menos una pista funcional y una pista técnica. En este caso, las ideas mínimas recomendadas son las siguientes: revisar el código fuente, probar rutas o directorios poco obvios y analizar con cuidado la lógica de comparación en PHP. Son pistas alineadas con el objetivo pedagógico y no destruyen la dificultad del reto. [1]

## Gestión de flags
Las flags del entorno de producción o del laboratorio real no deben reutilizarse en documentación pública ni en materiales que puedan circular fuera del equipo. La guía administrativa debe dejar claro que cada despliegue tendrá que crear **sus propias flags**, asociadas a cada uno de los tres objetivos del nivel Apache. [1]

Además, esas flags no deben quedarse solo en el contenedor o en el código del reto, sino estar integradas con la lógica general de la plataforma. Eso significa que cada identificador de flag tiene que estar conectado con la base de datos a través del validador PHP, de forma que el sistema pueda saber qué usuario la ha resuelto y cuántos puntos debe recibir. [1]

## Validación y puntos
El funcionamiento interno del validador del Nivel 1 sigue el mismo patrón que el resto de niveles. El usuario introduce una cadena en `nivel1.php`, el backend la compara contra la flag esperada para ese objetivo, y si es correcta comprueba primero si ya existe una relación previa en `flags_resueltas` para ese `user_id` y ese `flag_id`. [1]

Si la flag no había sido reclamada antes, el sistema actualiza los puntos del usuario en la tabla `usuarios` e inserta la relación correspondiente en `flags_resueltas`. Si ya estaba resuelta, el sistema debe informar de ello sin volver a sumar experiencia. Esta lógica es esencial y no debería eliminarse aunque se cambien las flags concretas del reto. [1]

El patrón técnico que debe mantenerse es este: [1]

```php
$check = $pdo->prepare("SELECT COUNT(*) FROM flags_resueltas WHERE user_id = ? AND flag_id = ?");
$check->execute([$userId, $flagId]);

if (!$check->fetchColumn()) {
    $pdo->beginTransaction();
    $pdo->prepare("UPDATE usuarios SET puntos = puntos + ? WHERE id = ?")->execute([$xp, $userId]);
    $pdo->prepare("INSERT INTO flags_resueltas (user_id, flag_id) VALUES (?, ?)")->execute([$userId, $flagId]);
    $pdo->commit();
}
```

## Base de datos implicada
A efectos administrativos, este nivel depende de la misma base de datos PostgreSQL que el resto de la plataforma. La tabla `usuarios` conserva la puntuación total y la identidad del alumno, mientras que `flags_resueltas` registra qué objetivos ya han sido reclamados. Gracias a esta relación, el panel principal puede pintar el progreso y el ranking global puede recalcularse automáticamente. [1]

Por tanto, aunque el reto Apache se despliegue en contenedores separados, su progreso no debe gestionarse de forma aislada. La validación final siempre debe pasar por la aplicación central para mantener coherencia de puntos, ranking y seguimiento individual. [1]

## Recomendaciones de administración
Este nivel debe mantenerse corto, limpio y muy controlado. Cada subnivel debería representar una sola idea central y evitar meter múltiples vulnerabilidades mezcladas en el mismo objetivo. Eso mejora tanto la experiencia de aprendizaje como el mantenimiento técnico. [1]

También conviene revisar tres aspectos antes de ponerlo en producción: que existan pistas reales en los tres objetivos, que la conectividad publicada apunte al dominio o infraestructura vigente de `xploit.cat`, y que los identificadores de flag estén unificados entre interfaz, validador PHP y base de datos. Una incoherencia de naming en este punto puede romper el progreso visible de la home aunque la resolución técnica del reto funcione. [1]

## Flujo resumido de recreación
Para recrear el Nivel 1 de forma ordenada, el procedimiento más razonable sería: diseñar los tres subniveles, preparar sus contenedores Apache/PHP, publicar cada objetivo por separado, definir tres flags nuevas, conectar `nivel1.php` con esos identificadores y validar la integración con PostgreSQL para que la puntuación se refleje correctamente en el hub principal. [1]

Después habría que probar el recorrido completo de usuario: acceso desde `index.php`, bloqueo si no hay sesión, entrada al panel del nivel, apertura de los objetivos, resolución de una flag de prueba, inserción en `flags_resueltas` y suma automática de puntos en `usuarios`. Si ese flujo funciona, el laboratorio ya queda correctamente integrado en XPLOIT. [1]

## Cierre
El Nivel 1 funciona como la introducción práctica al entorno XPLOIT porque combina observación, enumeración y validación de forma muy clara. Su valor está en enseñar al alumno a inspeccionar lo que expone un servidor Apache, a localizar rutas o archivos ocultos y a entender que una validación insegura en PHP puede convertirse en un punto de entrada real.

Desde administración, lo importante es mantener la progresión limpia, con pistas suficientes, flags propias en cada despliegue y una integración correcta con la base de datos para que el progreso se refleje en el ranking global. Si ese flujo está bien montado, el nivel cumple su papel como primer laboratorio de la plataforma y deja preparado al alumno para los retos más técnicos que vienen después.