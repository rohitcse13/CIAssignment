<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table            = 'users';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['name', 'email', 'password', 'created_at', 'updated_at'];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules = [
        'name' => 'required|alpha_space|min_length[3]|max_length[100]',
        'email' => 'required|valid_email|is_unique[users.email]',
        'password' => 'required|min_length[8]|max_length[16]',
        'confirm_password' => 'required|matches[password]'
    ];
    protected $validationMessages   = [
        'name' => [
            'required' => 'Name is required',
            'alpha_space' => 'Name can only contain alphabetical characters and spaces',
            'min_length' => 'Name must be at least 3 characters long',
            'max_length' => 'Name cannot exceed 100 characters',
        ],
        'email' => [
            'required' => 'Email is required',
            'valid_email' => 'Please enter a valid email address',
            'is_unique' => 'This email is already registered',
        ],
        'password' => [
            'required' => 'Password is required',
            'min_length' => 'Password must be at least 8 characters long',
            'max_length' => 'Password cannot exceed 20 characters',
        ],
        'confirm_password' => [
            'required' => 'Confirm password is required',
            'matches' => 'Confirm password does not match the password',
        ]
    ];
    
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];
}
