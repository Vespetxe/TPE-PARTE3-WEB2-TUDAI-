<?php

class CategoryModel {
    private $db;

    function __construct() {
     // 1. abro conexión con la DB
     $this->db = new PDO('mysql:host=localhost;dbname=db_tienda_panpox;charset=utf8', 'root', '');
    }

    public function getCategory($id) {
        $query = $this->db->prepare('SELECT * FROM categoria WHERE id = ?');
        $query->execute([$id]);
        $categoria = $query->fetch(PDO::FETCH_OBJ);

        return $categoria;
    }
    
    public function getCategories() {
        // 2. ejecuto la consulta 
        $query = $this->db->prepare('SELECT * FROM categoria');
        $query->execute([]);

        // 3. obtengo los resultados de la consulta
        $categorias = $query->fetchAll(PDO::FETCH_OBJ);

        return $categorias;
    }



    function insertCategory($nombre, $descripcion, $responsable) {

        $query = $this->db->prepare("INSERT INTO categoria(nombre, descripcion, responsable) VALUES(?,?,?)");
        $query->execute([$nombre, $descripcion, $responsable]);


        return $this->db->lastInsertId();
    }

    function removeCategory($id) {
        $query = $this->db->prepare('DELETE from categoria where id = ?');
        $query->execute([$id]);
    }

    function updateCategory($id, $nombre, $descripcion, $responsable) {
        $query = $this->db->prepare(
            'UPDATE categoria SET nombre=?, descripcion=?, responsable=? WHERE id=?'
        );

        $query->execute([$nombre, $descripcion, $responsable , $id]);
    }
}