# BovWeight CR

Proyecto Laravel para el curso IF7100 - Ingenieria del Software I de la UCR. El dominio del sistema modela un escenario de estimacion de peso bovino sobre Rancho, Animal y RegistroPeso, y el laboratorio documenta la aplicacion de los patrones Factory Method, Repository, Observer y Strategy.

## Contexto Del Problema

Antes de aplicar patrones, el codigo presentaba estos dolores de mantenimiento:

| Patron | Problema sin patron | Sintoma observable | Consecuencia a largo plazo |
| --- | --- | --- | --- |
| Factory | `new Brahman()` y `new Nelore()` dispersos | Cambiar la creacion obliga a tocar varios clientes | Agregar razas nuevas rompe muchos puntos |
| Repository | Consultas Eloquent mezcladas con servicios | Cambiar ORM o agregar cache obliga a reescribir logica | La capa de negocio queda acoplada a persistencia |
| Observer | El registro de peso llama directamente a varios efectos secundarios | Cada nuevo suscriptor obliga a abrir el flujo central | El metodo crece y se acopla con subsistemas externos |
| Strategy | Los algoritmos de estimacion viven en `if/elseif` | Agregar un algoritmo obliga a abrir el servicio orquestador | Se mezcla seleccion de algoritmo con flujo de negocio |

## Base Del Proyecto

- Base de datos: SQLite configurada en `.env`.
- Archivo de base: `database/database.sqlite`.
- Migraciones del dominio ejecutadas para:
	- `ranchos`
	- `animals`
	- `registro_pesos`

## Modelo De Dominio

- Rancho: nombre, ubicacion, propietario.
- Animal: arete unico, rancho, raza, sexo, fecha de nacimiento.
- RegistroPeso: animal, peso, confianza, metodo usado, fecha de registro.

Relaciones:

- Rancho tiene muchos Animal.
- Animal pertenece a Rancho y tiene muchos RegistroPeso.
- RegistroPeso pertenece a Animal.

## Patron 1 - Factory Method

Problema resuelto:

- La creacion de razas ya no queda dispersa en controladores ni servicios.
- El cliente depende de `IRazaFactory` y no de `new Brahman()` o `new Nelore()`.

Implementacion:

- Interfaz: `app/Domain/Factories/IRazaFactory.php`
- Fabrica concreta: `app/Domain/Factories/RazaFactory.php`
- Producto abstracto: `app/Domain/Models/Raza.php`
- Productos concretos:
	- `app/Domain/Models/Brahman.php`
	- `app/Domain/Models/Nelore.php`
- Registro en contenedor: `app/Providers/AppServiceProvider.php`

Puntos de uso refactorizados:

- `app/Http/Controllers/RazaTestController.php`
- `app/Http/Controllers/AnimalSeederController.php`

Como se prueba:

- Ruta: `/probar-razas`
- Devuelve datos de Brahman y Nelore creados a traves de la factory.

Extensibilidad:

- Para agregar Angus se crea `Angus.php` y se agrega una linea al arreglo interno de `RazaFactory`.

## Patron 2 - Repository

Problema resuelto:

- Los servicios ya no dependen de consultas Eloquent embebidas.
- La persistencia queda encapsulada detras de una interfaz de dominio.

Implementacion:

- Interfaz: `app/Domain/Repositories/IAnimalRepository.php`
- Implementacion Eloquent: `app/Infrastructure/Persistence/EloquentAnimalRepository.php`
- Implementacion en memoria: `app/Infrastructure/Persistence/InMemoryAnimalRepository.php`
- Binding del contenedor: `app/Providers/AppServiceProvider.php`
- Consumidor principal: `app/Services/ReporteService.php`

Como se prueba:

- Ruta: `/sembrar-animales`
- Ruta: `/reporte-rancho/{ranchoId}`

Resultado observado:

- La siembra crea un rancho y tres animales.
- El reporte usa solo `IAnimalRepository` para obtener animales por rancho y calcular peso promedio del ultimo registro.

## Patron 3 - Observer

Problema resuelto:

- El registro de peso ya no conoce directamente a todos los receptores de efectos secundarios.
- Agregar un nuevo observador no obliga a cambiar el sujeto.

Implementacion:

- Interfaz: `app/Domain/Observers/IRegistroPesoObserver.php`
- Subject: `app/Domain/Observers/RegistroPesoSubject.php`
- Observadores:
	- `app/Domain/Observers/NotificadorPropietario.php`
	- `app/Domain/Observers/RecalculadorICC.php`
	- `app/Domain/Observers/WebhookSenasa.php`
	- `app/Domain/Observers/AlertaSMS.php`
- Servicio de orquestacion: `app/Services/RegistroPesoService.php`
- Controlador: `app/Http/Controllers/RegistroPesoController.php`
- Test unitario: `tests/Unit/RegistroPesoSubjectTest.php`

Como se prueba:

- Test: `php artisan test --filter=RegistroPesoSubjectTest`
- Ruta: `/registrar-peso`

Evidencia en log:

- Email al propietario.
- Recalculo de ICC.
- Webhook a SENASA.
- SMS de alerta.

Demostracion de Open/Closed:

- `AlertaSMS` se agrego sin modificar `RegistroPesoSubject` ni los observadores previos.

## Patron 4 - Strategy

Problema resuelto:

- La seleccion del algoritmo ya no vive en `if/elseif` dentro del servicio estimador.
- El contexto delega a una estrategia intercambiable en tiempo de ejecucion.

Implementacion:

- Value object inmutable: `app/Domain/ValueObjects/ResultadoEstimacion.php`
- Interfaz: `app/Domain/Strategies/IAlgoritmoEstimacion.php`
- Excepcion de dominio: `app/Domain/Exceptions/ServicioYolov8NoDisponibleException.php`
- Estrategias concretas:
	- `app/Domain/Strategies/AlgoritmoYolov8.php`
	- `app/Domain/Strategies/AlgoritmoRegresionLineal.php`
	- `app/Domain/Strategies/AlgoritmoTablaReferencia.php`
- Contexto: `app/Services/EstimadorPesoService.php`
- Controlador: `app/Http/Controllers/EstimacionController.php`

Como se prueba:

- `/estimar/yolov8`
- `/estimar/regresion`
- `/estimar/tabla`
- `/estimar/fallback`

Simulacion realista de YOLOv8:

- Valida `imagen_url`.
- Simula llamada HTTP a un endpoint configurable.
- Simula latencia de red.
- Calcula peso y confianza pseudo-deterministicos a partir de `crc32(imagen_url)`.
- Registra logs de llamada, latencia y resultado.

Cambio de estrategia en runtime:

- `probarFallback()` intenta YOLOv8.
- Si el algoritmo lanza `ServicioYolov8NoDisponibleException`, se cambia a `AlgoritmoTablaReferencia` usando el mismo `EstimadorPesoService`.

## Evidencia De Ejecucion

### Factory

- `GET /probar-razas`
- Devuelve Brahman y Nelore creados por `IRazaFactory`.

### Repository

- `GET /sembrar-animales`
```json
{
	"mensaje": "Datos de prueba creados correctamente.",
	"rancho_id": 1,
	"animales_creados": 3
}
```

- `GET /reporte-rancho/1`
```json
{
	"rancho_id": 1,
	"cantidad_animales": 3,
	"peso_promedio_ultimo_registro_kg": 394.75
}
```

### Observer

- `GET /registrar-peso`
```json
{
	"mensaje": "Registro de peso creado y observadores notificados."
}
```

- Log esperado:
	- `Email enviado al propietario del animal BWCR-1-001`
	- `ICC recalculado para animal BWCR-1-001, peso 480 kg`
	- `Webhook SENASA disparado para animal BWCR-1-001`
	- `SMS de alerta enviado por peso de 480 kg`

### Strategy

- `GET /estimar/yolov8`
```json
{
	"pesoKg": 389.09,
	"confianzaPorcentaje": 91.99,
	"metodoUsado": "yolov8"
}
```

- `GET /estimar/regresion`
```json
{
	"pesoKg": 482,
	"confianzaPorcentaje": 78,
	"metodoUsado": "regresion_lineal"
}
```

- `GET /estimar/tabla`
```json
{
	"pesoKg": 450,
	"confianzaPorcentaje": 60,
	"metodoUsado": "tabla_referencia"
}
```

- `GET /estimar/fallback`
```json
{
	"fallback_aplicado": true,
	"resultado": {
		"pesoKg": 450,
		"confianzaPorcentaje": 60,
		"metodoUsado": "tabla_referencia"
	}
}
```

Logs observados de YOLOv8:

- Llamada al endpoint simulado.
- Latencia simulada.
- Resultado pseudo-deterministico.
- Warning del fallback cuando el servicio se marca como no disponible.

## Estructura Relevante

```text
app/
	Domain/
		Exceptions/
		Factories/
		Models/
		Observers/
		Repositories/
		Strategies/
		ValueObjects/
	Http/Controllers/
	Infrastructure/Persistence/
	Models/
	Providers/
	Services/
```

## Comandos Utiles

```bash
php artisan migrate
php artisan test --filter=RegistroPesoSubjectTest
php artisan serve
```

## Estado De Entrega

- SQLite configurado y operativo.
- Migraciones del dominio ejecutadas.
- Factory, Repository, Observer y Strategy implementados.
- Validaciones manuales y pruebas ejecutadas durante la implementacion.
