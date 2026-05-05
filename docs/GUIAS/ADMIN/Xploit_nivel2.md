# XPLOIT - Guía de administración del Nivel 2 MySQL/SQLite

## Visión general
El Nivel 2 es el laboratorio de inyección SQL de XPLOIT y está diseñado como un terminal interactivo con lógica vulnerable intencionada. El objetivo no es solo que el alumno “entre” en el sistema, sino que entienda cómo una consulta SQL mal construida puede ser manipulada, cómo responde internamente la aplicación y cómo se registra ese comportamiento en el sistema.  

A nivel de administración, este nivel debe verse como un módulo completo: entrada de datos, construcción de consulta, ejecución sobre SQLite, filtrado de resultados, detección de bypass, feedback visual y registro en log.

## Objetivo pedagógico
El reto enseña el funcionamiento real de una inyección SQL desde el punto de vista de la aplicación. El alumno debe comprender que el problema no está en la base de datos en sí, sino en cómo PHP construye la sentencia usando texto del usuario sin parametrizar.  

También sirve para diferenciar entre:
- un login vulnerable,
- un bypass trivial,
- una extracción de información útil,
- y una explotación que realmente aporta progreso dentro del laboratorio.

## Estructura del código
El script arranca con `session_start()` y define tres flags internas del nivel. Después configura dos ficheros locales:
- la base de datos SQLite,
- y el archivo de log de ataques.

Esto significa que el laboratorio tiene persistencia local, y por tanto el administrador debe asegurarse de que el directorio existe, que PHP tiene permisos y que los archivos sobreviven entre reinicios si se desea mantener estado.

La conexión se hace con PDO sobre SQLite:

```php
$db = new PDO("sqlite:$db_file");
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
```

Administrativamente, esto es importante porque hace que los errores SQL se conviertan en excepciones controlables. El reto puede mostrar errores de sintaxis al alumno, pero el servidor sigue teniendo trazabilidad del evento en el log.

## Flujo interno de la inyección
El núcleo del reto es esta consulta:

```php
$query = "SELECT * FROM usuarios WHERE username = '$user' AND password = '$pass'";
```

Aquí el problema técnico es claro: se inserta texto del usuario directamente en la consulta. Eso convierte el login en una consulta dinámica vulnerable a inyección SQL.  
Internamente, cuando el navegador envía el formulario, PHP recoge `user` y `pass`, construye la sentencia, la manda a SQLite y evalúa el resultado. Si el atacante altera la estructura de la sentencia, la base de datos no interpreta el texto como “usuario y contraseña”, sino como parte de la lógica SQL.

## Cómo funciona una inyección
Una inyección SQL ocurre cuando el atacante consigue que el motor de base de datos procese su entrada como código y no como dato. Eso pasa porque el desarrollador mezcla consulta y entrada en una misma cadena sin usar consultas preparadas.  

Lógicamente, el proceso suele seguir este orden:
1. el formulario recibe un valor,
2. la aplicación lo pega en la consulta,
3. el motor SQL interpreta la sentencia completa,
4. si el atacante ha roto la estructura, la consulta devuelve filas inesperadas o datos adicionales.

En este reto eso se ve muy bien porque el login no solo comprueba si hay resultados, sino que luego decide qué hacer según el contenido devuelto. Es decir, el resultado de la consulta no termina en “sí” o “no”, sino que alimenta una segunda capa de lógica.

## Detección de bypass básico
Después de ejecutar la consulta, el script comprueba si ha devuelto filas. Si no devuelve nada, el login falla. Si devuelve filas, entra en una lógica adicional que analiza la propia query para detectar bypasses demasiado obvios.  

Eso significa que el reto no premia simplemente “romper el login”, sino que exige una explotación algo más trabajada. En términos lógicos, el sistema intenta distinguir entre:
- un acceso legítimo,
- un bypass trivial de tautología,
- y una extracción más interesante de información.

Esa distinción es muy útil en administración porque obliga al alumno a ir más allá del patrón clásico y al mismo tiempo hace que el laboratorio tenga personalidad propia.

## Extracción de datos
Si la consulta devuelve filas y no se detecta la tautología básica, el script recorre cada valor de cada fila. Cuando encuentra cadenas largas, las interpreta como información relevante y las muestra como `DATA_EXTRACTED`.  

Esta lógica está pensada para simular que el atacante ha conseguido sacar algo útil de la base de datos, no solo pasar el login. Administrativamente, esto permite que el laboratorio reaccione a resultados reales y que las flags internas puedan localizarse dentro de los datos devueltos.

En otras palabras: la consulta no se usa solo para autenticar, sino como canal de exfiltración controlada de datos del reto.

## Estados del terminal
El frontend del nivel usa varios estados para representar el comportamiento del ataque:
- `normal`.
- `alert`.
- `shame`.
- `party`.

Cada estado tiene un significado distinto:
- `normal`: el entorno está en reposo.
- `alert`: el login ha funcionado, pero no se ha encontrado un secreto.
- `shame`: se ha detectado una inyección demasiado simple.
- `party`: se ha encontrado una flag válida.

Esto no es solo decoración. Desde administración, estos estados son parte de la retroalimentación del alumno y deben mantenerse coherentes con la lógica del backend.

## Registro de eventos
El nivel escribe actividad en `/var/lib/sqlite/ataques.log`. Allí se anotan intentos, errores, IPs y consultas ejecutadas. Esto aporta trazabilidad y permite revisar qué ha pasado en el laboratorio sin depender únicamente de la interfaz web.  

Para un administrador, este log es importante por tres motivos:
- permite auditar el uso del reto,
- ayuda a depurar fallos,
- y ofrece contexto si el reto está siendo usado de manera inesperada.

Si el laboratorio se despliega en público, conviene vigilar que el log no exponga más detalle del necesario y que los permisos estén bien ajustados.

## Integración con XPLOIT
El Nivel 2 no vive aislado. En la home principal aparece como `AUTH_BYPASS`, y su progreso debe enlazar con la base de datos general del sistema.  
Eso significa que las flags de este nivel tienen que existir también como identificadores de progreso, para que `index.php` pueda pintar qué objetivos ya están completados y actualizar el ranking global.

La integración correcta implica dos niveles:
- el nivel local, que ejecuta la vulnerabilidad y detecta el resultado,
- y el nivel global, que registra el progreso del usuario dentro del portal XPLOIT.

## Gestión de flags
Las flags no deben reutilizarse entre despliegues ni publicarse en documentación final. Cada instalación debe generar sus propias cadenas y asociarlas a los tres objetivos del nivel.  

El flujo lógico debe ser siempre el mismo:
1. el alumno envía una cadena,
2. el backend la evalúa,
3. si coincide con una flag del reto, se marca el objetivo,
4. si no ha sido resuelta antes, se suman puntos,
5. y se registra la relación en la base de datos principal.

Eso evita que el alumno pueda ganar experiencia dos veces con el mismo hallazgo y garantiza que el ranking tenga sentido.

## Qué revisar al desplegarlo
Antes de poner el nivel en marcha, el administrador debería comprobar:
- que `/var/lib/sqlite/ctf.db` existe y tiene el esquema correcto,
- que `/var/lib/sqlite/ataques.log` se puede escribir,
- que la consulta vulnerable funciona,
- que la detección de tautología no rompe el reto,
- que el frontend cambia de estado correctamente,
- y que la integración con XPLOIT registra el progreso.

También conviene validar que el reto no filtre detalles innecesarios en errores de sintaxis y que el comportamiento sea estable tras varios intentos seguidos.

## Explicación técnica de las inyecciones
A nivel lógico, una inyección SQL funciona porque el motor de base de datos no distingue por sí mismo si una parte de la sentencia era “dato” o “código”. Esa distinción la tiene que imponer la aplicación. Si la aplicación pega una cadena sin control, la consulta resultante puede cambiar de significado.  

Por ejemplo, un filtro de autenticación espera algo como:
- usuario exacto,
- contraseña exacta.

Pero si la entrada modifica la estructura del `WHERE`, la base de datos puede terminar evaluando otra condición distinta. En vez de “usuario A y contraseña B”, puede interpretar una condición que siempre da verdadero o una consulta que devuelve otras filas.

La parte importante en este reto es que la lógica del script no solo acepta o rechaza, sino que revisa la forma de la query y el contenido devuelto. Eso convierte la inyección en una mecánica de análisis: no basta con romper la consulta, hay que romperla de forma que produzca una salida útil.

## Recomendación de administración real
Para una guía de admin seria, este nivel debe explicarse como un sistema vulnerable controlado, con tres capas:
- capa de entrada: formulario de login,
- capa de interpretación: construcción de query SQL,
- capa de salida: resultados, estados y logs.

La explicación técnica más útil no es “cómo explotar”, sino cómo el flujo de datos pasa de texto del usuario a sentencia SQL, y cómo eso afecta al comportamiento del terminal. Ese es el punto clave que debería quedar claro en la documentación.

## Flujo resumido de recreación
1. Crear el servicio PHP con SQLite.
2. Definir el fichero de base de datos y el log persistente.
3. Montar el formulario vulnerable.
4. Implementar la detección de bypass trivial.
5. Mostrar resultados y estado visual según la consulta.
6. Conectar las flags al sistema global de XPLOIT.
7. Verificar que el ranking y el progreso se actualizan correctamente.

## Cierre
Este nivel es útil porque enseña una vulnerabilidad real, pero de manera controlada y con feedback visible. Bien administrado, permite explicar tanto el problema de la concatenación SQL como la importancia de registrar eventos, controlar estados y enlazar el reto local con el progreso general del portal.