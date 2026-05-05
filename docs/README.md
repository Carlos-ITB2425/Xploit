# XPLOIT

Plataforma de entrenamiento en ciberseguridad estilo CTF, con retos progresivos, validación de flags, ranking competitivo y documentación técnica del proyecto.

## Qué es XPLOIT

XPLOIT es una plataforma de entrenamiento en ciberseguridad diseñada como un CTF progresivo. El proyecto combina retos web, retos Linux, validación de flags, ranking competitivo y una arquitectura preparada para desplegarse en AWS con contenedores Docker y segmentación de red.

## Qué incluye el proyecto

- Guía de usuario de la plataforma.
- Guía de administración del entorno.
- Arquitectura general del sistema.
- Documentación de AWS, red, seguridad y hardening.
- Documentación de la web principal, backend, base de datos y ranking.
- Documentación de los retos Apache, MySQL/SQLi y Linux/LXC.
- Recursos gráficos, capturas, diagramas y material de apoyo.
- Planificación y memoria técnica del proyecto.

## Estructura del repositorio

```text
XPLOIT/
├── README.md
├── docs/
│   ├── 00-indice.md
│   ├── 01-guia-usuario.md
│   ├── 02-guia-admin/
│   │   ├── 01-guia-admin.md
│   │   └── 02-configuracion-admin.md
│   ├── 03-arquitectura/
│   │   ├── arquitectura-general.md
│   │   ├── red-y-seguridad.md
│   │   ├── aws.md
│   │   └── hardening.md
│   ├── 04-plataforma/
│   │   ├── frontend.md
│   │   ├── backend.md
│   │   ├── base-datos.md
│   │   ├── flags-y-ranking.md
│   │   └── despliegue.md
│   ├── 05-retos/
│   │   ├── apache.md
│   │   ├── mysql-sqli.md
│   │   ├── alpine-lxc.md
│   │   └── pistas-y-soluciones.md
│   ├── 06-operacion/
│   │   ├── monitorizacion.md
│   │   ├── backups.md
│   │   ├── mantenimiento.md
│   │   └── incidencias.md
│   └── 07-proyecto/
│       ├── sprint-01.md
│       ├── sprint-02.md
│       ├── sprint-review.md
│       └── tecnologias.md
├── assets/
│   ├── images/
│   ├── diagrams/
│   └── screenshots/
├── src/
│   ├── web/
│   ├── api/
│   ├── challenges/
│   └── db/
└── infra/
    ├── aws/
    ├── docker/
    └── pfsense/
```

## Documentación principal

- `docs/00-indice.md`: índice general de la documentación.
- `docs/01-guia-usuario.md`: guía para estudiantes.
- `docs/02-guia-admin/`: guía de administración y configuración.
- `docs/03-arquitectura/`: red, AWS, seguridad y hardening.
- `docs/04-plataforma/`: web, backend, base de datos, flags y despliegue.
- `docs/05-retos/`: descripción técnica de los retos.
- `docs/06-operacion/`: monitorización, backups e incidencias.
- `docs/07-proyecto/`: sprints, decisiones y tecnologías usadas.

## Cómo usar el repositorio

1. Leer primero `README.md`.
2. Consultar `docs/00-indice.md` para ir a la sección necesaria.
3. Revisar `docs/01-guia-usuario.md` para la experiencia del alumno.
4. Entrar en `docs/02-guia-admin/` para tareas de administración.
5. Consultar `docs/05-retos/` para ver la documentación de cada desafío.
6. Usar `infra/` y `src/` para despliegue y mantenimiento técnico.

## Estructura técnica

La plataforma está pensada para mantener separados el código, la infraestructura y la documentación. Esa separación facilita la evolución del proyecto, el mantenimiento del entorno y la colaboración entre desarrollo, administración y documentación.

## Autoría

Proyecto desarrollado por:

- Adrián González
- Marc Manzorro
- Carlos Rodríguez

Curso: **ASIXc2A**  
Centro: **Institut Tecnològic de Barcelona**  
Curso académico: **2025/26**