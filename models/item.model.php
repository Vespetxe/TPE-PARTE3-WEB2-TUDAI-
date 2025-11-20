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
