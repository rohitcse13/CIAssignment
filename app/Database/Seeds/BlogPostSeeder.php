<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class BlogPostSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'title' => 'My First Blog Post',
                // 'slug' => 'my-first-blog-post',
                'content' => 'This is the content of my first blog post.',
                'author_id' => 1, // Assuming the first user is the author
                // 'published_at' => time(),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ];

        // Using Query Builder
        $this->db->table('blog_posts')->insertBatch($data);
        
    }
}
