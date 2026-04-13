# Stack Tecnológico — Proyecto Xploit

**Documento de Arquitectura:** Definición de Tecnologías  
**Fase:** Sprint #1  
**Objetivo:** Establecer las herramientas, lenguajes y plataformas que soportarán la infraestructura, los retos CTF y el despliegue continuo.

---

## 1. Infraestructura Cloud y Red

### Amazon Web Services (AWS)
Proveedor cloud principal donde se alojará toda la infraestructura física virtualizada.
* **Componentes:** Instancias EC2 (Cómputo), VPC (Redes Virtuales), Security Groups (Firewall de AWS).
* **Propósito:** Alojar los servidores core, firewall y arena de retos.
* **Pros:** Altamente escalable, estándar de la industria, permite aislar redes fácilmente.
* **Contras:** Riesgo de sobrecostes si no se monitoriza el uso de recursos.
* **Dependencias:** Ninguna (Capa base).

### pfSense (Virtual Appliance)
Firewall y enrutador perimetral de código abierto basado en FreeBSD.
* **Propósito:** Actuar como puerta de enlace (Gateway) aislando la red de gestión (Core) de la red expuesta (CTF Arena).
* **Pros:** Potente sistema de reglas, interfaz gráfica intuitiva, capacidad para montar VPNs en el futuro (OpenVPN/Wireguard).
* **Contras:** Requiere conocimientos avanzados de enrutamiento y NAT.
* **Dependencias:** Instancia EC2 dedicada con al menos 2 interfaces de red (WAN/LAN).

### DonDominio
Registrador de dominios.
* **Propósito:** Adquisición y gestión de las zonas DNS para el dominio `xploit.es`.
* **Pros:** Económico para el primer año, panel de gestión DNS sencillo.
* **Contras:** Soporte técnico no siempre es 24/7 en planes básicos.

---

## 2. Servidores, Web y Contenedores

### Apache HTTP Server
Servidor web robusto y probado.
* **Propósito:** Servir el portal principal (frontend) de Xploit y actuar como servidor para los retos web estilo Natas.
* **Pros:** Fácil de configurar, excelente integración con PHP y módulos de seguridad, muy documentado.
* **Dependencias:** Sistema operativo Linux.

### Docker y Docker Compose
Plataforma de contenerización.
* **Propósito:** Aislar cada nivel del CTF (web, SQLi, contenedor Linux). 
* **Pros:** Aislamiento de procesos (si un jugador rompe el reto, el servidor principal sigue intacto), fácil reseteo de niveles, portabilidad.
* **Contras:** Requiere un bastionado (hardening) estricto para evitar un "Docker Escape".
* **Dependencias:** Demonio de Docker instalado en la instancia CTF.

### Linux (Ubuntu Server 24.04 LTS / Alpine)
Sistemas operativos base.
* **Propósito:** Ubuntu Server para las instancias EC2. Alpine Linux (minilinux) para los contenedores Docker de los retos.

---

## 3. Base de Datos y Backups

### MySQL
Sistema de gestión de bases de datos relacional.
* **Propósito:** 1. Base de datos segura en la instancia Core para el registro de usuarios y ranking de puntos. 2. Base de datos aislada en contenedor para el reto de Inyección SQL.
* **Pros:** Muy maduro, gran rendimiento, estándar en la industria.

### Bash Scripts + Rsync sobre SSH (Copias Locales y en Red)
Estrategia de copias de seguridad de archivos y bases de datos.
* **Propósito:** Extraer la BBDD (`mysqldump`) y los archivos críticos de la instancia CTF, y transferirlos de forma segura a la instancia Core mediante tareas automatizadas (`cron`).
* **Pros:** No requiere servicios de terceros, control total sobre los datos, coste cero adicional.
* **Contras:** Requiere configurar claves SSH dedicadas entre instancias y gestionar la rotación/limpieza manual de copias antiguas.
* **Dependencias:** Utilidades `rsync`, `tar` y `cron`.

### AWS EBS Snapshots (Disaster Recovery)
Copias de seguridad a nivel de bloque gestionadas por AWS.
* **Propósito:** Hacer una "foto" completa de los discos de las instancias periódicamente.
* **Pros:** Restaura una máquina completa a un estado anterior en minutos si hay un fallo catastrófico o intrusión grave.

---

## 4. Monitorización y Auditoría 

### Stack ELK (Elasticsearch, Logstash, Kibana) + Beats
Plataforma avanzada de centralización de logs y monitorización de rendimiento.
* **Propósito:** 1. **Monitorización de infraestructura:** Vigilar el uso de CPU, RAM, red y estado (Up/Down) de las 3 instancias AWS mediante *Metricbeat* y *Heartbeat*.
  2. **Auditoría de seguridad (CTF):** Recolectar logs de Apache, Docker, SSH y pfSense mediante *Filebeat* para tener visibilidad total de los ataques de los jugadores y detectar anomalías.
* **Pros:** Solución de nivel empresarial (Enterprise-grade). Ofrece dashboards en tiempo real increíblemente potentes e interactivos en Kibana. Cubre las rúbricas más altas del proyecto (RA1/RA2).
* **Contras:** Es un stack pesado. Elasticsearch requiere bastante memoria RAM para funcionar con fluidez (se recomienda al menos una instancia de 4GB RAM dedicada al stack). Curva de aprendizaje alta para configurar los filtros (Grok) en Logstash.
* **Dependencias:** Instancia Core/Gestión con recursos suficientes; instalación de agentes (Beats) en las máquinas cliente.

---

## 5. Gestión del Proyecto y Control de Versiones

### GitHub (Git)
* **Propósito:** Control de versiones del código de la web, los Dockerfiles de los retos y los scripts de backup.
* **Seguridad:** Autenticación obligatoria mediante Claves SSH generadas por el equipo.

### ProofHub
* **Propósito:** Gestión de la metodología Agile (Scrum), control del backlog de sprints y seguimiento de tareas (evidencia obligatoria para la rúbrica RA1 y RA2).