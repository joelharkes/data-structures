<?php

/*
|--------------------------------------------------------------------------
| Test Case
|--------------------------------------------------------------------------
|
| The closure you provide to your test functions is always bound to a specific PHPUnit test
| case class. By default, that class is "PHPUnit\Framework\TestCase". Of course, you may
| need to change it using the "pest()" function to bind a different classes or traits.
|
*/

// pest()->extend(Tests\TestCase::class)->in('Feature');

/*
|--------------------------------------------------------------------------
| Expectations
|--------------------------------------------------------------------------
|
| When you're writing tests, you often need to check that values meet certain conditions. The
| "expect()" function gives you access to a set of "expectations" methods that you can use
| to assert different things. Of course, you may extend the Expectation API at any time.
|
*/

expect()->extend('toBeIterableResult', function (array $expectedValues) {
    $this->toBeIterable();
    foreach ($this->value as $key => $value) {
        expect($value)->toBe($expectedValues[$key]);
    }
});

expect()->extend('toBeSameJson', function (mixed $expected) {
    $this->toBeIterable();
    expect(json_encode($this->value, JSON_THROW_ON_ERROR))->toBe(json_encode($expected, JSON_THROW_ON_ERROR));
});

/*
|--------------------------------------------------------------------------
| Functions
|--------------------------------------------------------------------------
|
| While Pest is very powerful out-of-the-box, you may have some testing code specific to your
| project that you don't want to repeat in every file. Here you can also expose helpers as
| global functions to help you to reduce the number of lines of code in your test files.
|
*/

//function something()
//{
//    // ..
//}
