<?php

use Yapstone\Yapstone;
use Yapstone\Request;
use Tests\Utility\Reflection;
use Yapstone\Exceptions\MissingRequiredCredentials;

describe('Request', function () {
    context('Credentials', function () {
        beforeEach(function () {
            $this->yapstone = new Yapstone();
            allow($this->yapstone)
                ->toReceive('::getApiUrl')
                ->andReturn('URL');
            allow($this->yapstone)
                ->toReceive('::getUsername')
                ->andReturn('USERNAME');
            allow($this->yapstone)
                ->toReceive('::getPassword')
                ->andReturn('PASSWORD');
            allow($this->yapstone)
                ->toReceive('::getAccountCode')
                ->andReturn('ACC_CODE');
        });
        it('does\'t throw an exception', function () {
            $request = new Request();
            expect($request)->toBeAnInstanceOf(Request::class);
        });
        it('has the correct properties set', function () {
            $request = new Request();
            $reflection = new Reflection();
            expect($reflection->getEncapsulatedProperty($request, 'url'))->toBe('URL');
            expect($reflection->getEncapsulatedProperty($request, 'username'))->toBe('USERNAME');
            expect($reflection->getEncapsulatedProperty($request, 'password'))->toBe('PASSWORD');
            expect($reflection->getEncapsulatedProperty($request, 'account'))->toBe('ACC_CODE');
        });
        it('throws an exception for missing url', function () {
            $closure = function () {
                allow($this->yapstone)
                    ->toReceive('::getApiUrl')
                    ->andReturn(null);
                $request = new Request();
            };
            expect($closure)->toThrow(new MissingRequiredCredentials('Credentials missing'));
        });
        it('throws an exception for missing username', function () {
            $closure = function () {
                allow($this->yapstone)
                    ->toReceive('::getUsername')
                    ->andReturn(null);
                $request = new Request();
            };
            expect($closure)->toThrow(new MissingRequiredCredentials('Credentials missing'));
        });
        it('throws an exception for missing password', function () {
            $closure = function () {
                allow($this->yapstone)
                    ->toReceive('::getPassword')
                    ->andReturn(null);
                $request = new Request();
            };
            expect($closure)->toThrow(new MissingRequiredCredentials('Credentials missing'));
        });
        it('throws an exception for missing account code', function () {
            $closure = function () {
                allow($this->yapstone)
                    ->toReceive('::getAccountCode')
                    ->andReturn(null);
                $request = new Request();
            };
            expect($closure)->toThrow(new MissingRequiredCredentials('Credentials missing'));
        });
    });
});
