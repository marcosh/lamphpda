<?php

declare(strict_types=1);

namespace Marcosh\LamPHPda\Typeclass\Extra;

use Marcosh\LamPHPda\Brand\Brand;
use Marcosh\LamPHPda\HK\HK1;
use Marcosh\LamPHPda\Typeclass\Apply;

/**
 * @template F of Brand
 *
 * @psalm-immutable
 */
final class ExtraApply
{
    /**
     * @param Apply<F> $apply
     */
    public function __construct(private readonly Apply $apply)
    {
    }

    /**
     * @template A
     * @template B
     * @template C
     * @param callable(A, B): C $f
     * @param HK1<F, A> $a
     * @param HK1<F, B> $b
     * @return HK1<F, C>
     */
    public function lift2(callable $f, HK1 $a, HK1 $b): HK1
    {
        $curriedF =
            /**
             * @param A $ca
             * @return callable(B): C
             */
            static fn (mixed $ca): \Closure =>
                /**
                 * @param B $cb
                 * @return C
                 */
                static fn (mixed $cb): mixed => $f($ca, $cb);

        return $this->apply->apply(
            $this->apply->map($curriedF, $a),
            $b
        );
    }

    /**
     * @template A
     * @template B
     * @template C
     * @template D
     * @param callable(A, B, C): D $f
     * @param HK1<F, A> $a
     * @param HK1<F, B> $b
     * @param HK1<F, C> $c
     * @return HK1<F, D>
     */
    public function lift3(callable $f, HK1 $a, HK1 $b, HK1 $c): HK1
    {
        $curriedF =
            /**
             * @param A $ca
             * @return callable(B): (callable(C): D)
             */
            static fn (mixed $ca): \Closure =>
                /**
                 * @param B $cb
                 * @return callable(C): D
                 */
                static fn (mixed $cb): \Closure =>
                    /**
                     * @param C $cc
                     * @return D
                     */
                    static fn (mixed $cc): mixed => $f($ca, $cb, $cc);

        return $this->apply->apply(
            $this->apply->apply(
                $this->apply->map($curriedF, $a),
                $b
            ),
            $c
        );
    }

    /**
     * @template A
     * @template B
     * @template C
     * @template D
     * @template E
     * @param callable(A, B, C, D): E $f
     * @param HK1<F, A> $a
     * @param HK1<F, B> $b
     * @param HK1<F, C> $c
     * @param HK1<F, D> $d
     * @return HK1<F, E>
     */
    public function lift4(callable $f, HK1 $a, HK1 $b, HK1 $c, HK1 $d): HK1
    {
        $curriedF =
            /**
             * @param A $ca
             * @return callable(B): (callable(C): (callable(D): E))
             */
            static fn (mixed $ca): \Closure =>
                /**
                 * @param B $cb
                 * @return callable(C): (callable(D): E)
                 */
                static fn (mixed $cb): \Closure =>
                    /**
                     * @param C $cc
                     * @return callable(D): E
                     */
                    static fn (mixed $cc): \Closure =>
                        /**
                         * @param D $cd
                         * @return E
                         */
                        static fn (mixed $cd): mixed => $f($ca, $cb, $cc, $cd);

        return $this->apply->apply(
            $this->apply->apply(
                $this->apply->apply(
                    $this->apply->map($curriedF, $a),
                    $b
                ),
                $c
            ),
            $d
        );
    }

    /**
     * @template A
     * @template B
     * @template C
     * @template D
     * @template E
     * @template G
     * @param callable(A, B, C, D, E): G $f
     * @param HK1<F, A> $a
     * @param HK1<F, B> $b
     * @param HK1<F, C> $c
     * @param HK1<F, D> $d
     * @param HK1<F, E> $e
     * @return HK1<F, G>
     */
    public function lift5(callable $f, HK1 $a, HK1 $b, HK1 $c, HK1 $d, HK1 $e): HK1
    {
        $curriedF =
            /**
             * @param A $ca
             * @return callable(B): (callable(C): (callable(D): (callable(E): G)))
             */
            static fn (mixed $ca): \Closure =>
                /**
                 * @param B $cb
                 * @return callable(C): (callable(D): (callable(E): G))
                 */
                static fn (mixed $cb): \Closure =>
                    /**
                     * @param C $cc
                     * @return callable(D): (callable(E): G)
                     */
                    static fn (mixed $cc): \Closure =>
                        /**
                         * @param D $cd
                         * @return callable(E): G
                         */
                        static fn (mixed $cd): \Closure =>
                            /**
                             * @param E $ce
                             * @return G
                             */
                            static fn (mixed $ce): mixed => $f($ca, $cb, $cc, $cd, $ce);

        return $this->apply->apply(
            $this->apply->apply(
                $this->apply->apply(
                    $this->apply->apply(
                        $this->apply->map($curriedF, $a),
                        $b
                    ),
                    $c
                ),
                $d
            ),
            $e
        );
    }

    /**
     * @template A
     * @template B
     * @template C
     * @template D
     * @template E
     * @template G
     * @template H
     * @param callable(A, B, C, D, E, G): H $f
     * @param HK1<F, A> $a
     * @param HK1<F, B> $b
     * @param HK1<F, C> $c
     * @param HK1<F, D> $d
     * @param HK1<F, E> $e
     * @param HK1<F, G> $g
     * @return HK1<F, H>
     */
    public function lift6(callable $f, HK1 $a, HK1 $b, HK1 $c, HK1 $d, HK1 $e, HK1 $g): HK1
    {
        $curriedF =
            /**
             * @param A $ca
             * @return callable(B): (callable(C): (callable(D): (callable(E): (callable(G): H))))
             */
            static fn (mixed $ca): \Closure =>
                /**
                 * @param B $cb
                 * @return callable(C): (callable(D): (callable(E): (callable(G): H)))
                 */
                static fn (mixed $cb): \Closure =>
                    /**
                     * @param C $cc
                     * @return callable(D): (callable(E): (callable(G): H))
                     */
                    static fn (mixed $cc): \Closure =>
                        /**
                         * @param D $cd
                         * @return callable(E): (callable(G): H)
                         */
                        static fn (mixed $cd): \Closure =>
                            /**
                             * @param E $ce
                             * @return callable(G): H
                             */
                            static fn (mixed $ce): \Closure =>
                                /**
                                 * @param G $cg
                                 * @return H
                                 */
                                static fn (mixed $cg): mixed => $f($ca, $cb, $cc, $cd, $ce, $cg);

        return $this->apply->apply(
            $this->apply->apply(
                $this->apply->apply(
                    $this->apply->apply(
                        $this->apply->apply(
                            $this->apply->map($curriedF, $a),
                            $b
                        ),
                        $c
                    ),
                    $d
                ),
                $e
            ),
            $g
        );
    }

    /**
     * @template A
     * @template B
     * @template C
     * @template D
     * @template E
     * @template G
     * @template H
     * @template I
     * @param callable(A, B, C, D, E, G, H): I $f
     * @param HK1<F, A> $a
     * @param HK1<F, B> $b
     * @param HK1<F, C> $c
     * @param HK1<F, D> $d
     * @param HK1<F, E> $e
     * @param HK1<F, G> $g
     * @param HK1<F, H> $h
     * @return HK1<F, I>
     */
    public function lift7(callable $f, HK1 $a, HK1 $b, HK1 $c, HK1 $d, HK1 $e, HK1 $g, HK1 $h): HK1
    {
        $curriedF =
            /**
             * @param A $ca
             * @return callable(B): (callable(C): (callable(D): (callable(E): (callable(G): (callable(H): I)))))
             */
            static fn (mixed $ca): \Closure =>
                /**
                 * @param B $cb
                 * @return callable(C): (callable(D): (callable(E): (callable(G): (callable(H): I))))
                 */
                static fn (mixed $cb): \Closure =>
                    /**
                     * @param C $cc
                     * @return callable(D): (callable(E): (callable(G): (callable(H): I)))
                     */
                    static fn (mixed $cc): \Closure =>
                        /**
                         * @param D $cd
                         * @return callable(E): (callable(G): (callable(H): I))
                         */
                        static fn (mixed $cd): \Closure =>
                            /**
                             * @param E $ce
                             * @return callable(G): (callable(H): I)
                             */
                            static fn (mixed $ce): \Closure =>
                                /**
                                 * @param G $cg
                                 * @return callable(H): I
                                 */
                                static fn (mixed $cg): \Closure =>
                                    /**
                                     * @param H $ch
                                     * @return I
                                     */
                                    static fn (mixed $ch): mixed => $f($ca, $cb, $cc, $cd, $ce, $cg, $ch);

        return $this->apply->apply(
            $this->apply->apply(
                $this->apply->apply(
                    $this->apply->apply(
                        $this->apply->apply(
                            $this->apply->apply(
                                $this->apply->map($curriedF, $a),
                                $b
                            ),
                            $c
                        ),
                        $d
                    ),
                    $e
                ),
                $g
            ),
            $h
        );
    }

    /**
     * @template A
     * @template B
     * @template C
     * @template D
     * @template E
     * @template G
     * @template H
     * @template I
     * @template J
     * @param callable(A, B, C, D, E, G, H, I): J $f
     * @param HK1<F, A> $a
     * @param HK1<F, B> $b
     * @param HK1<F, C> $c
     * @param HK1<F, D> $d
     * @param HK1<F, E> $e
     * @param HK1<F, G> $g
     * @param HK1<F, H> $h
     * @param HK1<F, I> $i
     * @return HK1<F, J>
     */
    public function lift8(callable $f, HK1 $a, HK1 $b, HK1 $c, HK1 $d, HK1 $e, HK1 $g, HK1 $h, HK1 $i): HK1
    {
        $curriedF =
            /**
             * @param A $ca
             * @return callable(B): (callable(C): (callable(D): (callable(E): (callable(G): (callable(H): (callable(I): J))))))
             */
            static fn (mixed $ca): \Closure =>
            /**
             * @param B $cb
             * @return callable(C): (callable(D): (callable(E): (callable(G): (callable(H): (callable(I): J)))))
             */
            static fn (mixed $cb): \Closure =>
            /**
             * @param C $cc
             * @return callable(D): (callable(E): (callable(G): (callable(H): (callable(I): J))))
             */
            static fn (mixed $cc): \Closure =>
            /**
             * @param D $cd
             * @return callable(E): (callable(G): (callable(H): (callable(I): J)))
             */
            static fn (mixed $cd): \Closure =>
            /**
             * @param E $ce
             * @return callable(G): (callable(H): (callable(I): J))
             */
            static fn (mixed $ce): \Closure =>
            /**
             * @param G $cg
             * @return callable(H): (callable(I): J)
             */
            static fn (mixed $cg): \Closure =>
            /**
             * @param H $ch
             * @return callable(I): J
             */
            static fn (mixed $ch): \Closure =>
            /**
             * @param I $ci
             * @return J
             */
            static fn (mixed $ci): mixed => $f($ca, $cb, $cc, $cd, $ce, $cg, $ch, $ci);

        return $this->apply->apply(
            $this->apply->apply(
                $this->apply->apply(
                    $this->apply->apply(
                        $this->apply->apply(
                            $this->apply->apply(
                                $this->apply->apply(
                                    $this->apply->map($curriedF, $a),
                                    $b
                                ),
                                $c
                            ),
                            $d
                        ),
                        $e
                    ),
                    $g
                ),
                $h,
            ),
            $i
        );
    }
}
