# Documentación: Sistema de Monitoreo en Vivo (XPLOIT SYS-CON)


## 1. Arquitectura del Sistema
El sistema se ha diseñado bajo una filosofía de **"Fichero Único" (Single-file Component)**. Esto significa que el control de sesiones, la API de datos, la lógica de sistema y la interfaz visual conviven en un solo archivo `index.php`.

### Componentes principales:
* **Backend (PHP):** Gestiona la autenticación por sesión y actúa como un puente (*bridge*) entre el sistema operativo Linux y la web.
* **Lógica de Sistema:** Utiliza funciones nativas (`shell_exec`, `sys_getloadavg`) y lectura de archivos de sistema en `/proc/` para evitar la dependencia de binarios externos.
* **Frontend (HTML5/CSS3/JS):** Interfaz con estética *Cyberpunk/Terminal* utilizando **Chart.js** para las gráficas y **AJAX (Fetch API)** para el refresco sin recarga de página cada 1.5 segundos.



## 2. Configuración del Servidor (Apache)
Para que el sistema sea accesible y funcional, se modificaron los archivos de configuración en `/etc/apache2/sites-enabled/`.

### Archivos de Configuración:
1.  **xploit.cat-le-ssl.conf:** Configura el acceso seguro (HTTPS). Se definió un bloque `<Directory /var/www/html/monitor>` para permitir la ejecución de scripts.
    * **Directivas Clave:**
        * `Options +FollowSymLinks`: Permite que Apache siga enlaces si fuera necesario.
        * `AllowOverride All`: Habilita el uso de archivos `.htaccess`.
        * `Require all granted`: Elimina el error 403 Forbidden al autorizar el acceso público a la carpeta.

2.  **xploit.cat.conf:** Gestiona la redirección forzosa de HTTP a HTTPS mediante `mod_rewrite` para asegurar que las credenciales no viajen en texto plano.



## 3. Seguridad y Permisos
Este es el punto más crítico. Para que el monitor pueda leer logs sensibles sin comprometer la seguridad del servidor, se aplicaron las siguientes medidas:

### Privilegios de Usuario
El servidor web corre bajo el usuario `www-data`. Por defecto, este usuario tiene prohibido leer `/var/log/apache2/`.
* **Solución:** Se añadió a `www-data` al grupo `adm` mediante el comando:
    ```bash
    sudo usermod -aG adm www-data
    ```
    Esto permite la lectura de logs de sistema sin necesidad de ejecutar PHP como root.

### Permisos de Archivos
* **Directorio raíz:** `/var/www/html/monitor` (Permisos `755`).
* **Script principal:** `index.php` (Permisos `644`, propiedad de `www-data:www-data`).



## 4. Funcionamiento de la API Interna
El panel no carga los datos una sola vez; consulta a sí mismo en segundo plano mediante parámetros GET:

* **`?api=stats`:**
    * **CPU:** Obtiene el *Load Average* del sistema.
    * **RAM:** Lee el archivo `/proc/meminfo`, parseando los valores de memoria total y disponible para calcular el porcentaje de uso real.
* **`?api=logs&type=access|error`:**
    * Ejecuta un comando `tail -n 50` sobre los archivos de log reales de Apache.
    * Aplica `htmlspecialchars()` para evitar ataques de *Log Injection* o *XSS* si un atacante intenta inyectar código malicioso a través de las cabeceras de sus peticiones.



## 5. Guía de Mantenimiento

### Cambio de Credenciales
Para cambiar el acceso, edita las variables de configuración en la parte superior del archivo `index.php`:

```php
$ADMIN_USER = '******';
$ADMIN_PASS = '******';