# XPLOIT - Guía de administración del Nivel 3 Alpine (LXC)

## Visión general
El Nivel 3 es el laboratorio Linux de XPLOIT orientado a enumeración, búsqueda de archivos y tratamiento de texto desde terminal. Aunque internamente el proyecto lo llama Alpine, la lógica real del entorno se apoya en un contenedor con SSH y usuarios locales, pensado para que el alumno trabaje como si estuviera dentro de una máquina remota aislada.

A nivel de administración, este nivel debe entenderse como un servicio de acceso remoto más un conjunto de ficheros distribuidos en el sistema de archivos. El reto no consiste solo en “entrar por SSH”, sino en seguir una progresión de lectura, búsqueda y filtrado que termina en una validación de flag integrada con la plataforma general.

## Objetivo pedagógico
El objetivo es entrenar al alumnado en habilidades muy habituales de administración y soporte Linux: conexión por SSH, navegación de directorios, búsqueda con `grep` y `find`, y tratamiento de ruido con `sort` y `uniq`. El valor del nivel está en que cada flag obliga a usar una herramienta distinta y a entender el tipo de problema que resuelve.

Desde el punto de vista didáctico, el laboratorio enseña que muchas veces la información no está en el sitio obvio. A veces está en un fichero sencillo del home, otras en una ruta menos evidente, y otras enterrada entre líneas repetidas que hay que limpiar antes de poder leer la correcta.

## Estructura del reto
El nivel se despliega como un contenedor Docker expuesto por SSH en el puerto 2222. Eso permite aislar el laboratorio del resto del sistema y facilita que el administrador pueda reiniciar, recrear o limpiar el entorno sin tocar otros servicios del proyecto.

La progresión interna se divide en cuatro pasos funcionales. Cada uno representa un patrón distinto de exploración del sistema y, aunque el usuario lo vea como una sola misión, administrativamente conviene tratarlo como cuatro estados o cuatro hitos conectados.

## Acceso por SSH
La entrada al laboratorio se hace mediante SSH. En este tipo de reto, el primer punto técnico importante es que el servicio SSH debe estar levantado correctamente, con usuarios locales creados para el laboratorio y con permisos adecuados sobre su directorio personal.

A nivel de administración, hay dos cosas críticas: que el puerto publicado realmente apunte al contenedor correcto y que los permisos de autenticación estén bien definidos. Si el acceso falla, no solo se rompe la jugabilidad, también se rompe el flujo completo de aprendizaje.

## Subniveles internos

### Subnivel 1: Acceso inicial
El alumno entra por SSH y debe localizar un archivo sencillo dentro de su directorio personal para obtener la primera flag. Este paso sirve para que se familiarice con el entorno, con el usuario asignado y con la estructura básica del sistema.

Administrativamente, conviene que este primer archivo sea muy claro y que no requiera técnicas avanzadas. El objetivo es confirmar que el acceso funciona y que el alumno entiende dónde empieza el laboratorio.

### Subnivel 2: Búsqueda de palabra clave
En el segundo paso, la flag ya no aparece de forma directa en el home y el alumno debe localizar una línea concreta dentro de un archivo más grande usando `grep`. Aquí el reto cambia de “navegar” a “buscar con intención”.

Desde administración, este es un buen punto para introducir archivos de tamaño moderado, con contenido realista y una palabra clave lo bastante concreta como para que la búsqueda tenga sentido. La idea es que el alumno aprenda a filtrar antes de leer manualmente.

### Subnivel 3: Búsqueda en el sistema
La flag se desplaza fuera del directorio personal y el alumno debe usar `find` para localizar el archivo correcto. Este paso obliga a explorar rutas del sistema, no solo el entorno inicial del usuario.

Para el administrador, este subnivel debe equilibrar bien la dificultad: suficiente para obligar a explorar, pero no tan oculto que se convierta en una lotería. Lo ideal es que el patrón de búsqueda recompense la observación de permisos, nombres de archivo o ubicaciones típicas.

### Subnivel 4: Ruido y filtrado
En el último paso, el archivo contiene muchas líneas repetidas y una sola línea distinta. El alumno debe usar herramientas de ordenación y deduplicación como `sort` y `uniq -u` para aislar la línea correcta.

Este subnivel es especialmente interesante porque enseña una técnica muy real de administración: limpiar ruido antes de interpretar datos. El reto no es solo encontrar el archivo, sino procesar su contenido de manera eficiente.

## Qué aprende el alumno
- Conexión SSH.
- Lectura de archivos en Linux.
- Uso de `grep`, `find`, `sort` y `uniq`.
- Exploración de rutas no evidentes.
- Tratamiento de información repetida o ruidosa.

Este bloque de habilidades es muy útil para cualquier entorno Linux real. La gracia del laboratorio es que cada herramienta no se presenta de forma teórica, sino aplicada a un objetivo concreto de progreso.

## Integración en la plataforma XPLOIT
En la home principal, este nivel aparece como `CONTAINER_HUNT` y se presenta como un reto de exploración del sistema de archivos del contenedor. Igual que en el resto del proyecto, el progreso debe quedar reflejado en la base de datos central de XPLOIT para que la interfaz principal marque flags completadas y actualice el ranking.

Eso significa que el laboratorio Linux no debe ser un servicio aislado sin conexión con el portal. El alumno resuelve el reto en el contenedor, pero el avance se registra arriba, en la plataforma global.

## Validación de acceso
El nivel debe estar protegido por sesión igual que el resto de laboratorios. Si el usuario no está autenticado, no debería poder entrar al panel del nivel ni validar progreso. Eso es importante porque la puntuación depende de la identidad del alumno y no de un acceso anónimo al contenedor.

La parte de acceso SSH y la parte de progreso web son dos piezas distintas, pero deben trabajar juntas. El SSH abre la puerta al reto, mientras que la web principal conserva la trazabilidad del avance.

## Conectividad con el laboratorio
Para la documentación final, la referencia de acceso debe ser el dominio del proyecto, `xploit.cat`, junto con el puerto o el mecanismo de publicación que corresponda. No conviene escribir IPs fijas si la infraestructura puede cambiar, porque eso obliga a rehacer documentación que debería ser estable.

Si el reto se publica con contenedores, lo normal es que el puerto 2222 del host redirija al SSH del contenedor. Administrativamente esto simplifica el despliegue y permite separar el laboratorio del resto de servicios del proyecto.

## Despliegue recomendado
El laboratorio debe construirse con Docker y ejecutarse de forma aislada. Esa decisión tiene tres ventajas: facilita el reinicio del entorno, reduce el riesgo de interferencia con otros retos y permite versionar el contenido del laboratorio como un bloque independiente.

En una recreación correcta, cada usuario local y cada archivo deben generarse con cuidado para que el flujo de progresión sea estable. No basta con copiar archivos; también hay que verificar rutas, permisos, propiedad de ficheros y exposición del puerto SSH.

## Pistas para estudiantes
Las pistas deben guiar sin regalar la solución. En este nivel, lo más útil es orientar al alumno a revisar primero el directorio personal, después a buscar palabras clave dentro de archivos grandes y, si no encuentra la flag, a explorar otras rutas del sistema.

Cuando haya mucho ruido, el consejo correcto es procesar el texto antes de leerlo manualmente. Ahí es donde entran `sort` y `uniq`, porque ayudan a limpiar duplicados y a descubrir la línea distinta que realmente importa.

## Gestión de flags
Las flags de este nivel no deben reutilizarse entre despliegues. Cada instalación del laboratorio debe crear sus propias cadenas, asociarlas a los hitos internos y vincularlas al sistema de progreso general mediante la base de datos del proyecto.

Administrativamente, cada flag debe corresponder a un identificador único para que el panel principal pueda marcar el avance por nivel. Además, el sistema debe impedir que la misma flag sume puntos más de una vez.

## Validación y puntos
El mecanismo de puntos debe seguir el mismo patrón que el resto de XPLOIT: si el usuario resuelve una flag nueva, se actualiza `usuarios`, se registra la relación en `flags_resueltas` y se refresca el ranking global. Eso hace que el avance sea persistente y visible desde la home.

Como en los otros niveles, la lógica debe evitar duplicados y no confiar solo en el frontend. La validación debe pasar por servidor para que el sistema sea coherente incluso aunque el alumno manipule la interfaz.

## Explicación técnica del tratamiento de texto
La parte final del nivel merece una explicación técnica porque enseña una práctica muy común en Linux: ordenar primero y filtrar después. `uniq` solo elimina líneas duplicadas si están juntas, así que normalmente se combina con `sort` para agrupar repeticiones antes de deduplicar.

En el reto, esto permite convertir un archivo ruidoso en una salida limpia. El alumno no memoriza un comando; aprende a reconocer que, cuando hay mucha repetición, primero se ordena y luego se reduce el ruido. Esa lógica es exactamente la que usan muchas tareas reales de administración y análisis de logs.

## Qué revisar al desplegarlo
Antes de publicar el nivel, el administrador debería comprobar:
- que el contenedor arranca correctamente,
- que SSH responde en el puerto asignado,
- que los usuarios locales existen,
- que los ficheros están en las rutas esperadas,
- que el contenido de cada paso coincide con la progresión,
- y que la conexión con XPLOIT guarda el progreso de cada flag.

También conviene validar permisos y propiedades de archivos, porque un laboratorio Linux mal montado suele fallar más por permisos que por contenido.

## Flujo resumido de recreación
1. Crear el contenedor Docker con SSH.
2. Publicar el puerto 2222 o el que corresponda.
3. Definir usuarios y ficheros de cada paso.
4. Colocar la progresión de flags en rutas distintas.
5. Conectar la validación con la base de datos central.
6. Comprobar que el ranking y el progreso se reflejan en la home.

## Cierre
Este nivel es muy sólido desde el punto de vista pedagógico porque obliga a usar terminal de verdad: SSH, lectura de archivos, búsqueda y filtrado. Bien administrado, funciona como un cierre perfecto para la progresión de Linux porque mezcla acceso remoto, exploración del sistema y tratamiento práctico de texto.