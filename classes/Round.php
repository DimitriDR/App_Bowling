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
     * Assesseur pour avoir la valeur du premier lancer
     * @return int Valeur du premier lancer
     */
    public function getFirstThrow(): int
    {
        return $this->first_throw;
    }

    /**
     * Mutateur pour le premier lancer
     * @param int $throw_value Valeur du premier lancer comprises en 0 et 10
     * @throws InvalidArgumentException Exception levée si la valeur du lancer n'est pas comprise dans l'intervalle [0, 10]
     */
    public function setFirstThrow(int $throw_value): void
    {
        if ($throw_value < 0 || $throw_value > 10)
        {
            throw new InvalidArgumentException("La valeur du lancer doit être comprise entre 0 et 10 inclus");
        }

        $this->first_throw = $throw_value;
    }

    /**
     * Assesseur pour avoir la valeur du second lancer
     * @return int Valeur du second lancer
     */
    public function getSecondThrow(): int
    {
        return $this->second_throw;
    }

    /**
     * Mutateur pour le second lancer
     * @param int $throw_value Valeur du second lancer comprises en 0 et 10
     * @throws InvalidArgumentException Exception levée si la valeur du lancer n'est pas comprise dans l'intervalle [0, 10]
     */
    public function setSecondThrow(int $throw_value): void
    {
        if ($throw_value < 0 || $throw_value > 10)
        {
            throw new InvalidArgumentException("La valeur du lancer doit être comprise entre 0 et 10");
        }

        $this->second_throw = $throw_value;
    }
}