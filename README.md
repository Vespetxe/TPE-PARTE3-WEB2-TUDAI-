IMPORTANTE: Tuve problemas para poder acceder a mi APIrest con Postman (supongo porque uso macOS).
Pude hacerlo desde THUNDER CLIENT, el plug in de Visual Studio Code.

PANPOX REST API
Este repositorio contiene una API REST simple para gestionar un proyecto de ropa intervenida.

Qué hay en este proyecto


router.php - Entry point para los endpoints de la API.

controllers/ - Controladores, por ejemplo category.controller.php.

models/ - Modelos, por ejemplo item.model.php.

libs/route/ - Librería ligera de ruteo usada por este proyecto.

db_tienda_panpox.sql - Script SQL para crear la base de datos y tablas iniciales.

.htaccess: reglas apache para soportar URL semánticas


Librería de ruteo
Este proyecto usa una librería interna para rutear peticiones ubicada en libs/route/.

Endpoints

GET /categorias — listar categorias

GET /categorias/:id — ver una categoria

POST /categorias - agregar una categoria

PUT /categorias/:id - editar una categoria


GET /prendas - listar prendas

GET /prendas/:id - ver una prenda

PUT /prendas/:id - editar una prenda

POST /prendas - agregar una prenda

Se puede obtener Ordenamiento con el endpoint /prendas

por ej: /prendas?sort=precio&order=asc

        /prendas?sort=precio&order=desc