<?php

class Round
{
    /**
     * @var int Valeur du premier lancer
     **/
    private int $first_throw;
    /**
     * @var int Valeur du second lancer
     * @note Si le joueur fait un strike, il n'a pas le droit au second lancer
     **/
    private int $second_throw;

    /**
     * @var int Valeur du troisième lancer
     * @note Utilisé uniquement pour le dernier round
     * @note Si le joueur fait un strike ou un spare, il a droit à un troisième lancer
     */
    private int $third_throw;

    /**
     * @var int Numéro du tour
     * @note Le premier tour est le tour 1
     * @note Le dernier tour est le tour 10
     */
    private int $turn;

    /**
     * Constructeur de la classe Round
     * @param array $round_data Tableau contenant les valeurs des lancers
     * @param int $turn Numéro du tour
     * @throws InvalidArgumentException Si le tableau contient plus de 3 valeurs
     */
    public function __construct(array $round_data, int $turn)
    {
        if ($turn < 1 || $turn > 10)
        {
            throw new InvalidArgumentException("Le numéro de tour doit être compris entre 1 et 10");
        }

        if (count($round_data) > 2 && $turn != 10)
        {
            throw new InvalidArgumentException("Le tableau contient plus de 2 valeurs pour un tour différent du 10");
        }

        if (count($round_data) > 3 && $turn == 10)
        {
            throw new InvalidArgumentException("Le tableau contient plus de 3 valeurs pour le tour 10");
        }

        if ($turn == 10)
        {
            $this->third_throw = $round_data[2];
        }

        $this->first_throw = $round_data[0];
        $this->second_throw = $round_data[1];
        $this->turn = $turn;
    }

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
        if ($this->first_throw + $throw_value > 10)
        {
            throw new InvalidArgumentException("La somme des deux lancers ne peut pas être supérieure à 10");
        }

        if ($this->first_throw == 10)
        {
            throw new InvalidArgumentException("Le joueur a fait un strike, il n'a pas le droit au second lancer");
        }

        $this->second_throw = $throw_value;
    }

    /**
     * Assesseur pour avoir le troisième lancer
     * @return int
     */
    public function getThirdThrow(): int
    {
        return $this->third_throw;
    }

    /**
     * Mutateur pour le troisième lancer
     * @param int $third_throw
     * @throws InvalidArgumentException
     */
    public function setThirdThrow(int $third_throw): void
    {
        if ($third_throw < 0 || $third_throw > 10)
        {
            throw new InvalidArgumentException("La valeur du lancer doit être comprise entre 0 et 10");
        }

        if ($this->turn != 10)
        {
            throw new InvalidArgumentException("Le troisième lancer n'est utilisé que pour le dernier tour");
        }

        if ($this->first_throw + $this->second_throw < 10)
        {
            throw new InvalidArgumentException("Le troisième lancer n'est utilisé que si le joueur fait un strike ou un spare");
        }

        $this->third_throw = $third_throw;
    }

    /**
     * Fonction qui permet de savoir si le joueur a fait un strike
     * @return bool
     */
    public function isStrike(): bool
    {
        if ($this->first_throw === 10)
        {
            $this->setSecondThrow(0);
            return true;
        }
        return false;
    }

    /**
     * Fonction qui permet de savoir si le joueur a fait un spare
     * @return bool
     */

    public function isSpare(): bool
    {
        return $this->first_throw + $this->second_throw === 10;
    }

    /**
     * Fonction qui retourne le score du round
     * @return array
     */
    public function getScore(): array
    {
        return [$this->first_throw, $this->second_throw];
    }

    /**
     * Fonction qui attribue le score du round au joueur
     * @param Player $player
     */
    public function setScore(Player $player): void
    {
        $player->set_marked_points($this->getScore());
    }

}