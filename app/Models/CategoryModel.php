<?php

namespace App\Models;
use CodeIgniter\Model;

class CategoryModel extends Model
{
    protected $table = 'categories';
    protected $primaryKey = 'id';
    protected $allowedFields = ['category_name', 'created_at', 'updated_at', 'is_deleted', 'deleted_at'];
    protected $returnType = 'array';
}