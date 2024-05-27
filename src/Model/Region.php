<?php

namespace App\Model;

class Region
{

    private string $code;
    private string $nom;

    /**
     * @return string
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * @param string $code
     * @return Region
     */
    public function setCode(string $code): Region
    {
        $this->code = $code;
        return $this;
    }

    /**
     * @return string
     */
    public function getNom(): string
    {
        return $this->nom;
    }

    /**
     * @param string $nom
     * @return Region
     */
    public function setNom(string $nom): Region
    {
        $this->nom = $nom;
        return $this;
    }


}