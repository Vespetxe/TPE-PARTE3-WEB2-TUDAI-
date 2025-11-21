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
        $minPrecio = isset($_GET['minPrecio']) ? floatval($_GET['minPrecio']) : null;
        $maxPrecio = isset($_GET['maxPrecio']) ? floatval($_GET['maxPrecio']) : null;

        // columnas permitidas
        $columnasValidas = ['id', 'nombre', 'precio', 'material', 'disponible', 'id_categoria'];

        // Normalizar order
        $order = strtolower($order) === 'desc' ? 'DESC' : 'ASC';

        // Normalizar sort
        $columna = (isset($sort) && in_array($sort, $columnasValidas)) ? $sort : 'id';

        // --------------------------------------
        // 1) FILTRO POR PRECIO (cubre todos los casos)
        // --------------------------------------
        if ($minPrecio !== null || $maxPrecio !== null) {

            // CASO: ordenar también
            if ($sort && in_array($sort, $columnasValidas)) {
                $items = $this->model->getItemsByPrecioFlexible($minPrecio, $maxPrecio, $columna, $order);
            } else {
                $items = $this->model->getItemsByPrecioFlexible($minPrecio, $maxPrecio);
            }

            return $res->json($items, 200);
        }

        // --------------------------------------
        // 2) FILTRO POR DISPONIBLE
        // --------------------------------------
        if ($disponible !== null) {
            if ($sort && in_array($sort, $columnasValidas)) {
                $items = $this->model->getDisponiblesOrdenados($disponible, $columna, $order);
            } else {
                $items = $this->model->getAllDisponibles($disponible);
            }
            return $res->json($items, 200);
        }

        // --------------------------------------
        // 3) SOLO ORDENAMIENTO
        // --------------------------------------
        if ($sort && in_array($sort, $columnasValidas)) {
            $items = $this->model->getItemsOrdenados($columna, $order);
            return $res->json($items, 200);
        }

        // --------------------------------------
        // 4) NADA → listar normal
        // --------------------------------------
        $items = $this->model->getItems();
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
