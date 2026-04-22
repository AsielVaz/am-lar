# AM+

Sistema de gestion empresarial desarrollado en Laravel para la administracion de empresas, documentacion fiscal y corporativa, socios independientes y control de usuarios por rol.

## Descripcion

AM+ esta pensado para centralizar la consulta y administracion de informacion operativa y documental de multiples empresas en una sola interfaz.

El sistema permite:

- administrar empresas con datos generales, estatus, prioridad, logo y datos operativos
- capturar y consultar documentacion corporativa y fiscal por empresa
- administrar socios como personas independientes con su propio CRUD
- relacionar uno o varios socios con una o varias empresas mediante tabla pivote
- consultar el directorio empresarial desde un dashboard visual tipo glass UI
- controlar acceso por roles: `administrador`, `capturista` y `usuario`

## Modulos principales

### Consulta

- vista principal del sistema
- muestra todas las empresas agrupadas alfabeticamente
- permite filtrar por `activa`, `inactiva` e `inerte`
- permite buscar empresas por nombre, RFC, direccion o correo

### Empresas

CRUD completo para empresas con:

- nombre
- RFC
- direccion
- codigo postal
- estatus
- prioridad
- logo
- telefono
- correo
- sitio web
- fin del dominio web
- contrasena IOFacturo

Tambien incluye:

- carga de documentos legales y fiscales
- vigencia de `32D`
- vigencia de `comprobante de domicilio`
- asignacion de socios existentes a la empresa

### Socios

CRUD completo para socios como entidad independiente con:

- puesto
- nombre
- direccion
- RFC
- contrasena
- INE en PDF
- CSF en PDF
- certificado `.cer`
- llave `.key`

Cada socio puede relacionarse con una o varias empresas.

### Usuarios

CRUD completo de usuarios sobre la tabla estandar de Laravel.

Roles disponibles:

- `Administrador`: acceso total
- `Capturista`: puede gestionar documentos y socios, con restricciones en datos generales de empresa
- `Usuario`: acceso de consulta

## Stack tecnico

- PHP `8.3+`
- Laravel `13`
- Blade
- CSS personalizado con interfaz glass
- MySQL o motor compatible con Laravel

## Estructura funcional relevante

- `app/Http/Controllers/EmpresaController.php`
- `app/Http/Controllers/SocioController.php`
- `app/Http/Controllers/UserController.php`
- `app/Models/Empresa.php`
- `app/Models/Socio.php`
- `app/Models/User.php`
- `resources/views/dashboard`
- `resources/views/empresas`
- `resources/views/socios`
- `resources/views/users`

## Instalacion local

1. Instalar dependencias de PHP:

```bash
composer install
```

2. Crear archivo de entorno:

```bash
cp .env.example .env
```

3. Generar llave de aplicacion:

```bash
php artisan key:generate
```

4. Configurar base de datos en `.env`

5. Ejecutar migraciones:

```bash
php artisan migrate
```

6. Si quieres sembrar el usuario base configurado en el proyecto:

```bash
php artisan db:seed --force
```

7. Levantar el proyecto:

```bash
php artisan serve
```

## Despliegue

Puntos importantes para despliegue:

- configurar correctamente `APP_URL`
- asegurar permisos de escritura en `storage/` y `bootstrap/cache/`
- si se usan archivos subidos por disco `public`, revisar la publicacion de `storage`
- limpiar cache despues de cada despliegue:

```bash
php artisan optimize:clear
```

## Archivos y documentos

El sistema almacena documentos empresariales y de socios en el disco `public` de Laravel.

Tipos usados actualmente:

- logos: `.jpg`, `.jpeg`, `.png`, `.webp`
- documentos: `.pdf`
- certificados: `.cer`
- llaves: `.key`

## Estado del proyecto

AM+ actualmente cuenta con:

- autenticacion con login y recordarme
- control de acceso por rol
- CRUD de empresas
- CRUD de socios
- CRUD de usuarios
- relacion many-to-many entre empresas y socios
- dashboard de consulta visual
- estilos personalizados tipo liquid glass

## Creditos

Proyecto desarrollado y firmado por:

- HoppingJet
- Edworld
