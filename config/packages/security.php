<?php
use App\Controller\User;

$container->loadFromExtension('security', [
    'enable_authenticator_manager' => true,
    'providers' => [
        'users' => [
            'entity' => [
                // the class of the entity that represents users
                'class'    => User::class,
                // the property to query by - e.g. username, email, etc
                'property' => 'email',
                // optional: if you're using multiple Doctrine entity
                // managers, this option defines which one to use
                // 'manager_name' => 'customer',
            ],
        ],
    ],

    // ...
]);
