<?php

/**
 * Hoa
 *
 *
 * @license
 *
 * New BSD License
 *
 * Copyright © 2007-2014, Ivan Enderlin. All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions are met:
 *     * Redistributions of source code must retain the above copyright
 *       notice, this list of conditions and the following disclaimer.
 *     * Redistributions in binary form must reproduce the above copyright
 *       notice, this list of conditions and the following disclaimer in the
 *       documentation and/or other materials provided with the distribution.
 *     * Neither the name of the Hoa nor the names of its contributors may be
 *       used to endorse or promote products derived from this software without
 *       specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS"
 * AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE
 * IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE
 * ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT HOLDERS AND CONTRIBUTORS BE
 * LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR
 * CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF
 * SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS
 * INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN
 * CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE)
 * ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 * POSSIBILITY OF SUCH DAMAGE.
 */

namespace Hoa\Iterator\Test\Unit;

use Hoa\Test;
use Hoa\Iterator as LUT;

/**
 * Class \Hoa\Iterator\Test\Unit\Demultiplexer.
 *
 * Test suite of the demultiplexer iterator.
 *
 * @author     Ivan Enderlin <ivan.enderlin@hoa-project.net>
 * @copyright  Copyright © 2007-2014 Ivan Enderlin.
 * @license    New BSD License
 */

class Demultiplexer extends Test\Unit\Suite {

    public function case_classic ( ) {

        $this
            ->given(
                $counter  = new LUT\Counter(0, 10, 1),
                $multiple = new LUT\Multiple(),
                $multiple->attachIterator($counter),
                $multiple->attachIterator(clone $counter),
                $demultiplexer = new LUT\Demultiplexer(
                    $multiple,
                    function ( $current ) {

                        return $current[0] * $current[1];
                    }
                )
            )
            ->when($result = iterator_to_array($demultiplexer, false))
            ->then
                ->array($result)
                    ->isEqualTo([
                        0,
                        1,
                        4,
                        9,
                        16,
                        25,
                        36,
                        49,
                        64,
                        81
                    ]);
    }

    public function case_associative_keys ( ) {

        $this
            ->given(
                $counter  = new LUT\Counter(0, 10, 1),
                $multiple = new LUT\Multiple(
                    LUT\Multiple::MIT_NEED_ANY
                  | LUT\Multiple::MIT_KEYS_ASSOC
                ),
                $multiple->attachIterator($counter,       'one'),
                $multiple->attachIterator(clone $counter, 'two'),
                $demultiplexer = new LUT\Demultiplexer(
                    $multiple,
                    function ( $current ) {

                        return $current['one'] * $current['two'];
                    }
                )
            )
            ->when($result = iterator_to_array($demultiplexer, false))
            ->then
                ->array($result)
                    ->isEqualTo([
                        0,
                        1,
                        4,
                        9,
                        16,
                        25,
                        36,
                        49,
                        64,
                        81
                    ]);
    }
}
