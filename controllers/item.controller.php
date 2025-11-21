<?php
require_once './models/item.model.php';


class ItemController {
    private $model;

    public function __construct() {
        $this->model = new ItemModel();

        // no hay vista en la API REST
    }

    public function getItems($req, $res) {
        $sort = $_GET['sort'] ?? null;
        $order = $_GET['order'] ?? 'asc';
        $disponible = $_GET['disponible'] ?? null;

        // columnas permitidas
        $columnasValidas = ['id', 'nombre', 'precio', 'material', 'disponible', 'id_categoria'];

        if ($sort && in_array($sort, $columnasValidas)) {
            $order = strtolower($order) === 'desc' ? 'DESC' : 'ASC';

            if ($disponible !== null) {
                $items = $this->model->getDisponiblesOrdenados($disponible, $sort, $order);
            } else {
                $items = $this->model->getItemsOrdenados($sort, $order);
            }
        } else {
            $items = $this->model->getItems();
        }

        return $res->json($items, 200);
    }

    public function getItem($req, $res) {
        // obtengo el ID que viene como parámetro del endpoint
        $idItem = $req->params->id;

        $item = $this->model->getItem($idItem);
        
        if (!$item) {
            return $res->json("La prenda con el id=$idItem no existe", 404);
        }

        return $res->json($item);
    }

    public function deleteItem($req, $res) {
        $idItem = $req->params->id;
        $item = $this->model->getItem($idItem);
    
        if (!$item) {
            return $res->json("La prenda con el id=$idItem no existe", 404);
        }

        $this->model->remove($idItem);

        return $res->json("La prenda con el id=$idItem se eliminó", 204);
    }

    public function insertItem($req, $res) {
        // Valida que vengan todos los datos necesarios en el body
        // Si falta alguno, devolvemos un error 400 (Bad Request)

        //QUEDE POR ACA!!!!!!!!!!!!!
        if (!isset($req->body->id_categoria) || !isset($req->body->nombre) || !isset($req->body->material) || !isset($req->body->precio) || !isset($req->body->disponible)) {
            return $res->json('Faltan datos', 400);
        }

        // guarda los datos del body en variables locales (solo para mayor claridad)
        $id_categoria = $req->body->id_categoria;
        $nombre = $req->body->nombre;
        $material = $req->body->material;
        $precio = $req->body->precio;
        $disponible = $req->body->disponible;

        // inserta la nueva tarea
        $newItemId = $this->model->insertItem($id_categoria, $nombre, $material, $precio, $disponible);

        // si el modelo devuelve false, algo falló al guardar (por ejemplo, error en la base de datos)
        if ($newItemId == false) {
            return $res->json('Error del servidor', 500);
        }

        // se considera una buena práctica devolver la entidad creada que contiene
        // todos los datos que el modelo agregó automaticamente
        $newItem = $this->model->getItem($newItemId);
        //return $res->json($newTask, 201); 
    }

    public function updateItem($req, $res) {
        $idItem = $req->params->id;
        $item = $this->model->getItem($idItem);
    
        if (!$item) {
            return $res->json("La prenda con el id=$idItem no existe", 404);
        }

        if (!isset($req->body->id_categoria) || 
            !isset($req->body->nombre) || 
            !isset($req->body->material) || 
            !isset($req->body->precio) ||
            !isset($req->body->disponible) 
        ) {
            // En una petición PUT se deben enviar todos los campos de la tarea.
            // Si solo queremos modificar algunos, el método correcto sería PATCH.
            return $res->json('Faltan datos', 400);
        }

        $id_categoria = $req->body->id_categoria;
        $nombre = $req->body->nombre;
        $material = $req->body->material;
        $precio = $req->body->precio;
        $disponible = $req->body->disponible;

        $this->model->updateItem($idItem, $id_categoria, $nombre, $material, $precio, $disponible);

        $updatedItem = $this->model->getItem($idItem);
        return $res->json($updatedItem, 201); 
    }
}
