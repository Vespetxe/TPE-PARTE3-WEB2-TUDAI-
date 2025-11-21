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

