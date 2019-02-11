<?php

use Quanta\Placeholder;
use Quanta\PA\PlaceholderSequence;

describe('PlaceholderSequence', function () {

    context('when there is no placeholder', function () {

        beforeEach(function () {

            $this->placeholders = new PlaceholderSequence;

        });

        describe('->number()', function () {

            it('should return 0', function () {

                $test = $this->placeholders->number();

                expect($test)->toEqual(0);

            });

        });

        describe('->names()', function () {

            context('when no format is given', function () {

                it('should return an empty array', function () {

                    $test = $this->placeholders->names();

                    expect($test)->toEqual([]);

                });

            });

            context('when a format is given', function () {

                it('should return an empty array', function () {

                    $test = $this->placeholders->names('p:%s');

                    expect($test)->toEqual([]);

                });

            });

        });

        describe('->from()', function () {

            it('should return a new empty placeholder sequence', function () {

                $test = $this->placeholders->from(0);

                expect($test)->not->toBe($this->placeholders);
                expect($test)->toEqual(new PlaceholderSequence);

            });

        });

        describe('->unbound()', function () {

            it('should return a new empty placeholder sequence', function () {

                $test = $this->placeholders->unbound(...[
                    'a1',
                    Placeholder::class,
                    'a3',
                    Placeholder::class,
                    'a5',
                ]);

                expect($test)->not->toBe($this->placeholders);
                expect($test)->toEqual(new PlaceholderSequence);

            });

        });

        describe('->with()', function () {

            it('should return a new placeholder sequence containing the given placeholders', function () {

                $test = $this->placeholders->with('p1', 'p2', 'p3');

                expect($test)->not->toBe($this->placeholders);
                expect($test)->toEqual(new PlaceholderSequence('p1', 'p2', 'p3'));

            });

        });

        describe('->str()', function () {

            context('when no separator is given', function () {

                it('should return an empty string', function () {

                    $test = $this->placeholders->str('p:%s');

                    expect($test)->toEqual('');

                });

            });

            context('when a separator is given', function () {

                it('should return an empty string', function () {

                    $test = $this->placeholders->str('p:%s', '>');

                    expect($test)->toEqual('');

                });

            });

        });

        describe('->__toString()', function () {

            it('should return an empty string', function () {

                $test = (string) $this->placeholders;

                expect($test)->toEqual('');

            });

        });

    });

    context('when there is one placeholder', function () {

        beforeEach(function () {

            $this->placeholders = new PlaceholderSequence('p1');

        });

        describe('->number()', function () {

            it('should return 1', function () {

                $test = $this->placeholders->number();

                expect($test)->toEqual(1);

            });

        });

        describe('->names()', function () {

            context('when no format is given', function () {

                it('should return an array containing the placeholder name', function () {

                    $test = $this->placeholders->names();

                    expect($test)->toEqual(['p1']);

                });

            });

            context('when a format is given', function () {

                it('should return an array containing the formatted placeholder name', function () {

                    $test = $this->placeholders->names('p:%s');

                    expect($test)->toEqual(['p:p1']);

                });

            });

        });

        describe('->from()', function () {

            context('when the position is 0', function () {

                it('should return a new placeholder sequence containing the placeholder', function () {

                    $test = $this->placeholders->from(0);

                    expect($test)->not->toBe($this->placeholders);
                    expect($test)->toEqual(new PlaceholderSequence('p1'));

                });

            });

            context('when the position is more than 0', function () {

                it('should return a new empty placeholder sequence', function () {

                    $test = $this->placeholders->from(1);

                    expect($test)->not->toBe($this->placeholders);
                    expect($test)->toEqual(new PlaceholderSequence);

                });

            });

        });

        describe('->unbound()', function () {

            context('when the first given argument is bound', function () {

                it('should return a new empty placeholder sequence', function () {

                    $test = $this->placeholders->unbound(...[
                        'a1',
                        Placeholder::class,
                        'a3',
                        Placeholder::class,
                        'a5',
                    ]);

                    expect($test)->not->toBe($this->placeholders);
                    expect($test)->toEqual(new PlaceholderSequence);

                });

            });

            context('when the first given argument is not bound', function () {

                it('should return a new placeholder sequence containing the placeholder', function () {

                    $test = $this->placeholders->unbound(...[
                        Placeholder::class,
                        Placeholder::class,
                        'a3',
                        Placeholder::class,
                        'a5',
                    ]);

                    expect($test)->not->toBe($this->placeholders);
                    expect($test)->toEqual(new PlaceholderSequence('p1'));

                });

            });

        });

        describe('->with()', function () {

            it('should return a new placeholder sequence containing the placeholder and the given placeholders', function () {

                $test = $this->placeholders->with('p2', 'p3', 'p4');

                expect($test)->not->toBe($this->placeholders);
                expect($test)->toEqual(new PlaceholderSequence('p1', 'p2', 'p3', 'p4'));

            });

        });

        describe('->str()', function () {

            context('when no separator is given', function () {

                it('should return an string representation of the placeholder sequence', function () {

                    $test = $this->placeholders->str('p:%s');

                    expect($test)->toEqual('p:p1');

                });

            });

            context('when a separator is given', function () {

                it('should return an string representation of the placeholder sequence', function () {

                    $test = $this->placeholders->str('p:%s', '>');

                    expect($test)->toEqual('p:p1');

                });

            });

        });

        describe('->__toString()', function () {

            it('should return a default string representation of the placeholder sequence', function () {

                $test = (string) $this->placeholders;

                expect($test)->toEqual('\'p1\'');

            });

        });

    });

    context('when there is more than one placeholder', function () {

        beforeEach(function () {

            $this->placeholders = new PlaceholderSequence('p1', 'p2', 'p3', 'p4', 'p5');

        });

        describe('->number()', function () {

            it('should return the number of placeholders', function () {

                $test = $this->placeholders->number();

                expect($test)->toEqual(5);

            });

        });

        describe('->names()', function () {

            context('when no format is given', function () {

                it('should return an array containing the placeholder names', function () {

                    $test = $this->placeholders->names();

                    expect($test)->toEqual(['p1', 'p2', 'p3', 'p4', 'p5']);

                });

            });

            context('when a format is given', function () {

                it('should return an array containing the formatted placeholder names', function () {

                    $test = $this->placeholders->names('p:%s');

                    expect($test)->toEqual(['p:p1', 'p:p2', 'p:p3', 'p:p4', 'p:p5']);

                });

            });

        });

        describe('->from()', function () {

            it('should return a new placeholder sequence containing only the placeholders starting from the given position', function () {

                $test = $this->placeholders->from(2);

                expect($test)->not->toBe($this->placeholders);
                expect($test)->toEqual(new PlaceholderSequence('p3', 'p4', 'p5'));

            });

        });

        describe('->unbound()', function () {

            it('should return a new placeholder sequence containing only the placeholders at the same position as the given unbound arguments', function () {

                $test = $this->placeholders->unbound(...[
                    'a1',
                    Placeholder::class,
                    'a3',
                    Placeholder::class,
                    'a5',
                ]);

                expect($test)->not->toBe($this->placeholders);
                expect($test)->toEqual(new PlaceholderSequence('p2', 'p4'));

            });

        });

        describe('->with()', function () {

            it('should return a new placeholder sequence containing the given placeholders', function () {

                $test = $this->placeholders->with('p6', 'p7', 'p8');

                expect($test)->not->toBe($this->placeholders);
                expect($test)->toEqual(new PlaceholderSequence(...[
                    'p1', 'p2', 'p3', 'p4', 'p5', 'p6', 'p7', 'p8',
                ]));

            });

        });

        describe('->str()', function () {

            context('when no separator is given', function () {

                it('should return an string representation of the placeholder sequence with the default separator', function () {

                    $test = $this->placeholders->str('p:%s');

                    expect($test)->toEqual('p:p1, p:p2, p:p3, p:p4, p:p5');

                });

            });

            context('when a separator is given', function () {

                it('should return an string representation of the placeholder sequence with the given separator', function () {

                    $test = $this->placeholders->str('p:%s', '>');

                    expect($test)->toEqual('p:p1>p:p2>p:p3>p:p4>p:p5');

                });

            });

        });

        describe('->__toString()', function () {

            it('should return a default string representation of the placeholder sequence', function () {

                $test = (string) $this->placeholders;

                expect($test)->toEqual('\'p1\', \'p2\', \'p3\', \'p4\', \'p5\'');

            });

        });

    });

});
