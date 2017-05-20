<?php

use Yapstone\Endpoints\CreditCardPayment;
use Tests\Utility\Reflection;

describe('CreditCardPayment', function () {
    context('Required fields', function () {
        it('has valid required field', function () {
            $requiredFields = [
                'number',
                'expiration',
                'cardholder',
                'type',
                'street',
                'city',
                'state',
                'zip',
                'country',
                'phone',
                'email',
                'amount|amounts',
                'id',
                'personFirstname',
                'personLastname'
            ];
            $reflection = new Reflection();
            $actual = $reflection->getEncapsulatedProperty(CreditCardPayment::class, 'requiredFields');
            expect($actual)->toBeA('array')->toBe($requiredFields);
        });
    });
    context('Submission', function () {
        it('accepts params', function () {
            $instance = new CreditCardPayment();
            $param = 'val';
            allow($instance)
                ->toReceive('::makeRequest')
                ->with($param)
                ->andReturn(true);
            expect($instance::submit($param))->toBe(true);
        });
        it('uses default params', function () {
            $instance = new CreditCardPayment();
            allow($instance)
                ->toReceive('::makeRequest')
                ->andReturn(true);
            expect($instance::submit())->toBe(true);
        });
    });
});
