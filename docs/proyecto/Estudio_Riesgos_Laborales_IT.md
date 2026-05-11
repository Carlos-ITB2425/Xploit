# Estudio de Riesgos Laborales: Administración de Sistemas y Backup

## 1. Riesgos Físicos y Ergonómicos

| Riesgo | Descripción | Medidas Preventivas |
| :--- | :--- | :--- |
| **Fatiga Visual** | Esfuerzo ocular prolongado por uso de monitores y exposición a luz azul. | Realizar pausas (regla 20-20-20), ajustar brillo y usar filtros de luz azul. |
| **Trastornos Musculoesqueléticos** | Dolores de espalda, cuello y muñecas (Túnel Carpiano) por posturas estáticas. | Uso de mobiliario ergonómico, teclado/ratón adecuado y pausas activas para estiramientos. |
| **Riesgo Eléctrico** | Contacto con conexiones de equipos portátiles, cargadores o regletas. | Mantener cableado organizado y evitar la sobrecarga de enchufes. |

## 2. Riesgos Psicosociales (Gestión del Estrés)

La administración de sistemas conlleva una carga mental específica debido a la criticidad de los datos.

* **Estrés por Alta Disponibilidad:** La responsabilidad de que el servidor esté operativo 24/7.
* **Burnout (Síndrome del Quemado):** Fatiga derivada de la resolución de incidencias críticas bajo presión de tiempo.
* **Carga Cognitiva:** Análisis complejo de logs, scripts de backup y configuraciones de red.

**Medida Preventiva Implementada:** La **automatización del backup** mediante el script `auto_backup_to_storage.sh` reduce el error humano y la ansiedad del técnico al garantizar procesos autónomos.

## 3. Matriz de Evaluación de Riesgos

| Riesgo | Probabilidad | Severidad | Nivel de Riesgo |
| :--- | :--- | :--- | :--- |
| Sedentarismo | Alta | Media | **Moderado** |
| Fatiga Mental | Media | Media | **Tolerable** |
| Caída de cables | Baja | Baja | **Trivial** |
| Síndrome visual | Alta | Baja | **Moderado** |

## 4. Conclusión
Aunque el entorno de trabajo es aparentemente seguro, el mayor riesgo reside en la ergonomía y la salud mental. La implementación de herramientas de automatización y la correcta configuración del entorno físico son las mejores barreras defensivas para el trabajador.
