<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Libraries\FileUploader;
use App\Models\BlogPostModel;
use CodeIgniter\HTTP\ResponseInterface;

class BlogPostController extends BaseController
{
    protected $blogPostModel;
    protected $fileUploader;

    public function __construct()
    {
        $this->blogPostModel = new BlogPostModel();
        $this->fileUploader = new FileUploader();
    }


    public function index()
    {
        $posts = $this->blogPostModel->paginate(10);
        $pager = $this->blogPostModel->pager;

        if (!empty($posts)) {
            $response = [
                'status' => true,
                'data' => [
                    'blogPost' => $posts,
                    'pager' => [
                        'currentPage' => $pager->getCurrentPage(),
                        'totalPages' => $pager->getPageCount(),
                        'perPage' => $pager->getPerPage(),
                        'totalResults' => $pager->getTotal()
                    ]
                ],
                'message' => 'Posts retrieved successfully.',
                'statusCode' => 200
            ];
        } else {
            $response = [
                'status' => false,
                'data' => null,
                'message' => 'Data not found',
                'statusCode' => 404
            ];
        }

        return $this->response
            ->setContentType('application/json')
            ->setStatusCode($response['statusCode'])
            ->setJSON($response);
    }


    public function show($id)
    {
        $post = $this->blogPostModel->find($id);
        if (!$post) {
            $response = [
                'status' => false,
                'data' => null,
                'message' => 'Post not found',
                'statusCode' => 404
            ];
        } else {
            $response = [
                'status' => true,
                'data' => $post,
                'message' => 'Post retrieved successfully.',
                'statusCode' => 200
            ];
        }
        return $this->response
            ->setContentType('application/json')
            ->setStatusCode($response['statusCode'])
            ->setJSON($response);
    }


    public function create()
    {
        $data = $this->request->getPost();
        $files = $this->request->getFiles();
        if (!$this->validate($this->blogPostModel->getValidationRules())) {
            $response = [
                'status' => false,
                'data' => null,
                'message' => $this->validator->getErrors(),
                'statusCode' => 400
            ];
        } else {
            $uploadPath = FCPATH . 'uploads/blog';
            if (isset($files['image'])) {
                $uploadResult = $this->fileUploader->upload($files['image'], $uploadPath);
                if ($uploadResult['status']) {
                    $data['image'] = base_url('public/uploads/blog/') . $uploadResult;
                } else {
                    $response = [
                        'status' => false,
                        'data' => null,
                        'message' => $uploadResult['message'],
                        'statusCode' => 400
                    ];
                    return $this->response
                        ->setContentType('application/json')
                        ->setStatusCode($response['statusCode'])
                        ->setJSON($response);
                }
            }
            $store = $this->blogPostModel->insert($data);

            if ($store) {
                $response = [
                    'status' => true,
                    'data' => $data,
                    'message' => 'Post created successfully.',
                    'statusCode' => 201
                ];
            } else {
                $response = [
                    'status' => false,
                    'data' => null,
                    'message' => 'Failed to create post.',
                    'statusCode' => 500
                ];
            }
        }

        return $this->response
            ->setContentType('application/json')
            ->setStatusCode($response['statusCode'])
            ->setJSON($response);
    }


    public function update($id)
    {
        $data = $this->request->getJSON(true);
        $post = $this->blogPostModel->find($id);
        if (!$post) {
            $response = [
                'status' => false,
                'data' => null,
                'message' => 'Post not found.',
                'statusCode' => 404
            ];
        } elseif (!$this->validate($this->blogPostModel->getValidationRules())) {
            $response = [
                'status' => false,
                'data' => null,
                'message' => $this->validator->getErrors(),
                'statusCode' => 400
            ];
        } else {
            $update = $this->blogPostModel->update($id, $data);
            if ($update) {
                $response = [
                    'status' => true,
                    'data' => $data,
                    'message' => 'Post updated successfully.',
                    'statusCode' => 200
                ];
            } else {
                $response = [
                    'status' => false,
                    'errors' => $this->blogPostModel->errors(),
                    'message' => 'Failed to update post.',
                    'statusCode' => 400
                ];
            }
        }

        return $this->response
            ->setContentType('application/json')
            ->setStatusCode($response['statusCode'])
            ->setJSON($response);
    }


    public function delete($id)
    {
        $post = $this->blogPostModel->find($id);
        if (!$post) {
            $response = [
                'status' => false,
                'data' => null,
                'message' => 'Post not found.',
                'statusCode' => 404
            ];
        } else {
            $this->blogPostModel->delete($id);
            $response = [
                'status' => true,
                'data' => null,
                'message' => 'Post deleted successfully.',
                'statusCode' => 200
            ];
        }
        return $this->response
            ->setContentType('application/json')
            ->setStatusCode($response['statusCode'])
            ->setJSON($response);
    }
}
