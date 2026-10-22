# TaskFlow API

[![tests](https://github.com/USUARIO/taskflow-api/actions/workflows/tests.yml/badge.svg)](https://github.com/USUARIO/taskflow-api/actions/workflows/tests.yml)
![PHP](https://img.shields.io/badge/PHP-8.3-777BB4?logo=php&logoColor=white)
![Laravel](https://img.shields.io/badge/Laravel-13-FF2D20?logo=laravel&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-8-4479A1?logo=mysql&logoColor=white)
![Versión](https://img.shields.io/badge/versión-v0.1.0-informational)
![Licencia](https://img.shields.io/badge/licencia-MIT-blue)

API REST para gestionar proyectos, tareas y colaboradores.
Proyecto guiado del programa ADSO (SENA), ficha 3174404.

- **Producción:** https://3174404-07.rdsdevs.com.co/api/v1
- **Versión actual:** v0.1.0

---

## Contenido

- [¿Qué es TaskFlow API?](#qué-es-taskflow-api)
- [Tecnologías y requerimientos](#tecnologías-y-requerimientos)
- [Instalación](#instalación)
- [Variables de entorno](#variables-de-entorno)
- [Pruebas y documentación de la API](#pruebas-y-documentación-de-la-api)
- [Endpoints](#endpoints)
- [Flujo de trabajo](#flujo-de-trabajo)
- [Autor](#autor)
- [Licencia](#licencia)

---

## ¿Qué es TaskFlow API?

Una pequeña empresa necesita organizar sus proyectos internos y las tareas de sus colaboradores. Hoy el seguimiento se hace con mensajes, hojas de cálculo y notas sueltas, lo que dificulta saber qué está pendiente, quién es el responsable y cómo avanza cada proyecto.

**TaskFlow API** es el backend que resuelve ese problema: una API REST en JSON, versionada (`/api/v1`), que será consumida por una aplicación móvil desarrollada en Flutter. No incluye interfaz web.

**Capacidades principales**

- Autenticación con correo y contraseña mediante tokens (inicio y cierre de sesión).
- Gestión de usuarios, proyectos y tareas.
- Proyectos con búsqueda por nombre, filtro por estado y paginación.
- Asignación de participantes a proyectos y de responsables a tareas.
- Control de acceso por roles, permisos y reglas de autorización.
- Auditoría de las acciones relevantes.
- Pruebas automatizadas, documentación de la API y despliegue por HTTPS.

**Roles**

| Rol | Alcance |
|-----|---------|
| Administrator | Administra usuarios, proyectos y participantes; consulta todas las tareas y los registros de auditoría. |
| Collaborator | Consulta su perfil, los proyectos en los que participa y sus tareas; crea y actualiza tareas permitidas y cambia el estado de las que tiene asignadas. |

---

## Tecnologías y requerimientos

**Tecnologías**

| Tecnología | Uso |
|------------|-----|
| PHP 8.3 | Lenguaje |
| Laravel 13 | Framework |
| MySQL 8 | Base de datos |
| Laravel Sanctum | Autenticación por tokens |
| Pest | Pruebas automatizadas |
| Scramble | Documentación de la API |

**Requisitos previos**

- PHP 8.3 o superior
- Composer 2
- MySQL 8
- Git

---

## Instalación

```bash
# 1. Clonar el repositorio
git clone git@github.com:USUARIO/taskflow-api.git
cd taskflow-api

# 2. Instalar dependencias
composer install

# 3. Configurar el entorno
cp .env.example .env
php artisan key:generate

# 4. Crear la base de datos en MySQL y ajustar el .env (ver siguiente sección)

# 5. Ejecutar las migraciones
php artisan migrate

# 6. Iniciar el servidor de desarrollo
php artisan serve
```
---

La API quedará disponible en `http://127.0.0.1:8000/api/v1`.
## Modelo de datos

Diagrama entidad-relación: [docs/er.md](docs/er.md)

## Datos de prueba

```bash
php artisan migrate:fresh --seed
```

Crea un administrador (admin@taskflow.test / password), 8 colaboradores,
4 proyectos y 24 tareas. Solo para desarrollo local.

---

## Variables de entorno

La configuración sensible vive en el archivo `.env`, que **no se sube al repositorio**. Parte de `.env.example` y ajusta como mínimo:

| Variable | Ejemplo | Descripción |
|----------|---------|-------------|
| `APP_ENV` | `local` | Entorno de ejecución (`local`, `production`) |
| `APP_DEBUG` | `true` | Modo depuración (`false` en producción) |
| `APP_URL` | `http://127.0.0.1:8000` | URL base de la aplicación |
| `DB_HOST` | `127.0.0.1` | Servidor MySQL |
| `DB_PORT` | `3306` | Puerto de MySQL |
| `DB_DATABASE` | `db_taskflow_api` | Nombre de la base de datos |
| `DB_USERNAME` | `root` | Usuario de MySQL |
| `DB_PASSWORD` | — | Contraseña de MySQL |
| `DB_PREFIX` | `a07_` | Prefijo de las tablas |

> `APP_KEY` se genera con `php artisan key:generate`.

---

## Pruebas y documentación de la API

### Pruebas automatizadas

```bash
php artisan test
```

Las pruebas se ejecutan también en GitHub Actions en cada push y pull request (ver el badge `tests` al inicio).

### Documentación interactiva

Con el servidor local en marcha, la documentación generada con Scramble está en:

- http://127.0.0.1:8000/docs/api

Cada endpoint documenta método, URI, descripción, autenticación, parámetros, request, response, códigos HTTP y errores posibles.

---

## Endpoints

Todas las rutas están bajo el prefijo `/api/v1`. Las respuestas son JSON.

### Disponibles

| Método | Ruta | Descripción | Auth |
|--------|------|-------------|------|
| GET | `/api/v1/health` | Estado del servicio | No |

### Planificados

Derivados de los requerimientos funcionales; los nombres definitivos se fijarán durante el diseño y se reflejarán en la documentación de Scramble.

| Recurso | Método | Ruta | Descripción | Auth |
|---------|--------|------|-------------|------|
| Autenticación | POST | `/auth/login` | Iniciar sesión (correo y contraseña) | No |
| | POST | `/auth/logout` | Cerrar sesión e invalidar el token | Sí |
| | GET | `/auth/me` | Consultar el perfil autenticado | Sí |
| Usuarios | GET, POST | `/users` | Listar y crear usuarios | Admin |
| | GET, PUT, DELETE | `/users/{id}` | Consultar, actualizar y desactivar un usuario | Admin |
| Proyectos | GET, POST | `/projects` | Listar (paginado, `?search=`, `?status=`) y crear | Sí |
| | GET, PUT, DELETE | `/projects/{id}` | Consultar, actualizar y eliminar un proyecto | Sí |
| Participantes | GET, POST | `/projects/{id}/participants` | Consultar y asignar participantes | Sí |
| Tareas | GET, POST | `/projects/{id}/tasks` | Listar y crear tareas de un proyecto | Sí |
| | GET | `/tasks/me` | Mis tareas asignadas | Sí |
| | PUT, DELETE | `/tasks/{id}` | Actualizar y eliminar una tarea | Sí |
| | PATCH | `/tasks/{id}/status` | Cambiar el estado de una tarea | Sí |
| Auditoría | GET | `/audit-logs` | Consultar registros de auditoría | Admin |

**Valores permitidos**

| Campo | Valores |
|-------|---------|
| Estado de proyecto | `planning`, `active`, `completed`, `cancelled` |
| Estado de tarea | `pending`, `in_progress`, `completed`, `cancelled` |
| Prioridad de tarea | `low`, `medium`, `high` |

**Autenticación:** las rutas protegidas exigen el encabezado `Authorization: Bearer <token>`.

---

## Flujo de trabajo

El proyecto se gestiona con **Git Flow**, **Conventional Commits** y **versionado semántico**.

**Ramas**

| Rama | Propósito |
|------|-----------|
| `main` | Código estable en producción |
| `develop` | Integración de las funcionalidades en desarrollo |
| `feature/*` | Una rama por funcionalidad, creada desde `develop` |
| `release/*` | Preparación de una nueva versión |
| `hotfix/*` | Correcciones urgentes sobre `main` |

**Commits** (Conventional Commits)

```text
feat: agrega el endpoint de inicio de sesión
fix: corrige la validación de la fecha límite de la tarea
docs: actualiza el README
test: agrega pruebas del recurso de proyectos
```

**Versionado:** `MAJOR.MINOR.PATCH` (por ejemplo, `v0.1.0`). Los cambios de cada versión se registran en [`CHANGELOG.md`](CHANGELOG.md).

---

## Autor

**Ana Pérez** · aprendiz ADSO · [github.com/USUARIO](https://github.com/USUARIO)

---

## Licencia

Distribuido bajo la licencia MIT. Ver [`LICENSE.md`](LICENSE.md).