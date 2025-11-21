<?php

class ItemModel {
    private $db;

    function __construct() {
     // 1. abro conexión con la DB
     $this->db = new PDO('mysql:host=localhost;dbname=db_tienda_panpox;charset=utf8', 'root', '');
    }

    public function getItem($id) {
        $query = $this->db->prepare('SELECT * FROM prenda WHERE id = ?');
        $query->execute([$id]);
        $item = $query->fetch(PDO::FETCH_OBJ);

        return $item;
    }
    
    public function getItemsOrdenados($columna, $orden) {
        $sql = "SELECT * FROM prenda ORDER BY $columna $orden";
        $query = $this->db->prepare($sql);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_OBJ);
    }
    public function getItemsByPrecioFlexible($min = null, $max = null, $columna = 'id', $orden = 'ASC') {
        $sql = "SELECT * FROM prenda WHERE 1";
        $params = [];

        if ($min !== null) {
            $sql .= " AND precio >= ?";
            $params[] = $min;
        }

        if ($max !== null) {
            $sql .= " AND precio <= ?";
            $params[] = $max;
        }

        $sql .= " ORDER BY $columna $orden";

        $query = $this->db->prepare($sql);
        $query->execute($params);

        return $query->fetchAll(PDO::FETCH_OBJ);
    }
    public function getDisponiblesOrdenados($disponible, $columna, $orden) {
        $sql = "SELECT * FROM prenda WHERE disponible = ? ORDER BY $columna $orden";
        $query = $this->db->prepare($sql);
        $query->execute([$disponible]);
        return $query->fetchAll(PDO::FETCH_OBJ);
    }
    public function getItemsByPrecio($min, $max, $columna = 'id', $orden = 'ASC') {
        $sql = "SELECT * FROM prenda WHERE precio BETWEEN ? AND ? ORDER BY $columna $orden";
        $query = $this->db->prepare($sql);
        $query->execute([$min, $max]);
        return $query->fetchAll(PDO::FETCH_OBJ);
    }
    
    public function getItems() {
        // 2. ejecuto la consulta 
        $query = $this->db->prepare('SELECT * FROM prenda');
        $query->execute([]);

        // 3. obtengo los resultados de la consulta
        $items = $query->fetchAll(PDO::FETCH_OBJ);

        return $items;
    }

    public function getAllDisponibles($disponible = true) {
        $query = $this->db->prepare('SELECT * FROM prenda WHERE disponible = ?');
        $query->execute([$disponible]);
        $items = $query->fetchAll(PDO::FETCH_OBJ);

        return $items;
    }

    function insertItem($id_categoria, $nombre, $material, $precio, $disponible) {

        $query = $this->db->prepare('INSERT INTO prenda (id_categoria, nombre, material, precio, disponible) VALUES (?,?,?,?,?)');
        $query->execute([$id_categoria, $nombre, $material, $precio, $disponible]);


        return $this->db->lastInsertId();
    }

    function remove($id) {
        $query = $this->db->prepare('DELETE from prenda where id = ?');
        $query->execute([$id]);
    }

    function updateItem($id, $id_categoria, $nombre, $material, $precio, $disponible) {
        $query = $this->db->prepare(
            'UPDATE prenda SET id_categoria=?, nombre=?, material=?, precio=?, disponible=? WHERE id=?'
        );
;

        $query->execute([$id_categoria, $nombre, $material, $precio, $disponible, $id]);
    }
}
