<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sylius\Bundle\CoreBundle\DataFixtures\ORM;

use Doctrine\Common\Persistence\ObjectManager;
use Sylius\Bundle\PromotionsBundle\Model\RuleInterface;
use Sylius\Bundle\PromotionsBundle\Model\ActionInterface;

/**
 * Default promotion fixtures.
 *
 * @author Saša Stamenković <umpirsky@gmail.com>
 */
class LoadPromotionsData extends DataFixture
{
    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager)
    {
        $promotion = $this->createPromotion(
            'Año nuevo',
            '$500 de regalo por la compra de 2 pares o más',
            array($this->createRule(RuleInterface::TYPE_ITEM_COUNT, array('count' => 2, 'equal' => true))),
            array($this->createAction(ActionInterface::TYPE_FIXED_DISCOUNT, array('amount' => 500000)))
        );
        $manager->persist($promotion);

        $promotion = $this->createPromotion(
            'Navidad',
            '25% en compras mayores a $500',
            array($this->createRule(RuleInterface::TYPE_ITEM_TOTAL, array('amount' => 500000, 'equal' => true))),
            array($this->createAction(ActionInterface::TYPE_PERCENTAGE_DISCOUNT, array('percentage' => 25)))
        );
        $manager->persist($promotion);

        $promotion = $this->createPromotion(
        		'Devolucion de compra',
        		'Devolucion de compra por $1500',
        		array(),
        		array($this->createAction(ActionInterface::TYPE_FIXED_DISCOUNT, array('amount' => 1500)))
        );
        $manager->persist($promotion);
        
        $manager->flush();
    }

    /**
     * {@inheritdoc}
     */
    public function getOrder()
    {
        return 1;
    }

    /**
     * Create promotion rule of given type and configuration.
     *
     * @param string $type
     * @param array  $configuration
     *
     * @return PromotionRuleInterface
     */
    private function createRule($type, array $configuration)
    {
        $rule = $this
            ->getPromotionRuleRepository()
            ->createNew()
        ;

        $rule->setType($type);
        $rule->setConfiguration($configuration);

        return $rule;
    }

    /**
     * Create promotion action of given type and configuration.
     *
     * @param string $type
     * @param array  $configuration
     *
     * @return PromotionActionInterface
     */
    private function createAction($type, array $configuration)
    {
        $action = $this
            ->getPromotionActionRepository()
            ->createNew()
        ;

        $action->setType($type);
        $action->setConfiguration($configuration);

        return $action;
    }

    /**
     * Create promotion with set of rules and actions.
     *
     * @param string $name
     * @param string $description
     * @param array  $rules
     * @param array  $actions
     *
     * @return PromotionInterface
     */
    private function createPromotion($name, $description, array $rules, array $actions)
    {
        $promotion = $this
            ->getPromotionRepository()
            ->createNew()
        ;

        $promotion->setName($name);
        $promotion->setDescription($description);

        foreach ($rules as $rule) {
            $promotion->addRule($rule);
        }
        foreach ($actions as $action) {
            $promotion->addAction($action);
        }

        $this->setReference('Sylius.Promotion.'.$name, $promotion);

        return $promotion;
    }
}
