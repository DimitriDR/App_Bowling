<?php

class Round
{
    /**
     * @var int Valeur du premier lancer
     **/
    private int $first_throw;
    /**
     * @var int Valeur du second lancer
     **/
    private int $second_throw;

    /**
     * Assesseur pour avoir le premier lancer
     * @return int
     */
    public function getFirstThrow(): int
    {
        return $this->first_throw;
    }

    /**
     * Mutateur pour le premier lancer
     * @param int $throw_value Valeur du premier lancer
     * @throws InvalidArgumentException
     */
    public function setFirstThrow(int $throw_value): void
    {
        if ($throw_value < 0 || $throw_value > 10) {
            throw new InvalidArgumentException("La valeur du lancer doit être comprise entre 0 et 10");
        }

        $this->first_throw = $throw_value;
    }

    /**
     * Assesseur pour avoir le second lancer
     * @return int
     */
    public function getSecondThrow(): int
    {
        return $this->second_throw;
    }

    /**
     * Mutateur pour le second lancer
     * @param int $second_throw
     * @throws InvalidArgumentException
     */
    public function setSecondThrow(int $second_throw): void
    {
        if ($second_throw < 0 || $second_throw > 10) {
            throw new InvalidArgumentException("La valeur du lancer doit être comprise entre 0 et 10");
        }

        $this->second_throw = $second_throw;
    }
}