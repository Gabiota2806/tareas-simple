---
globs:
  - resources/views/**
---

# Interfaz

## Heroicons SVG, nunca emojis

Los íconos van como SVG de Heroicons inline, con clases de Tailwind. Está
prohibido usar emojis o caracteres tipográficos como sustituto (⚠️, ✓, →). Es un
criterio explícito de varias HU y se revisa en las PRs.

Si te cruzás con un emoji en un bloque que estás editando por otro motivo,
cambialo: es deuda que se paga barata de a poco.

## Selects

Usá el componente `<x-select>` en vez de `<select>` nativo. Para listas largas
con búsqueda existe `<x-searchable-select>`.

Quedan unos pocos selects nativos sin migrar; si tocás uno de esos bloques,
convertilo.

## Verificá el balance de directivas después de editar

Blade no avisa si dejaste un `@if` sin `@endif`. Después de editar una vista,
contá que `@if(`, `@foreach(`, `@forelse(`, `@php` coincidan con `@endif`,
`@endforeach`, `@endforelse`, `@endphp`.

## No edites bloques largos por partes

Para borrar o reemplazar un bloque grande de Blade o PHP, leé el rango completo y
reemplazalo de una sola vez. Editarlo por pedazos deja el archivo a medio camino
—ya pasó, con un `@if(false)` envolviendo código muerto que sobrevivió a dos
intentos de arreglo—. Si el bloque es muy grande, `sed -i 'INICIO,FINd' archivo`
es más seguro que varias ediciones parciales.

## Controles que no hacen nada

Si una opción de la interfaz deja de tener efecto según el contexto, ocultala y
explicá por qué, en vez de dejarla visible e inerte. Ejemplo: el checkbox de
"descontar de caja chica" solo aparece cuando el medio de pago mueve efectivo.
