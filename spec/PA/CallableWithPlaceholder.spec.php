<?php

use function Eloquent\Phony\Kahlan\mock;

use Quanta\Placeholder;
use Quanta\PA\CallableInterface;
use Quanta\PA\PlaceholderSequence;
use Quanta\PA\CallableWithPlaceholder;

describe('CallableWithPlaceholder', function () {

    beforeEach(function () {

        $this->delegate = mock(CallableInterface::class);

    });

    context('when there is no default value', function () {

        beforeEach(function () {

            $this->callable = new CallableWithPlaceholder(
                $this->delegate->get(),
                'placeholder'
            );

        });

        it('should implement CallableInterface', function () {

            expect($this->callable)->toBeAnInstanceOf(CallableInterface::class);

        });

        describe('->placeholders()', function () {

            beforeEach(function () {

                $this->placeholders = new PlaceholderSequence(...[
                    'placeholder1',
                    'placeholder2',
                    'placeholder3',
                ]);

            });

            context('when optional placeholders are not included', function () {

                it('should return the delegate placeholders with this placeholder', function () {

                    $this->delegate->placeholders->with(true)->returns($this->placeholders);

                    $test = $this->callable->placeholders();

                    expect($test)->toEqual(new PlaceholderSequence(...[
                        'placeholder1',
                        'placeholder2',
                        'placeholder3',
                        'placeholder',
                    ]));

                });

            });

            context('when optional placeholders are included', function () {

                it('should return the delegate placeholders with this placeholder', function () {

                    $this->delegate->placeholders->with(true)->returns($this->placeholders);

                    $test = $this->callable->placeholders(true);

                    expect($test)->toEqual(new PlaceholderSequence(...[
                        'placeholder1',
                        'placeholder2',
                        'placeholder3',
                        'placeholder',
                    ]));

                });

            });

        });

        describe('->__invoke()', function () {

            context('when the delegate has no placeholder', function () {

                beforeEach(function () {

                    $this->delegate->placeholders->returns(new PlaceholderSequence);

                });

                context('when no argument is given', function () {

                    it('should throw an ArgumentCountError', function () {

                        expect($this->callable)->toThrow(new ArgumentCountError);

                    });

                    it('should display the delegate string representation and the placeholder name', function () {

                        $this->delegate->str->returns('str');

                        try {
                            ($this->callable)();
                        }

                        catch (ArgumentCountError $e) {
                            $test = $e->getMessage();
                        }

                        expect($test)->toContain('function str()');
                        expect($test)->toContain('[\'placeholder\']');

                    });

                });

                context('when at least one argument is given', function () {

                    it('should invoke the delegate with the given arguments', function () {

                        $this->delegate->__invoke->with(...[
                            'value1',
                            'value2',
                            'value3',
                        ])->returns('value');

                        $test = ($this->callable)('value1', 'value2', 'value3');

                        expect($test)->toEqual('value');

                    });

                });

            });

            context('when the delegate has at least one placeholder', function () {

                beforeEach(function () {

                    $this->delegate->placeholders->returns(new PlaceholderSequence(...[
                        'placeholder1',
                        'placeholder2',
                        'placeholder3',
                    ]));

                });

                context('when less argument than the delegate placeholders are given', function () {

                    it('should throw an ArgumentCountError', function () {

                        $test = function () { ($this->callable)('value1', 'value2'); };

                        expect($this->callable)->toThrow(new ArgumentCountError);

                    });

                    it('should display the delegate string representation and format one unbound placeholder', function () {

                        $this->delegate->str->returns('str');

                        try {
                            ($this->callable)('value1', 'value2');
                        }

                        catch (ArgumentCountError $e) {
                            $test = $e->getMessage();
                        }

                        expect($test)->toContain('function str()');
                        expect($test)->toContain('[\'placeholder3\', \'placeholder\']');

                    });

                    it('should display the delegate string representation and format multiple unbound placeholders', function () {

                        $this->delegate->str->returns('str');

                        try {
                            ($this->callable)('value1');
                        }

                        catch (ArgumentCountError $e) {
                            $test = $e->getMessage();
                        }

                        expect($test)->toContain('function str()');
                        expect($test)->toContain('[\'placeholder2\', \'placeholder3\', \'placeholder\']');

                    });

                });

                context('when as many arguments as the delegate placeholders are given', function () {

                    it('should throw an ArgumentCountError', function () {

                        $test = function () { ($this->callable)('value1', 'value2', 'value3'); };

                        expect($this->callable)->toThrow(new ArgumentCountError);

                    });

                    it('should display the delegate string representation and the placeholder name', function () {

                        $this->delegate->str->returns('str');

                        try {
                            ($this->callable)('value1', 'value2', 'value3');
                        }

                        catch (ArgumentCountError $e) {
                            $test = $e->getMessage();
                        }

                        expect($test)->toContain('function str()');
                        expect($test)->toContain('[\'placeholder\']');

                    });

                });

                context('when more arguments than the delegate placeholders are given', function () {

                    it('should invoke the delegate with the given arguments', function () {

                        $this->delegate->__invoke->with(...[
                            'value1',
                            'value2',
                            'value3',
                            'value4',
                        ])->returns('value');

                        $test = ($this->callable)('value1', 'value2', 'value3', 'value4');

                        expect($test)->toEqual('value');

                    });

                });

            });

        });

        describe('->str()', function () {

            it('should return the delegate string representation', function () {

                $this->delegate->str->returns('str');

                $test = $this->callable->str();

                expect($test)->toEqual('str');

            });

        });

    });

    context('when there is a default value', function () {

        beforeEach(function () {

            $this->callable = new CallableWithPlaceholder(
                $this->delegate->get(),
                'placeholder',
                'default'
            );

        });

        it('should implement CallableInterface', function () {

            expect($this->callable)->toBeAnInstanceOf(CallableInterface::class);

        });

        describe('->placeholders()', function () {

            beforeEach(function () {

                $this->placeholders = new PlaceholderSequence(...[
                    'placeholder1',
                    'placeholder2',
                    'placeholder3',
                ]);

            });

            context('when optional placeholders are not included', function () {

                it('should return the delegate placeholders', function () {

                    $this->delegate->placeholders->with(false)->returns($this->placeholders);

                    $test = $this->callable->placeholders();

                    expect($test)->toBe($this->placeholders);

                });

            });

            context('when optional placeholders are included', function () {

                it('should return the delegate placeholders with this placeholder', function () {

                    $this->delegate->placeholders->with(true)->returns($this->placeholders);

                    $test = $this->callable->placeholders(true);

                    expect($test)->toEqual(new PlaceholderSequence(...[
                        'placeholder1',
                        'placeholder2',
                        'placeholder3',
                        'placeholder',
                    ]));

                });

            });

        });

        describe('->__invoke()', function () {

            context('when the delegate has no placeholder', function () {

                beforeEach(function () {

                    $this->delegate->placeholders->returns(new PlaceholderSequence);

                });

                context('when no argument is given', function () {

                    it('should invoke the delegate with the default value', function () {

                        $this->delegate->__invoke->with('default')->returns('value');

                        $test = ($this->callable)();

                        expect($test)->toEqual('value');

                    });

                });

                context('when at least one argument is given', function () {

                    it('should invoke the delegate with the given arguments', function () {

                        $this->delegate->__invoke->with(...[
                            'value1',
                            'value2',
                            'value3',
                        ])->returns('value');

                        $test = ($this->callable)('value1', 'value2', 'value3');

                        expect($test)->toEqual('value');

                    });

                });

            });

            context('when the delegate has at least one placeholder', function () {

                beforeEach(function () {

                    $this->delegate->placeholders->returns(new PlaceholderSequence(...[
                        'placeholder1',
                        'placeholder2',
                        'placeholder3',
                    ]));

                });

                context('when less argument than the delegate placeholders are given', function () {

                    it('should invoke the delegate with the given arguments, placeholders and the default value', function () {

                        $this->delegate->__invoke->with(...[
                            'value1',
                            Placeholder::class,
                            Placeholder::class,
                            'default',
                        ])->returns('value');

                        $test = ($this->callable)('value1');

                        expect($test)->toEqual('value');

                    });

                });

                context('when as many arguments as the delegate placeholders are given', function () {

                    it('should invoke the delegate with the given arguments and the default value', function () {

                        $this->delegate->__invoke->with(...[
                            'value1',
                            'value2',
                            'value3',
                            'default',
                        ])->returns('value');

                        $test = ($this->callable)('value1', 'value2', 'value3');

                        expect($test)->toEqual('value');

                    });

                });

                context('when more arguments than the delegate placeholders are given', function () {

                    it('should invoke the delegate with the given arguments', function () {

                        $this->delegate->__invoke->with(...[
                            'value1',
                            'value2',
                            'value3',
                            'value4',
                        ])->returns('value');

                        $test = ($this->callable)('value1', 'value2', 'value3', 'value4');

                        expect($test)->toEqual('value');

                    });

                });

            });

        });

        describe('->str()', function () {

            it('should return the delegate string representation', function () {

                $this->delegate->str->returns('str');

                $test = $this->callable->str();

                expect($test)->toEqual('str');

            });

        });

    });

});
