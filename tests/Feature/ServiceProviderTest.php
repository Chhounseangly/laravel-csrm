<?php

use Seangly\LaravelCsrm\CsrmServiceProvider;

it('loads package service provider class', function () {
    expect(class_exists(CsrmServiceProvider::class))->toBeTrue();
});
