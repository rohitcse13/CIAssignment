<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;
use CodeIgniter\HTTP\ResponseInterface;
use Firebase\JWT\JWT;

class UserController extends BaseController
{

    protected $userModel;

    private $secretKey = 'secret1234xyz';

    public function __construct()
    {
        $this->userModel = new UserModel();
    }


    // // Handle response
    // protected function handleResponse($status, $data, $message, $statusCode)
    // {
    //     $response = [
    //         'status' => $status,
    //         'data' => $data,
    //         'message' => $message,
    //         'statusCode' => $statusCode
    //     ];

    //     return $this->response
    //         ->setContentType('application/json')
    //         ->setStatusCode($statusCode)
    //         ->setJSON($response);
    // }


    // List all users with pagination
    public function index()
    {
        $users = $this->userModel->paginate(10);
        $pager = $this->userModel->pager;

        if (!empty($users)) {
            $response = [
                'status' => true,
                'data' => [
                    'users' => $users,
                    'pager' => [
                        'currentPage' => $pager->getCurrentPage(),
                        'totalPages' => $pager->getPageCount(),
                        'perPage' => $pager->getPerPage(),
                        'totalResults' => $pager->getTotal()
                    ]
                ],
                'message' => 'Users retrieved successfully.',
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

    // Register a new user
    public function register()
    {
        $data = $this->request->getPost();
        if (!$this->validate($this->userModel->getValidationRules())) {
            $response = [
                'status' => false,
                'data' => null,
                'message' => $this->validator->getErrors(),
                'statusCode' => 400
            ];
        } else {
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
            $store = $this->userModel->insert($data);

            if ($store) {
                $data['id'] = $store;
                $token = $this->generateJWT($data);
                $response = [
                    'status' => true,
                    'data' => $data,
                    'token' =>$token,
                    'message' => 'User registered successfully',
                    'statusCode' => 201
                ];
            } else {
                $response = [
                    'status' => false,
                    'data' => null,
                    'message' => 'Failed to register user',
                    'statusCode' => 500
                ];
            }
        }

        return $this->response
            ->setContentType('application/json')
            ->setStatusCode($response['statusCode'])
            ->setJSON($response);
    }



    private function generateJWT($data)
    {
        $payload = [
            'iat' => time(),
            'exp' => time() + 3600,
            'name' => $data['name'],
            'id' => $data['id']
        ];

        // Encode and return the JWT token
        return JWT::encode($payload, $this->secretKey, 'HS256');
    }



    // User login
    public function login()
    {

        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        // Check user existence
        $user = $this->userModel->where('email', $email)->first();
        if (!$user || !password_verify($password, $user['password'])) {
            $response = [
                'status' => false,
                'data' => null,
                'message' => 'Invalid email or password',
                'statusCode' => 422
            ];
        } else {
            $token = $this->generateJWT($user);
            session()->set([
                'user_id' => $user['id'],
                'user_name' => $user['name'],
                'is_logged_in' => true
            ]);
            $response = [
                'status' => true,
                'token' => $token,
                'data' => $user,
                'message' => 'Login successfully',
                'statusCode' => 201
            ];
        }

        return $this->response
            ->setContentType('application/json')
            ->setStatusCode($response['statusCode'])
            ->setJSON($response);
    }


    // Logout user
    public function logout()
    {
        if (!session()->has('user_id')) {
            $response = [
                'status' => false,
                'data' => null,
                'message' => 'User is already logged out',
                'statusCode' => 400
            ];
        } else {
            session()->destroy();
            $response = [
                'status' => true,
                'data' => null,
                'message' => 'Logout successfully',
                'statusCode' => 200
            ];
        }


        return $this->response
            ->setContentType('application/json')
            ->setStatusCode($response['statusCode'])
            ->setJSON($response);
    }

    // Update user information
    public function update($id)
    {
        $data = $this->request->getRawInput();
        $user = $this->userModel->find($id);
        if (!$user) {
            $response = [
                'status' => false,
                'data' => null,
                'message' => 'User not found',
                'statusCode' => 404
            ];
        } else {
            if (!$this->userModel->update($id, $data)) {
                $response = [
                    'status' => false,
                    'errors' => $this->userModel->errors(),
                    'message' => 'Validation failed',
                    'statusCode' => 400
                ];
            } else {
                $response = [
                    'status' => true,
                    'data' => $data,
                    'message' => 'User updated successfully',
                    'statusCode' => 200
                ];
            }
        }

        return $this->response
            ->setContentType('application/json')
            ->setStatusCode($response['statusCode'])
            ->setJSON($response);
    }


    // Delete user
    public function delete($id)
    {
        $user = $this->userModel->find($id);
        if (!$user) {
            $response = [
                'status' => false,
                'data' => null,
                'message' => 'User not found',
                'statusCode' => 404
            ];
        } else {
            $this->userModel->delete($id);
            $response = [
                'status' => true,
                'data' => null,
                'message' => 'User deleted successfully',
                'statusCode' => 200
            ];
        }
        return $this->response
            ->setContentType('application/json')
            ->setStatusCode($response['statusCode'])
            ->setJSON($response);
    }
}
