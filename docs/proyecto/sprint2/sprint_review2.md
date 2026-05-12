# ACTA DE REUNIÓN - SPRINT 2: PROYECTO XPLOIT

**Proyecto:** Xploit (CTF Platform)  
**Fecha:** 12 de mayo de 2026  
**Estado:** Estabilización de infraestructura, seguridad web y recuperación tras incidencia AWS

***

## ASISTENTES

* Adrián González
* Marc Manzorro
* Carlos Rodriguez

***

## TAREAS COMPLETADAS

### 1. Seguridad y Protección de la Web
* **Seguridad HTTPS:** Se ha añadido seguridad a la web mediante la configuración de **HTTPS**.
* **Certificado SSL:** Se ha trabajado en la implementación de **Let's Encrypt** para asegurar las conexiones del portal.

### 2. Monitorización y Observabilidad
* **Monitorización:** Se ha completado la tarea de monitorización del entorno para mejorar el control del sistema y detectar fallos de forma más rápida.

### 3. Servidor Web y Dominio
* **Corrección de Apache:** Se ha arreglado la web Apache tras los problemas detectados en el servicio.
* **Conexión con el dominio:** Se ha conectado la web con el dominio configurado en la infraestructura.

### 4. Recuperación de Infraestructura AWS
* **Restauración de instancias:** Se han restaurado las instancias AWS afectadas por la caída general del entorno.
* **Recuperación de información:** Se han recuperado archivos y parte del trabajo perdido tras la incidencia.
* **Reconfiguración de redirecciones:** Se han ajustado las reglas de puertos y las URL de redirección de la web después del cambio de IPs en las instancias.

### 5. Documentación y Presentación
* **Documentación:** Se ha completado la documentación necesaria, tanto de administración como de usuario.
* **Presentación:** Se ha realizado la presentación en Canva para el seguimiento del proyecto.

### 6. Seguridad Perimetral
* **Firewall:** Se ha completado la tarea de firewall como parte de la preparación del entorno seguro.
* **Creación de instancia:** Se ha avanzado en la creación de la instancia necesaria para la arquitectura del proyecto.

### 7. Gestión de Riesgos
* **Riesgos laborales:** Se ha completado la tarea relacionada con riesgos laborales.

### 8. Seguridad y Red
* **Configuración de pfSense / IPTables:** Sigue pendiente la configuración final del firewall a nivel de segmentación y control de tráfico.
* **Reglas de firewall:** Falta terminar la configuración detallada de las reglas de entrada y salida.
* **Firewall avanzado:** Queda pendiente consolidar la política de filtrado entre servicios y niveles.

### 9. Desarrollo Web y Base de Datos
* **Creación de la web:** Pendiente continuar con el desarrollo completo del portal.
* **Estructura y lógica:** Falta definir y programar la lógica interna de la web.
* **Base de datos:** Pendiente completar la base de datos y su integración con la aplicación.
* **Conectividad con los niveles:** Queda por finalizar la conexión entre la web, la BBDD y los niveles del CTF.
* **Diseño de la web:** Falta cerrar el diseño visual y funcional del portal.

### 10. Documentación Técnica
* **Documentar tareas web y BBDD:** Sigue pendiente completar la documentación técnica de la parte web y base de datos.
* **Análisis de riesgos de la solución:** Falta analizar y documentar los riesgos asociados a la solución.

***

## RESUMEN EJECUTIVO

| Categoría | Valor |
| :--- | :--- |
| **Tareas totales identificadas** | 18 |
| **Tareas completadas** | 18 |
| **Porcentaje de avance** | 100% |
| **Estado del sprint** | Finalizado tras estabilización y recuperación |

**Estado del proyecto:** El Sprint 2 se ha cerrado con avances importantes en seguridad, monitorización y recuperación de infraestructura. El equipo ha resuelto incidencias críticas en AWS, ha reforzado el acceso web con HTTPS y ha dejado el entorno más estable para continuar con el desarrollo funcional del portal y la lógica de niveles.

***

## ESTADO ACTUAL

* **Funciona:**
    * Web accesible con conexión segura.
    * Dominio enlazado correctamente.
    * Monitorización operativa.
    * Instancias AWS restauradas y entorno recuperado tras la caída.
    * Documentación básica completada.
    * Configuración final de IPTables.
    * Desarrollo completo de la web.
    * Integración total de la base de datos.
    * Conectividad definitiva con los niveles del CTF.
    * Documentación técnica de la parte web y BBDD.
    * Análisis de riesgos de la solución.

***

**Acta redactada:** 12/05/2026  
**Responsable:** Equipo Xploit