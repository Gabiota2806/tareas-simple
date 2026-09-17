---
globs:
  - "**"
---

# Flujo de trabajo

## Una rama y una PR por TASK, no por HU

Cada TASK del backlog va en su propia rama, sale de `develop` y se revisa sola.

De ahí se desprende algo importante: **cada task tiene que quedar en un estado
consistente por sí misma**. Si una task deja algo a medias, así llega a
`develop`. Cuando el ticket pide algo que no se puede cumplir sin tocar un
archivo que no figura en su lista, se toca igual y se aclara en la descripción de
la PR.

Antes de empezar una task, verificá si depende de otra que todavía no se
mergeó. Si depende, la rama sale de esa otra rama, no de `develop`.

## El backlog vive en el repo

`docs/jira_backlog_and_sprints.md` tiene las HU y tasks con sus criterios de
aceptación. Consultalo antes de pedir contexto.

Para el **estado** de un ticket —sprint activo, en curso, asignado a quién— ese
documento no sirve: eso se lee de Jira con `scripts/jira.sh` (ver `jira.md`).

Ojo: los tickets a veces describen mal el estado del código —piden refactorizar
`stripos` donde no hay ninguno, listan archivos incompletos, o asumen que existe
una columna que no está—. Verificá el código antes de aceptar la premisa.

## Tests

Toda task lleva su suite en Pest. Antes de abrir la PR, corré la suite completa,
no solo el filtro de lo nuevo: los refactores de modelos y helpers rompen tests
lejanos con frecuencia.

Después de tocar PHP, pasá Pint con `--dirty` para formatear solo lo de la rama.

## Documentación

`docs/database_dictionary.md` y `docs/api_contract.md` son documentos vivos. Si
tu cambio agrega una columna o define una regla de comportamiento que atraviesa
varios componentes, actualizalos en el mismo commit.
