# Sistema de Gestión de Proyectos — Tech Solutions Group

Evaluación sumativa Unidad 1 (CRUD con Framework), Unidad 2 (Base de Datos, ORM y Autenticación) y Unidad 3 (API REST) — Laravel 11 / PHP 8.3

Aplicación web para la gestión de proyectos de Tech Solutions. La Unidad 1 practicó los conceptos fundamentales de un framework MVC (rutas, controladores, modelos, vistas, componentes reutilizables) con datos en sesión. La Unidad 2 evolucionó el proyecto incorporando base de datos real, ORM (Eloquent) y un módulo de registro/inicio de sesión con cifrado de contraseña. La Unidad 3 agrega una API REST que expone el CRUD de proyectos en formato JSON.

## Contenido

- [Descripción](#descripción)
- [Tecnologías](#tecnologías)
- [Instalación](#instalación)
- [API REST de proyectos (Unidad 3)](#api-rest-de-proyectos-unidad-3)
- [Módulo de autenticación (Unidad 2)](#módulo-de-autenticación-unidad-2)
- [Rutas disponibles](#rutas-disponibles)
- [Arquitectura (MVC)](#arquitectura-mvc)
- [Patrones de diseño utilizados](#patrones-de-diseño-utilizados)
- [Componente reutilizable: Valor UF del día](#componente-reutilizable-valor-uf-del-día)
- [Estándares de desarrollo web aplicados](#estándares-de-desarrollo-web-aplicados)

## Descripción

La aplicación permite administrar proyectos (listar, ver, crear, actualizar y eliminar), cumpliendo los siguientes requerimientos:

- CRUD completo de proyectos (id, nombre, fecha de inicio, estado, responsable, monto, creado_por), disponible tanto en vistas web como en API REST (JSON).
- Persistencia real en base de datos MySQL mediante Eloquent ORM (Unidad 2).
- Registro e inicio de sesión de usuarios, con cifrado de la clave (bcrypt) y protección de las rutas web de proyectos mediante middleware.
- API REST de proyectos con los códigos de estado HTTP correctos (201, 200, 404, 204) y validación de campos requeridos (Unidad 3).
- Vistas con estilos básicos y mensajes de confirmación tipo pop-up.
- Componente reutilizable que consume un servicio externo (API de indicadores económicos) para mostrar el valor de la UF del día, con respaldo simulado si el servicio no está disponible.

## Tecnologías

- PHP 8.3
- Laravel 11
- Eloquent ORM + MySQL
- Blade (motor de plantillas)
- JavaScript básico (pop-ups de notificación)
- CSS propio (sin frameworks externos)

## Instalación

```bash
git clone <url-del-repositorio>
cd eva1-varas-brayan-web1
composer install
cp .env.example .env      # ya viene configurado con los datos de la Unidad 2
php artisan key:generate
```

Configura una base de datos MySQL llamada `desarrollo_software_1` (usuario `root`, clave `desarrollo_software_1`, o ajusta el `.env` según tu entorno) y luego ejecuta las migraciones:

```bash
php artisan migrate
php artisan db:seed   # crea un usuario de prueba, util para probar la API
php artisan serve
```

Luego visita `http://127.0.0.1:8000/login` para crear una cuenta y acceder al módulo de proyectos, o prueba directamente la API en `http://127.0.0.1:8000/api/proyectos` (ver sección siguiente).

> La Unidad 1 no requería base de datos (datos en sesión). Las Unidades 2 y 3 sí la requieren: revisa las variables `DB_*` en tu `.env` antes de ejecutar `php artisan migrate`.

## API REST de proyectos (Unidad 3)

Todas las rutas quedan bajo el prefijo `/api` y responden siempre en JSON (`app/Http/Controllers/Api/ProyectoApiController.php` + `routes/api.php`).

| Acción | Verbo | Ruta | Éxito | Si el id no existe |
|---|---|---|---|---|
| Listar proyectos | GET | `/api/proyectos` | `200` (arreglo, vacío `[]` si no hay datos) | — |
| Crear proyecto | POST | `/api/proyectos` | `201` + proyecto creado | — |
| Ver proyecto por id | GET | `/api/proyectos/{id}` | `200` + proyecto | `404` |
| Actualizar proyecto | PUT / PATCH | `/api/proyectos/{id}` | `200` + proyecto actualizado | `404` |
| Eliminar proyecto | DELETE | `/api/proyectos/{id}` | `204` (sin contenido) | `404` |

**Validación (POST / PUT / PATCH):** `nombre`, `fecha_inicio`, `estado`, `responsable`, `monto` y `created_by` son obligatorios y no pueden ir vacíos. `created_by` debe ser el `id` de un registro existente en `usuarios` (por eso el seeder crea uno de prueba). Si falta algún campo, Laravel responde `422` con el detalle del error.

Ejemplo con `curl` (reemplaza `created_by` por el id real del usuario de prueba, normalmente `1`):

```bash
curl -X POST http://127.0.0.1:8000/api/proyectos \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
        "nombre": "Portal de Clientes",
        "fecha_inicio": "2026-03-03",
        "estado": "En curso",
        "responsable": "Camila Rojas",
        "monto": 4500000,
        "created_by": 1
      }'
```

> Nota sobre la rúbrica: el enunciado indica "respuesta HTTP de 201" y luego "el código de respuesta debe ser 200" para la actualización (PUT/PATCH). Se implementó `200 OK` para actualizar (es el estándar REST cuando la respuesta incluye el recurso actualizado) y `201 Created` solo para la creación — que es la lectura más consistente entre ambas frases del enunciado.

## Módulo de autenticación (Unidad 2)

| Función | Archivo | Detalle |
|---|---|---|
| Registro de usuario | `AuthController::registro()` | Valida los datos, verifica correo único y crea el `Usuario`. La clave se cifra automáticamente con bcrypt gracias al cast `'clave' => 'hashed'` del modelo `Usuario`. |
| Inicio de sesión | `AuthController::login()` | Busca al usuario por correo y valida la clave con `Hash::check()`. Si es correcta, guarda `usuario_id` y `usuario_nombre` en sesión. |
| Cierre de sesión | `AuthController::logout()` | Limpia los datos de sesión del usuario. |
| Autorización | `app/Http/Middleware/EnsureUsuarioAutenticado.php` | Middleware `auth.usuario`, aplicado a todo el grupo de rutas `/proyectos`. Si no hay sesión iniciada, redirige a `/login`. |

Modelos usados por este módulo (Eloquent, tabla real en base de datos):

- **`Usuario`** (`usuarios`): `id`, `nombre`, `correo` (único), `clave` (cifrada).
- **`Proyecto`** (`proyectos`, actualizado desde la Unidad 1): `id`, `nombre`, `fecha_inicio`, `estado`, `responsable`, `monto`, `created_by` (FK a `usuarios.id`, con el id del usuario que creó el proyecto).

## Rutas disponibles

| Acción | Verbo | Ruta | Nombre | Controlador |
|---|---|---|---|---|
| Formulario de registro | GET | `/registro` | `register` | `AuthController::mostrarRegistro` |
| Registrar usuario | POST | `/registro` | `register.store` | `AuthController::registro` |
| Formulario de inicio de sesión | GET | `/login` | `login` | `AuthController::mostrarLogin` |
| Iniciar sesión | POST | `/login` | `login.attempt` | `AuthController::login` |
| Cerrar sesión | POST | `/logout` | `logout` | `AuthController::logout` |
| Listar proyectos | GET | `/proyectos` | `projects.index` | `index` |
| Formulario crear | GET | `/proyectos/crear` | `projects.create` | `create` |
| Guardar proyecto | POST | `/proyectos` | `projects.store` | `store` |
| Formulario editar | GET | `/proyectos/{id}/editar` | `projects.edit` | `edit` |
| Actualizar proyecto | PUT | `/proyectos/{id}` | `projects.update` | `update` |
| Confirmar eliminación | GET | `/proyectos/{id}/eliminar` | `projects.confirmDelete` | `confirmDelete` |
| Eliminar proyecto | DELETE | `/proyectos/{id}` | `projects.destroy` | `destroy` |
| Obtener proyecto por id | GET | `/proyectos/{id}` | `projects.show` | `show` |

protegida por el middleware `auth.usuario` (requiere sesión iniciada).

Las rutas con parámetro `{id}` están restringidas con `whereNumber()` para aceptar solo valores numéricos, evitando errores por parámetros inválidos.

## Arquitectura (MVC)

- **Modelos (`app/Models/Usuario.php`, `app/Models/Proyecto.php`)**: Eloquent ORM, respaldados por las tablas `usuarios` y `proyectos` en MySQL (ver migraciones en `database/migrations/`). `Proyecto` reemplaza al `Project.php` de la Unidad 1 (que usaba datos en sesión, sin base de datos).
- **Controladores web (`AuthController.php`, `ProjectController.php`)**: reciben las peticiones HTTP del navegador, validan los datos de entrada y coordinan la comunicación entre los modelos y las vistas Blade.
- **Controlador API (`Api/ProyectoApiController.php`)**: expone el mismo modelo `Proyecto` en formato JSON, con sus propios códigos de estado HTTP (Unidad 3). No comparte código con `ProjectController` para mantener separadas las respuestas HTML y JSON, aunque ambos operan sobre el mismo modelo y la misma base de datos.
- **Middleware (`EnsureUsuarioAutenticado.php`)**: aplica la lógica de autorización a nivel de ruta, solo en el grupo web de `/proyectos` (la API no requiere sesión iniciada).
- **Vistas (`resources/views/auth/*.blade.php`, `resources/views/projects/*.blade.php`)**: se encargan exclusivamente de la presentación, heredando una estructura común desde `layouts/app.blade.php`.

## Patrones de diseño utilizados

| Patrón | Dónde se aplica |
|---|---|
| MVC (Model-View-Controller) | Estructura general del proyecto |
| Front Controller / Router | `routes/web.php` distribuye cada petición al controlador correspondiente |
| Inyección de dependencias | `UfWidget` recibe `UfService` automáticamente vía el constructor |
| Service Layer | `UfService` aísla la lógica de consumo de la API externa |
| Component Pattern | `<x-uf-widget />`, componente Blade reutilizable en todas las vistas |
| Template Method | `layouts/app.blade.php` con `@yield`, completado por cada vista hija |

## Componente reutilizable: Valor UF del día

`app/Services/UfService.php` consulta la API pública `mindicador.cl`. Si el servicio no responde (sin conexión, timeout, error del servidor), se entrega automáticamente un valor simulado, evitando que la aplicación falle.

`app/View/Components/UfWidget.php` expone este servicio como componente Blade:

```blade
<x-uf-widget />
```

Se incluye una única vez en el layout compartido, por lo que aparece en todas las vistas del módulo sin duplicar código.

## Estándares de desarrollo web aplicados

- Verbos HTTP semánticos (GET, POST, PUT, PATCH, DELETE), tanto en las rutas web (vía `@method()`) como en la API REST.
- Protección CSRF (`@csrf`) en todos los formularios web.
- Validación server-side (`$request->validate()`) en formularios y en la API, incluyendo unicidad del correo y confirmación de clave en el registro.
- Rutas nombradas (`route()`) en lugar de URLs escritas manualmente (rutas web).
- Separación de responsabilidades entre modelo, controlador web, controlador API, vista, servicio y middleware.
- Cifrado de contraseñas con bcrypt (cast `hashed` de Eloquent) — la clave nunca se guarda ni se muestra en texto plano.
- Autorización de rutas web mediante middleware (`auth.usuario`).
- Códigos de estado HTTP correctos y semánticos en la API (201, 200, 404, 204) y respuestas siempre en JSON, incluso para errores (`shouldRenderJsonWhen` en `bootstrap/app.php`).
- Mensajes de retroalimentación al usuario (éxito / error) mediante flash messages y notificaciones tipo pop-up en la web.

---

Desarrollado por Eduardo Palma — Evaluación Sumativa Unidad 3, Desarrollo de Software Web I (Docente: Víctor Israel Cofré Farías). Basado en el trabajo grupal de la Unidad 1 (Eduardo Palma, Brayan Varas, Luis Muñoz) y en la Unidad 2 (autenticación y base de datos,esta vez realizado individual).