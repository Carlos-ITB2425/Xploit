# Estudio de Mercado y Análisis de Referentes — Proyecto Xploit

**Documento de Arquitectura:** Análisis del Sector Productivo (RA1)  
**Fase:** Sprint #1  
**Objetivo:** Identificar las necesidades del sector, analizar las soluciones CTF (Capture The Flag) existentes en el mercado y definir la propuesta de valor y características base de la plataforma Xploit.

---

## 1. Contexto y Necesidad del Sector

En el ámbito de la ciberseguridad y la educación informática, el aprendizaje práctico (Hands-on) es fundamental. Tras analizar las necesidades de formación técnica, hemos detectado que los estudiantes y profesionales requieren entornos controlados y seguros donde puedan practicar técnicas de intrusión (Pentesting), escalada de privilegios y auditoría web sin poner en riesgo infraestructuras reales. Para satisfacer esta necesidad, hemos investigado las principales alternativas del mercado.

---

## 2. Análisis del Referente Principal: OverTheWire

Una de las plataformas más reconocidas, longevas y valoradas por la comunidad de ciberseguridad es **OverTheWire** (OTW). 

![Plataforma OverTheWire](../../src/otw.png)

OverTheWire ofrece múltiples "Wargames" (juegos de guerra cibernética) orientados a enseñar conceptos de seguridad desde un nivel absolutamente básico hasta niveles avanzados. Se divide en diferentes ramas (ej. *Bandit* para Linux/SSH, *Natas* para vulnerabilidades Web).

### Ideas Fundamentales Adquiridas (Base para Xploit)
Tras un análisis técnico de la plataforma OTW, hemos extraído los siguientes pilares que formarán la arquitectura central de **Xploit**:

* **Sistema de Flags (Banderas):** La validación del éxito del usuario no se basa en un test teórico, sino en la obtención de una cadena de texto única (Flag) que demuestra que el usuario ha logrado comprometer el servicio objetivo.
* **Progresión Aislada y Escalonada:** Los retos están estructurados en niveles de dificultad incremental (Nivel 1, Nivel 2, Nivel 3). Cada nivel requiere las credenciales obtenidas en el nivel inmediatamente anterior.
* **Retos Multi-Servicio:** El aprendizaje no se limita a un solo vector. Se utilizan diferentes servicios reales para los minijuegos:
    * Búsqueda de información y explotación de permisos a través de conexiones **SSH**.
    * Auditoría y explotación de vulnerabilidades a través del servicio **Web (HTTP/HTTPS)**.
* **Accesibilidad y Despliegue:** Todo el servicio está disponible a través de una página web centralizada que sirve como punto de entrada y panel de instrucciones.

---

## 3. Análisis de Otras Alternativas del Mercado

Para asegurar que nuestra plataforma ofrece un valor real y diferenciado, hemos evaluado otras soluciones predominantes:

### HackTheBox (HTB) / TryHackMe (THM)
* **Modelo:** Plataformas comerciales y masivas. Máquinas virtuales completas que requieren conexión vía VPN para interactuar.
* **Contraste con Xploit:** HTB tiene una curva de aprendizaje inicial muy pronunciada y una infraestructura pesada. THM es más guiado pero requiere suscripciones de pago para el contenido de calidad. Xploit busca un modelo de acceso mucho más directo (sin necesidad de VPN complejas de usuario) y más enfocado a vulnerabilidades atómicas (concepto por concepto).

### Plataformas de Programación Competitiva (ej. JO-EL)
* **Modelo:** Validación de código algorítmico con un sistema de ranking en tiempo real.
* **Contraste con Xploit:** Aunque no son de ciberseguridad, Xploit adoptará su **sistema de puntuación y ranking competitivo (Leaderboard)**. Esto fomenta la gamificación y la competitividad sana, una característica que OverTheWire no posee de forma nativa.

---

## 4. Propuesta de Valor y Diferenciación de Xploit

En conclusión, **Xploit no es una simple copia de OverTheWire**. Nuestra plataforma fusiona lo mejor de los diferentes referentes del mercado:
1. Toma la simplicidad, la arquitectura de niveles y la conexión directa (SSH/Web) de **OverTheWire**.
2. Implementa la gamificación, el registro de usuarios y el ranking competitivo estilo **JO-EL**.
3. Se apoya en una infraestructura **Cloud moderna (AWS + Docker + ELK)**, garantizando que el proyecto sea escalable, auditable y fácilmente desplegable para entornos educativos o competiciones privadas.