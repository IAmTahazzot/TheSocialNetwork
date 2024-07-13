<?php

namespace Presentation\Http\Controllers\Web;

class PostController
{
    public function index()
    {
        echo 'Post index';
    }

    public function getPost(string $slug)
    {
        echo '<p style="color: dodgerblue;">Getting post from: ' . $slug . "</p>";
    }
}
