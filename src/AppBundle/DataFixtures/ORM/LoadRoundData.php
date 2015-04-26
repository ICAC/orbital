<?php


namespace AppBundle\DataFixtures\ORM;


use AppBundle\Entity\Person;
use AppBundle\Entity\Round;
use AppBundle\Entity\RoundTarget;
use AppBundle\Services\Enum\ScoreZones;
use AppBundle\Services\Enum\Skill;
use AppBundle\Services\Enum\Unit;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadRoundData extends AbstractFixture implements OrderedFixtureInterface
{

    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $round1 = new Round();
        $round1->setName("Round 1");

        $round2 = new Round();
        $round2->setName("Round 2");

        $target1 = new RoundTarget();
        $target1->setArrowCount(60);
        $target1->setEndSize(6);
        $target1->setDistanceValue(18);
        $target1->setDistanceUnit(Unit::METER);
        $target1->setTargetValue(60);
        $target1->setTargetUnit(Unit::CENTIMETER);
        $target1->setScoringZones(ScoreZones::METRIC);

        $target2 = new RoundTarget();
        $target2->setArrowCount(60);
        $target2->setEndSize(6);
        $target2->setDistanceValue(18);
        $target2->setDistanceUnit(Unit::METER);
        $target2->setTargetValue(40);
        $target2->setTargetUnit(Unit::CENTIMETER);
        $target2->setScoringZones(ScoreZones::METRIC);

        $target3 = new RoundTarget();
        $target3->setArrowCount(60);
        $target3->setEndSize(6);
        $target3->setDistanceValue(25);
        $target3->setDistanceUnit(Unit::METER);
        $target3->setTargetValue(60);
        $target3->setTargetUnit(Unit::CENTIMETER);
        $target3->setScoringZones(ScoreZones::METRIC);

        $round1->addTarget($target1);
        $round2->addTarget($target2);
        $round2->addTarget($target3);

        $manager->persist($round1);
        $manager->persist($round2);

        $manager->flush();
    }

    /**
     * Get the order of this fixture
     *
     * @return integer
     */
    public function getOrder()
    {
        return 1;
    }
}
