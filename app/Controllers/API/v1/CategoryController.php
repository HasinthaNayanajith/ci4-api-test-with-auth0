<?php

namespace App\Controllers\API\v1;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use App\Models\CategoryModel;

class CategoryController extends ResourceController
{

    use ResponseTrait;

    // get all categories - /api/v1/categories/all
    public function index()
    {
        $model = new CategoryModel();
        $data = $model->select('id, category_name, created_at')->where('is_deleted', 0)->findAll();
        // if data is not empty
        if (!empty($data)) {
            $response = [
                'status' => 200, // Not found
                'error' => null,
                'message' => 'Categories retrieved successfully.',
                'data' => $data
            ];
            return $this->respond($response);
        } else {
            $response = [
                'status' => 404, // Not found
                'error' => null,
                'message' => 'No categories found.'
            ];
            return $this->respond($response);
        }
    }

    // get single category - /api/v1/categories/show/(:num)
    public function show($id = null)
    {
        $model = new CategoryModel();
        $data = $model->select('id, category_name, created_at')->where('is_deleted', 0)->find($id);
        // if data is not empty
        if (!empty($data)) {
            $response = [
                'status' => 200, // Success
                'error' => null,
                'message' => 'Category retrieved successfully.',
                'data' => $data
            ];
            return $this->respond($response);
        } else {
            $response = [
                'status' => 404, // Not found
                'error' => null,
                'message' => 'Category not found.'
            ];
            return $this->respond($response);
        }
    }

    // create new category - /api/v1/categories/create
    public function create()
    {
        $model = new CategoryModel();

        // get category name from request
        $category_name = $this->request->getVar('category_name');
        // check if category name is not empty
        if (!empty($category_name)) {
            // check if category name already exists
            $category = $model->where('category_name', $category_name)->findAll();
            if (empty($category)) {
                // insert category into database
                $data = [
                    'category_name' => $category_name,
                    'created_at' => date('Y-m-d H:i:s'),
                ];
                $model->insert($data);
                $response = [
                    'status' => 201, // created
                    'error' => null,
                    'message' => 'Category created successfully.',
                    'data' => $data
                ];
                return $this->respondCreated($response);
            } else {
                $response = [
                    'status' => 409, // duplicate
                    'error' => null,
                    'message' => 'Category already exists.'
                ];
                return $this->respond($response);
            }
        } else {
            $response = [
                'status' => 400, // Bad request
                'error' => null,
                'message' => 'Category name cannot be empty.'
            ];
            return $this->respond($response);
        }
    }

    // update category - /api/v1/categories/update/(:num)
    public function update($id = null)
    {
        $model = new CategoryModel();

        // get category name from request
        $category_name = $this->request->getVar('category_name');
        // check if category name is not empty
        if (!empty($category_name)) {
            // check if category name already exists
            $category = $model->where('category_name', $category_name)->findAll();
            if (empty($category)) {
                // update category into database
                $data = [
                    'category_name' => $category_name,
                    'updated_at' => date('Y-m-d H:i:s'),
                ];
                $model->update($id, $data);
                $response = [
                    'status' => 200, // Success
                    'error' => null,
                    'message' => 'Category updated successfully',
                    'data' => $data
                ];
                return $this->respond($response);
            } else {
                $response = [
                    'status' => 409, // duplicate
                    'error' => null,
                    'message' => 'Either you tried to update the Category Name with the same name as it was or there is another category with this name. Please try again with a different name.'
                ];
                return $this->respond($response);
            }
        } else {
            $response = [
                'status' => 400, // Bad request
                'error' => null,
                'message' => 'Category name cannot be empty.'
            ];
            return $this->respond($response);
        }
    }

    // delete category - /api/v1/categories/delete/(:num)
    public function delete($id = null)
    {
        $model = new CategoryModel();
        $data = $model->where('is_deleted', 0)->find($id);
        // if data is not empty
        if (!empty($data)) {
            // delete category from database - set is_deleted to 1, deleted_at to current date time
            $delete_data = [
                'is_deleted' => 1,
                'deleted_at' => date('Y-m-d H:i:s'),
            ];
            $model->update($id, $delete_data);
            $response = [
                'status' => 200, // Success
                'error' => null,
                'message' => 'Category deleted successfully.'
            ];
            return $this->respondDeleted($response);
        } else {
            $response = [
                'status' => 404, // Not found
                'error' => null,
                'message' => 'Category not found.'
            ];
            return $this->respond($response);
        }
    }
}
