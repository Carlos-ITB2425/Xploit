# Guía de usuario // Xploit
## Sistema de entrenamiento en ciberseguridad

**Xploit** es una plataforma diseñada para poner a prueba y mejorar habilidades en ciberseguridad mediante retos progresivos, validación de flags y ranking competitivo.

## 1. Acceso y registro

Para interactuar con la plataforma y registrar el progreso es necesario disponer de una cuenta de usuario.

- **Login:** si ya existe una cuenta, usar el botón **LOGIN** en la parte superior derecha e introducir usuario o email junto con la contraseña.
- **Registro:** si se accede por primera vez, usar **REGISTER** y completar el formulario con nombre, apellidos, fecha de nacimiento, usuario, email y contraseña. Tras confirmar el alta, el sistema permitirá iniciar sesión.

## 2. Página principal

Al iniciar sesión se muestra el panel central o hub de la plataforma.

- **Niveles:** se muestran los 3 niveles disponibles, su progreso y el acceso a cada uno.
- **Ranking global:** una tabla con los 10 mejores usuarios de la plataforma.
- **Posición personal:** si el usuario está autenticado, su posición en el ranking aparece resaltada.
- **Volver al hub:** desde cualquier nivel se puede regresar a la pantalla principal con el botón de navegación superior.
- **Desconexión:** el botón **DISCONNECT** cierra la sesión de forma segura.

## 3. Los niveles de entrenamiento

La plataforma se divide en tres niveles temáticos. Para acceder a ellos, el usuario debe estar autenticado.

### Nivel 1: Apache

Enfocado en el análisis de código fuente, la inspección de elementos del navegador y el descubrimiento de rutas expuestas.

**Cómo proceder:** usar **INICIAR MISIÓN** para desplegar los entornos disponibles y acceder a las URLs del nivel. A partir de ahí, inspeccionar el servidor Apache y localizar las flags ocultas.

### Nivel 2: MySQL

Centrado en técnicas de inyección SQL para vulnerar sistemas de autenticación.

**Cómo proceder:** acceder al entorno del nivel y analizar los formularios de entrada. La meta es identificar vulnerabilidades que permitan extraer datos confidenciales o saltar la comprobación de contraseñas.

### Nivel 3: Linux / Container Hunt

Especializado en la exploración de sistemas de archivos y gestión de permisos en entornos Linux.

**Cómo proceder:** usar el botón de conexión para obtener credenciales SSH y acceder al terminal del contenedor. Después, navegar por el sistema con comandos como `ls`, `find` o `cat` para localizar los archivos ocultos que contienen las flags.

## 4. Validación de flags y puntuación

Cada nivel contiene retos específicos. Cuando se encuentra una flag, el proceso es el siguiente:

1. Volver a la interfaz de **VALIDACIÓN DE FLAGS** del nivel correspondiente.
2. Introducir el código en el campo de texto habilitado.
3. Pulsar **EJECUTAR VALIDACIÓN**.

Si la flag es correcta, el sistema suma automáticamente los puntos al perfil y actualiza la posición del usuario en el ranking global.

> Cada flag solo puede validarse una vez por usuario. Si surge un bloqueo, se pueden usar las **PISTAS** desplegables situadas a la derecha de cada nivel.

## 5. Flujo recomendado de aprendizaje

La plataforma está pensada para seguir una progresión clara:

- **Nivel 1:** observar y entender qué expone una web sin tocar el backend.
- **Nivel 2:** detectar fallos lógicos en formularios y consultas.
- **Nivel 3:** explorar sistemas Linux, permisos y rutas ocultas.

El objetivo no es solo capturar flags, sino aprender a pensar como un analista de seguridad: observar, enumerar, probar hipótesis y validar resultados.

## 6. Consejos para empezar

- Revisar siempre el código fuente y los recursos cargados por la web.
- Probar rutas, directorios y ficheros ocultos.
- Usar herramientas básicas antes de recurrir a técnicas avanzadas.
- Tomar notas de cada pista y cada flag encontrada.
- Volver al hub con frecuencia para validar el progreso.

## 7. Normas de uso

- No compartir credenciales con otros usuarios.
- No reutilizar flags de otro usuario para validar progreso propio.
- No alterar los contenedores o los servicios fuera de lo necesario para resolver el reto.
- Mantener la sesión abierta solo el tiempo necesario para trabajar.

## 8. Apoyo y resolución de problemas

Si un nivel no carga correctamente, comprobar primero la conexión, refrescar la página y volver a entrar desde el hub. Si una validación falla, revisar que la flag se haya copiado completa y sin espacios adicionales.

Si el acceso SSH o la URL del nivel no responde, lo correcto es revisar el estado del servicio, la dirección asignada y las credenciales del entorno.

## 9. Notas técnicas del entorno

La plataforma utiliza contenedores y despliegue reproducible mediante Docker Compose para simplificar la gestión de servicios y mantener el entorno consistente entre desarrollo y pruebas. En el apartado web, la lógica de autenticación y validación debe tratar comparaciones y entradas del usuario con cuidado, especialmente en PHP, donde las comparaciones débiles pueden provocar bypass de autenticación si se usan operadores inseguros como `==` en lugar de `===`.

En los retos de tipo web, también conviene recordar que Apache puede exponer información sensible mediante configuraciones débiles o directorios accesibles, por lo que la inspección del HTML, de directorios y de rutas expuestas forma parte del aprendizaje.

## 10. Monitoreo del Sistema (XPLOIT SYS-CON)

Para supervisar el rendimiento de la infraestructura, se ha habilitado un panel de control en tiempo real.

- **Acceso:** Se puede visualizar en [https://xploit.cat/monitor/](https://xploit.cat/monitor/).
- **Información disponible:** El panel muestra métricas de CPU, uso de memoria RAM y el flujo de logs de acceso y errores del servidor.
- **Seguridad:** El acceso a los datos detallados requiere autenticación de administrador. Los usuarios autorizados deben iniciar sesión para activar el refresco dinámico de las métricas de sistema.