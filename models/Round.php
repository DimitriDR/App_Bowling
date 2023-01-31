<?php
require_once "Game.php";

class Round
{
    /**
     * @var int Valeur du premier lancer
     **/
    private int $first_throw = -1;

    /**
     * @var int Valeur du second lancer
     *
     * Si le joueur fait un strike, il n'a pas le droit au second lancer
     **/
    private int $second_throw = -1;

    /**
     * @var int Valeur du troisième lancer
     *
     * Cette variable a une valeur quand le joueur fait un strike ou un spare au dernier tour
     **/
    private int $third_throw = 0;

    /**
     * Assesseur pour la valeur du premier lancer
     * @return int Valeur du premier lancer
     **/
    public function get_first_throw(): int
    {
        return $this->first_throw;
    }

    /**
     * Assesseur pour la valeur du deuxième lancer
     * @return int Valeur du deuxième lancer
     **/
    public function get_second_throw(): int
    {
        return $this->second_throw;
    }

    /**
     * Assesseur pour la valeur du troisième lancer
     * @return int Valeur du troisième lancer
     **/
    public function get_third_throw(): int
    {
        return $this->third_throw;
    }

    /**
     * Mutateur pour le premier lancer
     *
     * @param int $throw_value Valeur du premier lancer comprises en 0 et Game::MAX_PIN
     * @throws InvalidArgumentException Exception levée si la valeur du lancer n'est pas comprise dans l'intervalle [0, Game::MAX_PIN]
    **/
    public function set_first_throw(int $throw_value): void
    {
        if ($throw_value < 0 || $throw_value > Game::MAX_PIN)
        {
            throw new InvalidArgumentException("La valeur du lancer doit être comprise entre 0 et " . Game::MAX_PIN . " inclus");
        }

        $this->first_throw = $throw_value;
    }

    /**
     * Mutateur pour le deuxième lancer
     *
     * @param int $throw_value Valeur du deuxième lancer comprises en 0 et Game::MAX_PIN
     * @throws InvalidArgumentException Exception levée si la valeur du lancer n'est pas comprise dans l'intervalle [0, Game::MAX_PIN]
     * @throws LogicException Exception levée si on veut définir une valeur alors que le joueur a fait un strike au premier lancer
     **/
    public function set_second_throw(int $throw_value): void
    {
        if ($throw_value < 0 || $throw_value > Game::MAX_PIN)
        {
            throw new InvalidArgumentException("La valeur du lancer doit être comprise entre 0 et " . Game::MAX_PIN . " inclus");
        }

        $this->second_throw = $throw_value;
    }

    /**
     * Mutateur pour le troisième lancer
     *
     * @param int $throw_value Valeur du deuxième lancer comprises en 0 et Game::MAX_PIN
     * @throws InvalidArgumentException Exception levée si la valeur du lancer n'est pas comprise dans l'intervalle [0,Game::MAX_PIN]
     **/
    public function set_third_throw(int $throw_value): void
    {
        if ($throw_value < 0 || $throw_value > Game::MAX_PIN)
        {
            throw new InvalidArgumentException("La valeur du lancer doit être comprise entre 0 et " . Game::MAX_PIN . " inclus");
        }

        $this->third_throw = $throw_value;
    }
}
