# GUÍA DE USUARIO // XPLOIT
## Sistema de Entrenamiento en Ciberseguridad

**XPLOIT** es una plataforma diseñada para poner a prueba y mejorar tus capacidades en el ámbito de la ciberseguridad. A través de diferentes retos y niveles, los usuarios pueden enfrentarse a escenarios reales de ataque y defensa, validando sus conocimientos mediante la captura de **flags** (puntos de control).

---

### 1. ACCESO Y REGISTRO (IDENTIDAD)
Para interactuar con la plataforma y registrar tus progresos, es necesario disponer de una cuenta de usuario.

* **Login:** Si ya dispones de una identidad, utiliza el botón **LOGIN** en la parte superior derecha. Introduce tu usuario (o email) y tu contraseña para acceder.
* **Registro (Nueva Identidad):** Si eres un nuevo agente, utiliza el botón **REGISTER**. Deberás completar el formulario con tu información personal (Nombre, Apellido, Fecha de nacimiento, Usuario, Email y Contraseña). Una vez confirmada tu nueva identidad, el sistema te permitirá iniciar sesión automáticamente.

---

### 2. PÁGINA PRINCIPAL (HUB)
Al iniciar sesión, visualizarás el panel central:

* **Rankings (TOP 10 GLOBAL):** En la parte inferior se muestra una tabla con los 10 mejores puntuadores de la plataforma. Si estás logueado, tu posición aparecerá resaltada para que puedas comparar tu progreso.
* **Volver al Hub:** En cualquier momento, puedes regresar a esta pantalla principal haciendo clic en el botón de navegación superior, dentro de cualquier nivel.
* **Desconexión:** Utiliza el botón **DISCONNECT** para cerrar tu sesión de forma segura.

---

### 3. LOS NIVELES DE ENTRENAMIENTO
La plataforma se divide en tres niveles temáticos. Para acceder a ellos, debes estar autenticado en el sistema.

#### NIVEL 1: APACHE (Web Inspector)
Enfocado en el análisis de código fuente y el descubrimiento de rutas expuestas.
* **Cómo proceder:** Haz clic en **INICIAR MISIÓN**. Se abrirá una ventana de conexión donde podrás desplegar los entornos disponibles (Nivel 1, 2 y 3). Accede a cada URL proporcionada, investiga el servidor Apache y localiza las flags ocultas.

#### NIVEL 2: MYSQL (Auth Bypass)
Centrado en técnicas de inyección SQL (SQLi) para vulnerar sistemas de autenticación.
* **Cómo proceder:** Accede mediante el botón **INICIAR MISIÓN**. Deberás identificar las vulnerabilidades en los formularios de entrada para extraer datos confidenciales o saltar la comprobación de contraseñas.

#### NIVEL 3: LINUX (Container Hunt)
Especializado en la exploración de sistemas de archivos y gestión de permisos en entornos Linux.
* **Cómo proceder:** Utiliza el botón de conexión para obtener tus credenciales SSH y acceder al terminal del contenedor. Deberás navegar por el sistema de directorios (usando comandos como `ls`, `find` o `cat`) para localizar los archivos ocultos que contienen las flags.

---

### 4. VALIDACIÓN DE FLAGS Y PUNTUACIÓN
Cada nivel contiene retos específicos. Cuando encuentres una cadena de texto (flag) en el sistema objetivo:

1.  Regresa a la interfaz de **VALIDACIÓN DE FLAGS** del nivel correspondiente.
2.  Introduce el código en el campo de texto habilitado para la flag específica.
3.  Haz clic en **EJECUTAR VALIDACIÓN**.

**Nota:** Si la flag es correcta, el sistema sumará automáticamente los puntos (XP) a tu perfil y se actualizará tu rango en el ranking global.

> [!IMPORTANTE]
> Recuerda: Cada flag solo puede ser validada una vez por usuario. Utiliza las **PISTAS** desplegables situadas a la derecha de cada nivel si te encuentras bloqueado en alguna fase del ataque.