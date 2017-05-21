<?php

use Kahlan\Filter\Filter;

$commandLine = $this->commandLine();
$commandLine->option('reporter', 'default', 'verbose');
$commandLine->option('coverage', 'default', 3);

Filter::register('set.credentials', function ($chain) {
    $credentials = [
        'username' => 'TEST_USERNAME',
        'password' => 'TEST_PASSWORD',
        'api_url' => 'TEST_URL',
        'acc_code' => 'TEST_ACC_CODE'
    ];
    \Yapstone\Yapstone::setCredentials($credentials);
    return $chain->next();
});
Filter::apply($this, 'run', 'set.credentials');
