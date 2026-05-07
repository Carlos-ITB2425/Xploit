# Documentación Técnica: Reto Alpine

Esta documentación describe la arquitectura y los niveles de desafío del laboratorio basado en **Ubuntu 22.04**, diseñado para practicar enumeración de sistemas, gestión de usuarios y búsqueda de información en entornos Linux.

---

## 1. Resumen del Proyecto
El laboratorio consiste en un contenedor único que expone un servicio **SSH**. Dentro del sistema, existen cuatro usuarios distintos, cada uno representando un nivel de dificultad progresivo donde se debe localizar una flag específica.

* **Tecnologías:** Docker, Ubuntu 22.04, OpenSSH Server.
* **Acceso Externo:** Puerto `2222` (mapeado al puerto 22 interno).
* **Usuarios del Sistema:** `xploit1`, `xploit2`, `xploit3`, `xploit4` (Password igual al nombre de usuario).

---

## 2. Infraestructura y Despliegue
El entorno se levanta mediante **Docker Compose**, inyectando las flags como argumentos de construcción (`args`) para asegurar que se graben en el sistema de archivos durante la creación de la imagen.

### Configuración de Red
| Servicio | Contenedor | Puerto Host | Puerto Contenedor |
| :--- | :--- | :--- | :--- |
| `alpine-lab` | `alpine-lab` | 2222 | 22 |

---

## 3. Análisis de Niveles y Flags

### ## Nivel 1: Enumeración Básica
* **Usuario:** `xploit1`
* **Localización:** `/home/xploit1/readme.txt`
* **Concepto:** Lectura de archivos con permisos restringidos (`400`).
* **Flag:** `0R9MCObUHzK6cslk35pC`

### ## Nivel 2: Permisos de Grupo y Rutas del Sistema
* **Usuario:** `xploit2`
* **Localización:** `/var/backups/hidden_secrets/misterio.txt`
* **Concepto:** El archivo pertenece a `root` pero el grupo `xploit2` tiene permisos de lectura (`440`).
* **Flag:** `n54Nl0X13FlAA9D1JBxe`

### ## Nivel 3: Archivos Ocultos (Dotfiles)
* **Usuario:** `xploit3`
* **Localización:** `/home/xploit3/.secret_flag`
* **Concepto:** Uso de archivos ocultos. Requiere `ls -a`.
* **Flag:** `m7dXY8rHWmlzloS20VQ8`

### ## Nivel 4: Análisis de Datos y Ruido
* **Usuario:** `xploit4`
* **Localización:** `/opt/.cache/.local/share/.backup_store/data.txt`
* **Concepto:** Estructura de directorios profunda y archivo con 800 líneas de distracción. Requiere `grep` o herramientas de filtrado.
* **Flag:** `QzWwOa22Z129EKruLuDZ`

---

## 4. Conexión de los Jugadores
Para acceder a los diferentes niveles, los usuarios deben conectarse vía SSH utilizando las credenciales preconfiguradas.

**Comando de acceso:**
```bash
ssh <usuario>@<IP_DEL_SERVIDOR> -p 2222