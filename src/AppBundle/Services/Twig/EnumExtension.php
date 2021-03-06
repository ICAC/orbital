<?php

namespace AppBundle\Services\Twig;

use AppBundle\Services\Enum\BadgeCategory;
use AppBundle\Services\Enum\BadgeState;
use AppBundle\Services\Enum\BowType;
use AppBundle\Services\Enum\Classification;
use AppBundle\Services\Enum\Environment;
use AppBundle\Services\Enum\Gender;
use AppBundle\Services\Enum\Skill;
use AppBundle\Services\Enum\Unit;

class EnumExtension extends \Twig_Extension implements \Twig_Extension_GlobalsInterface
{
    public function getFilters()
    {
        return [new \Twig_SimpleFilter('enum', [$this, 'enum_filter'])];
    }

    public function getGlobals()
    {
        return ['Enum' => [
            'bowtype' => BowType::$choices,
            'gender' => Gender::$choices,
            'skill' => Skill::$choices,
            'unit' => Unit::$choices,
            'badgecat' => BadgeCategory::$choices,
            'badgestate' => BadgeState::$choices,
            'classification' => Classification::$choices,
            'short_classification' => Classification::$shortChoices,
            'environment' => Environment::$choices,
        ]];
    }

    public function enum_filter($value, $enum)
    {
        return $this->getGlobals()['Enum'][$enum][$value];
    }

    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName()
    {
        return 'app_enum_ext';
    }
}
