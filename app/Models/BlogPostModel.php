<?php

namespace App\Models;

use CodeIgniter\Model;

class BlogPostModel extends Model
{
    protected $table            = 'blog_posts';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['title', 'content', 'author_id', 'image', 'created_at', 'updated_at'];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [
        'title'     => 'required|max_length[255]',
        'content'   => 'required|max_length[255]',
        'author_id' => 'required|integer|is_not_unique[users.id]',
        'image' => 'uploaded[image]|is_image[image]|mime_in[image,image/jpeg,image/png,image/gif]|max_size[image,2048]',
    ];
    protected $validationMessages   = [
        'title' => [
            'required'   => 'The title is required.',
            'min_length' => 'The title must be at least 5 characters long.',
            'max_length' => 'The title cannot exceed 255 characters.',
        ],
        'content' => [
            'required'   => 'The content is required.',
            'max_length' => 'The content cannot exceed 255 characters.',
        ],
        'author_id' => [
            'required'    => 'Author ID is required.',
            'integer'     => 'Author ID must be a valid integer.',
            'is_not_unique' => 'The selected author does not exist.',

        ],
        'image' => [
            'uploaded' => 'You must upload an image.',
            'is_image' => 'The file must be a valid image.',
            'mime_in' => 'Only JPEG, PNG, and GIF image formats are allowed.',
            'max_size' => 'The image size must not exceed 2MB.'
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
