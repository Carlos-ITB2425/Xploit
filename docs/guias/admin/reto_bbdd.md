# Proyecto CTF: Documentación de Infraestructura y Despliegue

## 1. Introducción
Este documento detalla la arquitectura de red y la configuración de seguridad implementada para el despliegue de retos de ciberseguridad (CTF). La infraestructura se basa en un modelo de dos capas en AWS: una capa perimetral de filtrado (Firewall) y una capa de servicios (Xploit Host).

## 2. Arquitectura de Red
La red se divide en dos instancias principales con roles diferenciados para maximizar la seguridad y el control del tráfico.

### 2.1. Firewall Perimetral (Gateway)
- **IP Pública:** 34.200.XX.XX
- **Función:** Actúa como el único punto de entrada para los usuarios. Gestiona la inspección de tráfico y la redirección de puertos mediante el uso de `iptables`.
- **Configuración de Seguridad (Security Group):**
    - Puertos reservados para retos, accesibles globalmente:
        80, 443, 6969, 2222, 8081-8083.
    - Puerto SSH (22): Restringido para acceso administrativo.

### 2.2. Host Xploit (Servidor de Aplicaciones)
- **IP Privada:** 172.31.XX.XX
- **Función:** Aloja los servicios y retos (Dockerizados). No es accesible directamente desde internet para los retos, sino que recibe el tráfico redirigido por el Firewall.
- **Configuración de Seguridad (SSH):**
    - Se ha inhabilitado la autenticación por contraseña (`PasswordAuthentication no`).
    - Solo se permite el acceso mediante clave pública utilizando el archivo de identidad `.pem`.

## 3. Configuración de Redirección (DNAT)
Para permitir que el tráfico llegue desde el Firewall hasta el Host Xploit, se han implementado reglas de traducción de direcciones de red (NAT) en la tabla `nat` de `iptables`.

### Reglas Implementadas:
Se han redirigido los siguientes servicios críticos:
- **HTTP (80) y HTTPS (443):** Necesarios para la navegación web y la posterior validación de certificados.
- **Reto SQLi (6969):** Redirección directa al contenedor de base de datos.
- **Reto Alpine (2222):** Acceso SSH al entorno restringido.
- **Retos Web Adicionales (8081-8083):** Puertos dedicados a retos específicos.

### Persistencia de Reglas:
Dado que las reglas de `iptables` son volátiles, se ha configurado un script de carga automática en `/etc/network/if-pre-up.d/iptables` que restaura las reglas desde `/etc/iptables/rules.v4` durante el arranque de la interfaz de red.

## 4. Reto de Base de Datos (SQL Injection)
El reto principal consiste en una vulnerabilidad de SQL Injection de tipo "Union-Based" y "Error-Based" sobre una base de datos SQLite.

### Características del Reto:
- **Lógica de Detección:** El script PHP analiza las consultas. Si se detecta un bypass trivial mediante tautologías simples (ej. `OR 1=1`), el terminal entra en estado de `shame` (animación de error), incentivando al alumno a usar técnicas más avanzadas como `UNION SELECT`.
- **Feedback Verboso:** En caso de error sintáctico, el sistema devuelve el error de la base de datos y la query ejecutada, facilitando la fase de enumeración del ataque.
- **Seguridad del Contenedor:** La aplicación se ejecuta en un entorno aislado con acceso restringido al sistema de archivos del host.

---
*Documentación generada para el equipo de administración de infraestructura.*
