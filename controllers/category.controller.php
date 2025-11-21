<?php

    require_once './models/category.model.php';
    require_once './models/item.model.php';

    class CategoryController {
        private $model;
        private $itemModel;

        public function __construct()
        {
            $this->model = new CategoryModel();
            $this->itemModel = new ItemModel();
        }

        public function getCategories($req, $res) {
            $categories = $this->model->getCategories();


            // respondo con 200 OK
            return $res->json($categories, 200);
        }
        
        public function getCategory($req, $res){
            $id= $req->params->id;

            $category = $this->model->getCategory($id);
        
            if (!$category) {
                return $res->json("La categoria con el id=$id no existe", 404);
            }

            return $res->json($category,200);
        }

        public function deleteCategory($req, $res) {
            $id = $req->params->id;
            $category = $this->model->getCategory($id);
        
            if (!$category) {
                return $res->json("La categoria con el id=$id no existe", 404);
            }

            $this->model->removeCategory($id);

            return $res->json("La categoria con el id=$id se eliminó", 204);
        }

        public function insertCategory($req, $res) {
        // Valida que vengan todos los datos necesarios en el body
        // Si falta alguno, devolvemos un error 400 (Bad Request)
        if (!isset($req->body->nombre) || !isset($req->body->descripcion) || !isset($req->body->responsable)) {
            return $res->json('Faltan datos', 400);
        }

        // guarda los datos del body en variables locales (solo para mayor claridad)
        $nombre = $req->body->nombre;
        $descripcion = $req->body->descripcion;
        $responsable = $req->body->responsable;

        // inserta la nueva categoria
        $newCategoryId = $this->model->insertCategory($nombre, $descripcion, $responsable);

        // si el modelo devuelve false, algo falló al guardar (por ejemplo, error en la base de datos)
        if ($newCategoryId == false) {
            return $res->json('Error del servidor', 500);
        }

        // se considera una buena práctica devolver la entidad creada que contiene
        // todos los datos que el modelo agregó automaticamente
        $newCategory = $this->model->getCategory($newCategoryId);
        return $res->json($newCategory, 201); 
    }

        public function updateCategory($req, $res) {
        $id = $req->params->id;
        $category = $this->model->getCategory($id);
    
        if (!$category) {
            return $res->json("La categoria con el id=$id no existe", 404);
        }

        if (!isset($req->body->nombre) || 
            !isset($req->body->descripcion) || 
            !isset($req->body->responsable) 
        ) {
            // En una petición PUT se deben enviar todos los campos de la tarea.
            // Si solo queremos modificar algunos, el método correcto sería PATCH.
            return $res->json('Faltan datos', 400);
        }

        $nombre = $req->body->nombre;
        $descripcion = $req->body->descripcion;
        $responsable = $req->body->responsable;

        $this->model->updateCategory($id, $nombre, $descripcion, $responsable);

        $updatedCategory = $this->model->getCategory($id);
        return $res->json($updatedCategory, 201); 
    }


    }

     
?>