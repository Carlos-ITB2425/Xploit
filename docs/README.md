# XPLOIT: Plataforma de Entrenamiento CTF Multi-Nivel

## 1. Resumen Ejecutivo
XPLOIT es un entorno de entrenamiento en ciberseguridad diseñado bajo el modelo de Capture The Flag. El proyecto despliega una infraestructura real en la nube que ofrece retos progresivos de hacking ético, cubriendo desde la explotación de sistemas Linux y redes, hasta vulnerabilidades web avanzadas e inyección de bases de datos.

La plataforma integra un sistema de ranking competitivo, validación automática de flags y una arquitectura segmentada para garantizar un entorno de aprendizaje seguro y controlado.

## 2. Arquitectura de Red y Sistemas
El proyecto se despliega sobre una infraestructura distribuida en AWS, estructurada mediante cuatro componentes principales:

* **Nodo Principal (XPLOIT):** Servidor central de gestión y retos de sistema.
* **Servicios Web y Base de Datos:** Hosting de la plataforma de usuarios mediante Apache y motor de datos PostgreSQL.
* **Seguridad y Perímetro:** Segmentación de red mediante una instancia dedicada que actúa como Firewall utilizando iptables para el control de tráfico en lugar de soluciones preconfiguradas.
* **Gestión de Red en AWS:** Configuración estricta de Security Groups para la gestión de reglas de entrada y salida.

## 3. El Desafío: Niveles y Retos
Los retos están diseñados en una escala de dificultad creciente para facilitar un aprendizaje progresivo:

* **Nivel 1 (Fácil):** Acceso SSH inicial con privilegios mínimos y búsqueda de flags en el sistema de archivos.
* **Nivel 2 (Medio):** Retos web de explotación de vulnerabilidades y técnicas de intrusión básica.
* **Nivel 3 (Difícil):** Inyección SQL avanzada en portales de login y explotación de contenedores Docker.

## 4. Índice de Documentación
Toda la documentación técnica y de gestión está organizada siguiendo la estructura del repositorio:

###  Configuración y Administración
* Guía del Administrador (Retos)
    * [Alpine (LXC)](./guias/admin/alpine.md)
    * [Apache](./guias/admin/apache.md)
    * [Base de Datos](./guias/admin/reto_bbdd.md)
* [Configuración Web](./guias/configuracion/xploit_web.md): Parámetros técnicos del servidor Apache.
* [Dominio y HTTPS](./guias/configuracion/dominio_https.md): Gestión del dominio `xploit.cat` y certificados SSL.
* [Hardening & Firewall](./guias/configuracion/firewall.md): Reglas de IpTables y endurecimiento del sistema.

###  Gestión del Proyecto (Agile)
* **Análisis Inicial:** [Estudio de Tecnologías Usadas](./proyecto/estudio_mercado_tecnologias/tecnologias_usadas.md) y [Comparativa](./proyecto/estudio_mercado_tecnologias/tecnologias_similares.md).
* **Sprint 1:** [Planificación](./proyecto/sprint1/sprint_planning1.md) y [Acta de Revisión](./proyecto/sprint1/sprint_review1.md) (13/04 - 24/04).
* **Sprint 2:** [Planificación](./proyecto/sprint2/sprint_planning2.md) y [Acta de Revisión](./proyecto/sprint2/sprint_review2.md) (27/04 - 12/05).

### Manuales de Usuario
* [Guía del Usuario](./guias/configuracion/guia_usuario.md): Instrucciones para jugadores sobre cómo acceder y validar flags.

## 5. Tecnologías Utilizadas
| Categoría | Tecnologías |
| :--- | :--- |
| Cloud | AWS (EC2, VPC, Security Groups)  |
| Contenedores | Docker (Linux Containers)  |
| Servicios Web | Apache, PHP  |
| Bases de Datos | PostgreSQL, SQLite  |
| Seguridad | iptables, SSH (Privilegios Mínimos)  |
| Monitorización | Servicio de Backups y Gestión de Logs  |


## Autoría

Proyecto desarrollado por:

- Adrián González
- Marc Manzorro
- Carlos Rodríguez


Curso: **ASIXc2A**  
Centro: **Institut Tecnològic de Barcelona**  
Curso académico: **2025/26**