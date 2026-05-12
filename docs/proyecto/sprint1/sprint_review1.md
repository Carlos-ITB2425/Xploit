# ACTA DE REUNIÓN - SPRINT 1: PROYECTO XPLOIT

**Proyecto:** Xploit (CTF Platform)
**Fecha:** 27 de abril de 2026
**Estado:** Infraestructura Base y Definición de Retos

---

## ASISTENTES

* Adrián González
* Marc Manzorro
* Carlos Rodriguez

---

## TAREAS COMPLETADAS

### 1. Planificación y Gestión del Proyecto
* **Sprint Planning:** Definición de objetivos y reparto de tareas iniciales.
* **Control de Versiones:** Creación y configuración del repositorio GitHub para la colaboración del equipo.
* **Documentación:** Redacción de actas y capturas de seguimiento en Proofhub.

### 2. Análisis y Diseño del CTF
* **Estudio de Mercado:** Identificación de necesidades y análisis de soluciones existentes (referencia: OverTheWire).
* **Desarrollo de Ideas:** Conceptualización de retos basados en SSH con privilegios mínimos, inyecciones SQL y contenedores Linux.
* **Diseño de Niveles:** Establecimiento de una progresión de dificultad (Fácil, Medio, Difícil).
* **Selección de Dominio:** Verificación de disponibilidad para el dominio `xploit.es` en DonDominio.

### 3. Infraestructura Cloud (AWS) y Redes
* **Instancia AWS:** Creación de la primera instancia EC2 (Ubuntu) para el entorno de trabajo.
* **Seguridad de Acceso:** Configuración de acceso seguro mediante el despliegue de llaves públicas SSH.
* **Entorno de Red:** Configuración inicial de Security Groups y reglas de entrada/salida en AWS.

### 4. Configuración de Servicios y Contenedores
* **Docker:** Instalación y configuración del entorno para la gestión de retos basados en contenedores.
* **Bases de Datos:** Creación de la base de datos MySQL para la gestión de usuarios del portal.
* **Servidor Web:** Despliegue de una web Apache básica para la validación de flags y ranking inicial.

---

## TAREAS PENDIENTES (BACKLOG)

### 1. Desarrollo de Red y Seguridad Avanzada
* **Arquitectura de Red:** Diseño y desarrollo de los esquemas de red y segmentación interna.
* **Firewall:** Despliegue y configuración de **firewall (a determinar)** para el control de tráfico entre retos.
* **Instancias:** Despliegue de las 2 instancias de AWS restantes para completar la arquitectura de 3 nodos.

### 2. Implementación Técnica de Retos
* **Orquestación:** Creación de archivos `docker-compose` con múltiples contenedores.
* **Personalización:** Configuración de Dockerfiles específicos para los diferentes niveles.

---

## RESUMEN EJECUTIVO

| Categoría | Valor |
| :--- | :--- |
| **Tareas totales identificadas** | 25 |
| **Tareas completadas** | 18 |
| **Porcentaje de avance** | 72% |
| **Infraestructura Cloud** | Operativa (Fase 1 de 3) |

**Estado del proyecto:** El Sprint 1 se ha cerrado con éxito en cuanto a la base del sistema. El equipo ha establecido el motor de contenedores y la base de datos inicial. El siguiente foco será la segmentación crítica de red y el despliegue de la lógica de juego.

---

## ESTADO ACTUAL

* **Funciona:**
    * Instancia AWS accesible y segura vía SSH.
    * Entorno Docker instalado y listo para el despliegue de retos.
    * Base de datos de usuarios y servidor Apache inicializados.
    * Plan de niveles y arquitectura conceptual definida.

* **Pendiente:**
    * Configuración detallada de reglas de firewall.
    * Documentar riesgos laborales
    * Agregar dominio **xploit.cat** a infraestructura
    * Docuementar todo el trabajo hecho

---

**Acta redactada:** 27/04/2026
**Responsable:** Equipo Xploit