<?php

use Symfony\Component\HttpFoundation\Request;

return [
    // Without createFromGlobal execution, the Request array will be empty
    // Well, that's how Symfony work
    Request::class => function () {
        return Request::createFromGlobals();
    }
];
