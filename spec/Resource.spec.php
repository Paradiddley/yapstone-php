<?php

use Kahlan\Plugin\Double;
use Tests\Utility\ResourceTester;
use Tests\Utility\Reflection;
use Yapstone\Request;
use Yapstone\Exceptions\InvalidParameters;

describe('Resource', function () {
    context('Make request', function () {
        beforeEach(function () {
            $this->request = Double::instance(['class' => Request::class]);
            allow(Request::class)
                ->toReceive('send')
                ->andReturn(true);
        });
        it('has invalid parameters', function () {
            $closure = function () {
                $param = 'val';
                $instance = new ResourceTester();
                $instance::submit($param);
            };
            expect($closure)->toThrow(new InvalidParameters('The parameters passed should be an array'));
        });
        it('has all required fields', function () {
            $param = ['key' => 'val'];
            $reflection = new Reflection();
            $instance = $reflection->setStaticEncapsulatedProperty(
                ResourceTester::class,
                'requiredFields',
                ['key']
            );
            expect($instance::submit($param))->toBe(true);
        });
        it('doesn\'t have all required fields', function () {
            $closure = function () {
                $param = ['key1' => 'val'];
                $reflection = new Reflection();
                $instance = $reflection->setStaticEncapsulatedProperty(
                    ResourceTester::class,
                    'requiredFields',
                    ['key1', 'key2', 'key3']
                );
                $instance::submit($param);
            };
            expect($closure)->toThrow(new InvalidParameters('Missing required fields; key2, key3'));
        });
        it('handles optional fields', function () {
            $closure = function ($param = []) {
                $reflection = new Reflection();
                $instance = $reflection->setStaticEncapsulatedProperty(
                    ResourceTester::class,
                    'requiredFields',
                    ['key1|key2']
                );
                return $instance::submit($param);
            };
            expect($closure(['key1' => 'val']))->toBe(true);
            expect($closure(['key2' => 'val']))->toBe(true);
            expect($closure)->toThrow(new InvalidParameters('Missing required fields; key1|key2'));
        });
    });
});
