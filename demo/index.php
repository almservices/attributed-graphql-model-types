<?php

declare(strict_types=1);

require_once __DIR__.'/../vendor/autoload.php';

use AlmServices\Graphql\TypeContainer;
use Demo\ChangeUserNameMutation;
use Demo\ChangeUserNameResolver;
use Demo\Context;
use Demo\Mutation;
use Demo\MyUserQuery;
use Demo\MyUserResolver;
use Demo\Query;
use Demo\Schema;
use Demo\User;
use Demo\UserModel;
use GraphQL\Server\ServerConfig;
use GraphQL\Server\StandardServer;

$container = new TypeContainer();
$userType = $container->get(UserModel::class);
$myUserResolver = new MyUserResolver();
$changeUserNameResolver = new ChangeUserNameResolver();
$schema = new Schema(
    query: new Query([
        new MyUserQuery($userType, $myUserResolver),
    ]),
    mutation: new Mutation([
        new ChangeUserNameMutation($userType, $changeUserNameResolver),
    ])
);

$server = new StandardServer(
    ServerConfig::create()
        ->setSchema($schema)
        ->setContext(new Context(static function () {
            if (!isset($_SERVER['HTTP_AUTHORIZATION'])) {
                return null;
            }

            return new User(1, 'foo', 'bar');
        }))
);
$server->handleRequest();
