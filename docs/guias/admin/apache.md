# Documentación Técnica: Reto Apache

Esta documentación describe la arquitectura, configuración y lógica de explotación de la infraestructura de contenedores diseñada para el entrenamiento en seguridad web (CTF).

---

## 1. Resumen del Proyecto
El proyecto despliega un entorno multi-nivel utilizando **Docker Compose** para simular vulnerabilidades reales en servidores Apache y aplicaciones PHP.

* **Tecnologías:** Docker, Docker Compose, Apache 2.4, PHP 8.2.
* **Objetivo:** Identificar y explotar fallos de configuración y lógica de programación.
* **Puertos de Acceso:** 8081 (Nivel 1), 8082 (Nivel 2), 8083 (Nivel 3).

---

## 2. Infraestructura de Red y Despliegue
La orquestación se define en el archivo `docker-compose.yml`. Cada servicio es aislado y recibe su secreto (Flag) a través de variables de entorno del sistema.

### Tabla de Servicios
| Servicio | Puerto Host | Directorio Base | Flag Inyectada |
| :--- | :--- | :--- | :--- |
| `apache-nivel1` | 8081 | `./nivel1` | `F8wedQd00MWoS82EPbF` |
| `apache-nivel2` | 8082 | `./nivel2` | `geP2tDjbU50Q2Y6ZYRPj` |
| `apache-nivel3` | 8083 | `./nivel3` | `89YNRlOMmoc0FwLqbAKK` |

---

## 3. Análisis de Niveles

### ## Nivel 1: Exposición de Comentarios (Information Leakage)
* **Vulnerabilidad:** Presencia de información sensible en el código fuente del lado del cliente.
* **Descripción:** El servidor PHP procesa la variable de entorno y la imprime dentro de un comentario HTML (``). Aunque el navegador no lo renderiza visualmente, el dato es accesible para cualquier usuario.
* **Solución:** Inspección manual del DOM o ver código fuente de la página (`view-source`).

### ## Nivel 2: Directorios Expuestos y Autoindex
* **Vulnerabilidad:** Configuración insegura de permisos de directorio y habilitación de `mod_autoindex`.
* **Configuración Crítica:** * El Dockerfile habilita `a2enmod autoindex`.
    * Se crea un archivo de configuración en `/etc/apache2/conf-available/secret.conf` que permite explícitamente el listado de archivos (`Options +Indexes`) en la ruta `.secret`.
* **Solución:** Navegar hacia el directorio oculto `/var/www/html/.secret/` y leer el archivo `flag.txt`.

### ## Nivel 3: PHP Magic Hash (Type Juggling)
* **Vulnerabilidad:** Comparación débil de tipos utilizando el operador `==`.
* **Descripción:** PHP utiliza una lógica de comparación flexible. Al comparar dos strings que comienzan por `0e` seguidos de números, PHP los trata como notación científica ($0 \times 10^n$). Como resultado, cualquier "Magic Hash" de este tipo siempre es igual a 0.
* **Lógica de Explotación:**
    * `real_password = "0e749238492"` (Evaluado como float 0).
    * `input_usuario = "0e215962017"` (Evaluado como float 0).
    * Resultado: `true`.
* **Solución:** Introducir en el formulario cualquier cadena que cumpla el patrón de Magic Hash sugerido en el código de depuración filtrado.

---

## 4. Instrucciones de Operación

### Despliegue
Para iniciar el laboratorio, ejecute el siguiente comando en la raíz del proyecto:
```bash
docker-compose up -d --build
```
