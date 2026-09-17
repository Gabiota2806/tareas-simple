---
globs:
  - app/Livewire/**
  - resources/views/livewire/**
---

# Livewire

Dos trampas que ya costaron horas de depuración en este proyecto.

## Las computed properties se cachean dentro del request

El resultado de `getXxxProperty()` se calcula una vez por ciclo y queda cacheado.
Si el valor puede cambiar **durante** ese mismo ciclo, la propiedad va a devolver
el dato viejo.

Nos pasó con el redondeo (HU-62): un `dump()` mostraba
`roundingAdjustment = -300.0` mientras la computed property seguía devolviendo el
cálculo previo al cambio.

**Regla:** si el valor puede cambiar dentro del ciclo, escribilo como método
plano, no como computed property. En `CashShiftControl` y en el trait
`HandlesRoundingAdjustment` está resuelto así a propósito.

Si necesitás liberar una computed ya leída, `unset($this->propiedad)` la
recalcula (se usa en `PosTerminal::addPayment()` con `remainingAmount`).

## `$this->xxx` en Blade no resuelve `getXxxProperty()`

Sin el atributo `#[Computed]`, escribir `$this->miPropiedad` en la vista no llama
al accessor. O bien agregás `#[Computed]`, o pasás el valor desde `render()`.

## Métodos públicos accesibles desde el test

Los métodos públicos del componente se pueden llamar con
`$component->instance()->miMetodo()` en Pest. Los privados no: si un test
necesita datos que produce un método privado, consultá el modelo directamente en
vez de cambiar la visibilidad solo para el test.
