<?php

use Quanta\PA\ParameterCollection;

describe('ParameterCollection', function () {

    context('when there is no parameter', function () {

        beforeEach(function () {

            $this->parameters = new ParameterCollection;

        });

        describe('->number()', function () {

            it('should return 0', function () {

                $test = $this->parameters->number();

                expect($test)->toEqual(0);

            });

        });

        describe('->names()', function () {

            context('when no format is given', function () {

                it('should return an empty array', function () {

                    $test = $this->parameters->names();

                    expect($test)->toEqual([]);

                });

            });

            context('when a format is given', function () {

                it('should return an empty array', function () {

                    $test = $this->parameters->names('$%s');

                    expect($test)->toEqual([]);

                });

            });

        });

        describe('->only()', function () {

            it('should return a new empty parameter collection', function () {

                $test = $this->parameters->only(0, 2, 4);

                expect($test)->not->toBe($this->parameters);
                expect($test)->toEqual(new ParameterCollection);

            });

        });

        describe('->head()', function () {

            it('should return a new empty parameter collection', function () {

                $test = $this->parameters->head(3);

                expect($test)->not->toBe($this->parameters);
                expect($test)->toEqual(new ParameterCollection);

            });

        });

        describe('->tail()', function () {

            it('should return a new empty parameter collection', function () {

                $test = $this->parameters->tail(3);

                expect($test)->not->toBe($this->parameters);
                expect($test)->toEqual(new ParameterCollection);

            });

        });

        describe('->with()', function () {

            it('should return a new parameter collection containing the given parameters', function () {

                $test = $this->parameters->with('p1', 'p2', 'p3');

                expect($test)->not->toBe($this->parameters);
                expect($test)->toEqual(new ParameterCollection('p1', 'p2', 'p3'));

            });

        });

        describe('->__toString()', function () {

            it('should return an empty string', function () {

                $test = (string) $this->parameters;

                expect($test)->toEqual('');

            });

        });

    });

    context('when there is one parameter', function () {

        beforeEach(function () {

            $this->parameters = new ParameterCollection('p1');

        });

        describe('->number()', function () {

            it('should return 1', function () {

                $test = $this->parameters->number();

                expect($test)->toEqual(1);

            });

        });

        describe('->names()', function () {

            context('when no format is given', function () {

                it('should return an array containing the parameter name', function () {

                    $test = $this->parameters->names();

                    expect($test)->toEqual(['p1']);

                });

            });

            context('when a format is given', function () {

                it('should return an array containing the formatted parameter name', function () {

                    $test = $this->parameters->names('$%s');

                    expect($test)->toEqual(['$p1']);

                });

            });

        });

        describe('->only()', function () {

            context('when a given position is 0', function () {

                it('should return a new parameter collection containing the parameter', function () {

                    $test = $this->parameters->only(0, 2, 4);

                    expect($test)->not->toBe($this->parameters);
                    expect($test)->toEqual(new ParameterCollection('p1'));

                });

            });

            context('when no given position is 0', function () {

                it('should return a new empty parameter collection', function () {

                    $test = $this->parameters->only(1, 2, 4);

                    expect($test)->not->toBe($this->parameters);
                    expect($test)->toEqual(new ParameterCollection);

                });

            });

        });

        describe('->head()', function () {

            context('when the given length is 0', function () {

                it('should return a new empty parameter collection', function () {

                    $test = $this->parameters->head(0);

                    expect($test)->not->toBe($this->parameters);
                    expect($test)->toEqual(new ParameterCollection);

                });

            });

            context('when the given length is at least 1', function () {

                it('should return a new parameter collection containing the parameter', function () {

                    $test = $this->parameters->head(1);

                    expect($test)->not->toBe($this->parameters);
                    expect($test)->toEqual(new ParameterCollection('p1'));

                });

            });

        });

        describe('->tail()', function () {

            context('when the given length is 0', function () {

                it('should return a new empty parameter collection', function () {

                    $test = $this->parameters->tail(0);

                    expect($test)->not->toBe($this->parameters);
                    expect($test)->toEqual(new ParameterCollection);

                });

            });

            context('when the given length is at least 1', function () {

                it('should return a new parameter collection containing the parameter', function () {

                    $test = $this->parameters->tail(1);

                    expect($test)->not->toBe($this->parameters);
                    expect($test)->toEqual(new ParameterCollection('p1'));

                });

            });

        });

        describe('->with()', function () {

            it('should return a new parameter collection containing the parameter and the given parameters', function () {

                $test = $this->parameters->with('p2', 'p3', 'p4');

                expect($test)->not->toBe($this->parameters);
                expect($test)->toEqual(new ParameterCollection('p1', 'p2', 'p3', 'p4'));

            });

        });

        describe('->__toString()', function () {

            it('should return a default string representation of the parameter collection', function () {

                $test = (string) $this->parameters;

                expect($test)->toEqual('parameter $p1');

            });

        });

    });

    context('when there is more than one parameter', function () {

        beforeEach(function () {

            $this->parameters = new ParameterCollection('p1', 'p2', 'p3', 'p4', 'p5');

        });

        describe('->number()', function () {

            it('should return the number of parameters', function () {

                $test = $this->parameters->number();

                expect($test)->toEqual(5);

            });

        });

        describe('->names()', function () {

            context('when no format is given', function () {

                it('should return an array containing the parameter names', function () {

                    $test = $this->parameters->names();

                    expect($test)->toEqual(['p1', 'p2', 'p3', 'p4', 'p5']);

                });

            });

            context('when a format is given', function () {

                it('should return an array containing the formatted parameter names', function () {

                    $test = $this->parameters->names('$%s');

                    expect($test)->toEqual(['$p1', '$p2', '$p3', '$p4', '$p5']);

                });

            });

        });

        describe('->only()', function () {

            it('should return a new parameter collection containing the parameters at the given positions', function () {

                $test = $this->parameters->only(0, 2, 4);

                expect($test)->not->toBe($this->parameters);
                expect($test)->toEqual(new ParameterCollection('p1', 'p3', 'p5'));

            });

        });

        describe('->head()', function () {

            context('when the given length is 0', function () {

                it('should return a new empty parameter collection', function () {

                    $test = $this->parameters->head(0);

                    expect($test)->not->toBe($this->parameters);
                    expect($test)->toEqual(new ParameterCollection);

                });

            });

            context('when the given length is positive', function () {

                it('should return a new parameter collection containing the first n parameters', function () {

                    $test = $this->parameters->head(3);

                    expect($test)->not->toBe($this->parameters);
                    expect($test)->toEqual(new ParameterCollection('p1', 'p2', 'p3'));

                });

            });

            context('when the given length is negative', function () {

                it('should return a new parameter collection containing all parameters except the last n', function () {

                    $test = $this->parameters->head(-3);

                    expect($test)->not->toBe($this->parameters);
                    expect($test)->toEqual(new ParameterCollection('p1', 'p2'));

                });

            });

        });

        describe('->tail()', function () {

            context('when the given length is 0', function () {

                it('should return a new empty parameter collection', function () {

                    $test = $this->parameters->tail(0);

                    expect($test)->not->toBe($this->parameters);
                    expect($test)->toEqual(new ParameterCollection);

                });

            });

            context('when the given length is positive', function () {

                it('should return a new parameter collection containing the last n parameters', function () {

                    $test = $this->parameters->tail(3);

                    expect($test)->not->toBe($this->parameters);
                    expect($test)->toEqual(new ParameterCollection('p3', 'p4', 'p5'));

                });

            });

            context('when the given length is negative', function () {

                it('should return a new parameter collection containing all parameters except the first n', function () {

                    $test = $this->parameters->tail(-3);

                    expect($test)->not->toBe($this->parameters);
                    expect($test)->toEqual(new ParameterCollection('p4', 'p5'));

                });

            });

        });

        describe('->with()', function () {

            it('should return a new parameter collection containing the given parameters', function () {

                $test = $this->parameters->with('p6', 'p7', 'p8');

                expect($test)->not->toBe($this->parameters);
                expect($test)->toEqual(new ParameterCollection(...[
                    'p1', 'p2', 'p3', 'p4', 'p5', 'p6', 'p7', 'p8',
                ]));

            });

        });

        describe('->__toString()', function () {

            it('should return a default string representation of the parameter collection', function () {

                $test = (string) $this->parameters;

                expect($test)->toEqual('parameters $p1, $p2, $p3, $p4 and $p5');

            });

        });

    });

});
