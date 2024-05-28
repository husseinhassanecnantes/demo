<?php

namespace App\Model;

use App\Validator\UniqueCategoryName;
use Symfony\Component\Validator\Constraints as Assert;

class CategoryDTO
{
    #[UniqueCategoryName()]
    #[Assert\NotBlank(message: 'Veuillez saisir une catégorie!')]
    #[Assert\Length(
        min: 2,
        max: 180,
        minMessage: 'La catégorie devrait avoir au moins 2 caractères',
        maxMessage: 'La catégorie devrait avoir maximum 180 caractères'
    )]
    public string $name;

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return CategoryDTO
     */
    public function setName(string $name): CategoryDTO
    {
        $this->name = $name;
        return $this;
    }


}