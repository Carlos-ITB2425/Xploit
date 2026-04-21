# Sprint Planning #1 — Proyecto Xploit

**Proyecto:** Xploit — Plataforma CTF estilo OverTheWire  
**Sprint:** #1  
**Fecha:** 13 de abril de 2026  
**Metodología:** Scrum / Agile

---

## Equipo

| Miembro | Rol |
|---|---|
| Marc Manzorro | Desarrollador |
| Carlos Rodríguez | Desarrollador |
| Adrián González | Desarrollador |

---

## Descripción del Proyecto

Xploit es una plataforma CTF (Capture The Flag) con infraestructura en la nube AWS. La plataforma permite a los usuarios conectarse, resolver retos de seguridad, validar flags y competir en un ranking de puntos. Los retos se ejecutan en contenedores Docker aislados con diferentes niveles de dificultad.

---

## Arquitectura General

```
Actor
  └── Firewall AWS
        └── Instancia AWS (Principal — Xploit)
              ├── Apache (Web)
              ├── MySQL (BBDD Usuarios)
              ├── pfSense
              └── Contenedores Docker
                    ├── Apache (Niveles CTF)
                    │     ├── Nivel 1 (Fácil)
                    │     ├── Nivel 2 (Medio)
                    │     └── Nivel 3 (Difícil)
                    ├── MySQL (Inyección SQL en portal login)
                    └── Linux Container (Búsqueda de flags)
                          ├── Nivel 1
                          ├── Nivel 2
                          └── Nivel 3
```

**Instancias AWS previstas (sprints futuros):**
- Instancia Principal (Xploit) — este sprint
- Instancia Monitorización
- Instancia Backups
- Instancia PfSense
---

## Objetivo del Sprint #1

El objetivo principal de este sprint es sentar las bases del proyecto: documentación previa, organización del equipo y despliegue de la infraestructura inicial con la estructura básica operativa.

---

## Tareas del Sprint #1

### Documentación y Planificación

- **Desarrollo de ideas** — Definición y concreción de la idea del proyecto, lluvia de ideas del equipo y toma de decisiones iniciales sobre tecnologías y arquitectura.

- **Identificar necesidades del sector productivo (estudio de mercado)** — Análisis de soluciones CTF existentes en el mercado (ej. HackTheBox, TryHackMe, OverTheWire, picoCTF), identificación de necesidades y diferenciación del proyecto. Alineado con la rúbrica RA1.

- **Sprint Planning** — Planificación del Sprint #1: definición de tareas, asignación de tiempos y organización del backlog en ProofHub.

### Infraestructura y Herramientas

- **Crear GitHub con claves SSH** — Creación del repositorio del proyecto, configuración de las claves SSH de cada miembro del equipo y definición de la estructura de ramas y convenciones de commits.

- **Montar entorno** — Creación y configuración de la instancia AWS principal, configuración de las reglas de entrada/salida del Firewall AWS, e implementación de la estructura base del proyecto: Apache (web), MySQL (BBDD usuarios), LDAP, pfSense y estructura Docker con los tres contenedores principales.

---

## Backlog — Sprints Futuros

Los siguientes elementos fueron identificados en la sesión de brainstorming y se planificarán en próximos sprints:

- Web con sistema de validación de flags y ranking de puntos (estilo JO-EL)
- Acceso SSH con usuario de mínimos privilegios (solo lectura) para leer flags
- Desarrollo de niveles CTF en contenedor Apache (Fácil / Medio / Difícil)
- Inyección SQL en portal de login (contenedor MySQL)
- Búsqueda de flags en Linux Container (Nivel 1, 2 y 3)
- Servicio de monitorización (instancia AWS independiente)
- Servicio de backups (instancia AWS independiente)
- Compra y configuración de dominio
- Joc de proves i validació del sistema (RA2)
- Documentación técnica para administrador
- Documentación para el cliente/usuario
- Defensa final del proyecto

---

## Notas de la Sesión

- Se ha realizado una sesión de brainstorming completa con definición de la arquitectura del proyecto.
- El nombre del proyecto es **Xploit**.
- La rúbrica del proyecto final (RA1–RA4) se ha tenido en cuenta para priorizar las tareas de documentación y planificación.
- Las tareas se gestionan en **ProofHub** y el código en **GitHub**.