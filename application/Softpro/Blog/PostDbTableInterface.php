<?php

namespace Softpro\Blog;

interface PostDbTableInterface {
    
    public function get($id);
    public function getList();
    public function setDb($db);
    public function delete($id);
    public function update($data, $id);
    public function insert($data);
    
}