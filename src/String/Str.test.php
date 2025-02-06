<?php declare(strict_types=1);


use DataStructures\String\Str;

describe("A String", function (){
    it('can be empty', function () {
        $str = new Str('');
        expect($str)->not->toBeNull();
        expect($str->isEmpty())->toBeTrue();
    });


    it('can be split', function () {
        $str = new Str('a,b,c');
        expect($str->split(','))->toBeSameJson(['a', 'b', 'c']);
        expect($str->split(''))->toBeSameJson(['a',',','b', ',','c']);
    });

    it('can be replaced', function () {
        $str = new Str('a,b,c');
        expect((string) $str->replace(',', '.'))->toBe('a.b.c');
    });

    it('can regex replace', function() {
        $str = new Str('a,b,c');
        expect((string) $str->regReplace('/(a|b)/', 'd'))->toBe('d,d,c');
    });

    it('can regex match', function () {
        $str = new Str('a,b,c');
        expect( $str->isMatch('/a/'))->toBeTrue();
        expect( $str->isMatch('/d/'))->toBeFalse();
    });

    it('can retrieve regex matches', function () {
        $str = new Str('a,b,c');
        expect($str->matches('/a/'))->toBeSameJson(['a']);
        expect($str->matches('/(a|b)/'))->toBeSameJson(['a', 'a']);
    });

    it('can lowercase', function () {
        $str = new Str('Hello World');
        expect($str->toLowercase()->__toString())->toBe('hello world');
    });

    it('can uppercase', function () {
        $str = new Str('Hello World');
        expect($str->toUppercase()->__toString())->toBe('HELLO WORLD');
    });

    it('can trim', function () {
        $str = new Str(' Hello World ');
        expect($str->trim()->__toString())->toBe('Hello World');
        expect($str->trimLeft()->__toString())->toBe('Hello World ');
        expect($str->trimRight()->__toString())->toBe(' Hello World');
    });

    it('can check contents', function () {
        $str = new Str('Hello World');
        expect($str->startsWith('Hello'))->toBeTrue();
        expect($str->startsWith('World'))->toBeFalse();

        expect($str->endsWith('Hello'))->toBeFalse();
        expect($str->endsWith('World'))->toBeTrue();

        expect($str->contains('Hello'))->toBeTrue();
        expect($str->contains('World'))->toBeTrue();
        expect($str->contains('WorldHello'))->toBeFalse();
    });

    it('can substring', function () {
        $str = new Str('Hello World');
        expect($str->substring(0, 5)->__toString())->toBe('Hello');
        expect($str->substring(6)->__toString())->toBe('World');
    });

    it('can prepend and append', function () {
        $str = new Str('World');
        expect($str->prepend('Hello ')->__toString())->toBe('Hello World');
        expect($str->append('!')->__toString())->toBe('World!');
    });

    it('can convert to slug', function () {
        expect((new Str('Hello World'))->slug()->__toString())->toBe('hello-world');
        expect((new Str('Hello World_'))->slug('_')->__toString())->toBe('hello_world');
        expect((new Str('Hello World_'))->slug('_', upperToSlugLowercase: false)->__toString())->toBe('ello_orld');
        expect((new Str('asdf(*(90asdf'))->slug()->__toString())->toBe('asdf-90asdf');
        expect((new Str('ABSO LUTEly'))->slug('_', replacementRegex: '/[^A-Z]/', upperToSlugLowercase: false)->__toString())->toBe('ABSO_LUTE');
        expect((new Str('ABSO LUTEly'))->slug('_', replacementRegex: '/[A-Z\s]/', upperToSlugLowercase: false)->__toString())->toBe('ly');
    });
});
