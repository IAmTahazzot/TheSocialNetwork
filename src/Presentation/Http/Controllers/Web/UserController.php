<?php

namespace Presentation\Http\Controllers\Web;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class UserController
{
    public function index()
    {
        echo 'User index';
    }

    public function profile(string $username, PostController $postController)
    {
        echo "This is " . $username . "'s profile";
        $postController->getPost('a-sample-post-slug');
    }

    public function about()
    {
        echo 'User controller about';
    }

    public function createUser(Request $request, Response $response)
    {
        $username = $request->request->get('username');

        header('Content-Type: application/json');
        $response->headers->set('Content-Type', 'application/json');

        if (!$username) {
            $response->setContent(json_encode(['message' => 'Username required.']));
            return $response->send();
        }

        // send json response header
        $response->setContent(json_encode(['message' => 'User retrieved successfully']));
        $response->send();
    }
}
