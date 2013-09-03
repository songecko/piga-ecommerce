<?php

namespace Gecko\SyliusBackendBundle\Menu;

use Knp\Menu\ItemInterface;
use Symfony\Component\HttpFoundation\Request;
use Knp\Menu\FactoryInterface;
use Symfony\Component\Translation\TranslatorInterface;
use Symfony\Component\Security\Core\SecurityContextInterface;
use JMS\TranslationBundle\Annotation\Ignore;

/**
 * Main menu builder.
 *
 */
class MenuBuilder
{
	/**
	 * Menu factory.
	 *
	 * @var FactoryInterface
	 */
	protected $factory;
	
	/**
	 * Security context.
	 *
	 * @var SecurityContextInterface
	 */
	protected $securityContext;
	
	/**
	 * Translator instance.	
	 *
	 * @var TranslatorInterface
	 */
	protected $translator;
	
	/**
	 * Constructor.
	 *
	 * @param FactoryInterface         $factory
	 * @param SecurityContextInterface $securityContext
	 * @param TranslatorInterface      $translator
	 */
	public function __construct(FactoryInterface $factory, SecurityContextInterface $securityContext, TranslatorInterface $translator)
	{
		$this->factory = $factory;
		$this->securityContext = $securityContext;
		$this->translator = $translator;
	}
	
	/**
	 * Translate label.
	 *
	 * @param string $label
	 * @param array $parameters
	 *
	 * @return string
	 */
	protected function translate($label, $parameters = array())
	{
		return $this->translator->trans(/** @Ignore */ $label, $parameters, 'menu');
	}
	
    /**
     * Builds backend main menu.
     *
     * @param Request $request
     *
     * @return ItemInterface
     */
    public function createMainMenu(Request $request)
    {
        $menu = $this->factory->createItem('root', array(
            'childrenAttributes' => array(
                'class' => 'nav'
            )
        ));

        $menu->setCurrent($request->getRequestUri());

        $childOptions = array(
            'attributes'         => array('class' => 'dropdown'),
            'childrenAttributes' => array('class' => 'dropdown-menu'),
            'labelAttributes'    => array('class' => 'dropdown-toggle', 'data-toggle' => 'dropdown', 'href' => '#')
        );

        $this->addAssortmentMenu($menu, $childOptions, 'main');
        $this->addCustomersMenu($menu, $childOptions, 'main');
        $this->addSalesMenu($menu, $childOptions, 'main');
        //$this->addConfigurationMenu($menu, $childOptions, 'main');

        return $menu;
    }
	
    /**
     * Builds backend main menu account.
     *
     * @param Request $request
     *
     * @return ItemInterface
     */
    public function createMainMenuAccount(Request $request)
    {
    	$menu = $this->factory->createItem('root', array(
    			'childrenAttributes' => array(
    					'class' => 'nav pull-right'
    			)
    	));
    
    	$menu->setCurrent($request->getRequestUri());
    
    	$childOptions = array(
    			'attributes'         => array('class' => 'dropdown'),
    			'childrenAttributes' => array('class' => 'dropdown-menu'),
    			'labelAttributes'    => array('class' => 'dropdown-toggle', 'data-toggle' => 'dropdown', 'href' => '#')
    	);
    	
    	$this->addAccountMenu($menu, $childOptions, 'main');
    
    	return $menu;
    }
    
    /**
     * Builds backend sidebar menu.
     *
     * @param Request $request
     *
     * @return ItemInterface
     */
    public function createDashboardMenu(Request $request)
    {
    	$menu = $this->factory->createItem('root', array(
    		'childrenAttributes' => array(
    			'class' => 'nav nav-list well'
    		)
    	));
    
    	$menu->setCurrent($request->getRequestUri());
    
    	$childOptions = array(
    			'childrenAttributes' => array('class' => 'nav nav-list'),
    			'labelAttributes'    => array('class' => 'nav-header')
    	);
    
    	$this->addAssortmentMenu($menu, $childOptions, 'main');
    	$this->addCustomersMenu($menu, $childOptions, 'main');
    	$this->addSalesMenu($menu, $childOptions, 'main');
    	//$this->addConfigurationMenu($menu, $childOptions, 'main');
        $this->addAccountMenu($menu, $childOptions, 'main');
    
    	return $menu;
    }
    
    /**
     * Add assortment menu.
     *
     * @param ItemInterface $menu
     * @param array         $childOptions
     */
    protected function addAssortmentMenu(ItemInterface $menu, array $childOptions, $section)
    {
        $child = $menu
            ->addChild('assortment', $childOptions)
            ->setLabel($this->translate(sprintf('sylius.backend.menu.%s.assortment', $section)))
        ;

        $child->addChild('taxonomies', array(
            'route' => 'sylius_backend_taxonomy_index',
            'labelAttributes' => array('icon' => 'icon-tags'),
        ))->setLabel($this->translate(sprintf('sylius.backend.menu.%s.taxonomies', $section)));
        
        $child->addChild('options', array(
            'route' => 'sylius_backend_option_index',
            'labelAttributes' => array('icon' => 'icon-th'),
        ))->setLabel($this->translate(sprintf('sylius.backend.menu.%s.options', $section)));

        $child->addChild('products', array(
            'route' => 'sylius_backend_product_index',
            'labelAttributes' => array('icon' => 'icon-certificate'),
        ))->setLabel($this->translate(sprintf('sylius.backend.menu.%s.products', $section)));

        /*$child->addChild('stockables', array(
            'route' => 'sylius_backend_stockable_index',
            'labelAttributes' => array('icon' => 'icon-signal'),
        ))->setLabel($this->translate(sprintf('sylius.backend.menu.%s.stockables', $section)));*/


        /*$child->addChild('properties', array(
            'route' => 'sylius_backend_property_index',
            'labelAttributes' => array('icon' => 'icon-cog'),
        ))->setLabel($this->translate(sprintf('sylius.backend.menu.%s.properties', $section)));*/

        /*$child->addChild('prototypes', array(
            'route' => 'sylius_backend_prototype_index',
            'labelAttributes' => array('icon' => 'icon-cogs'),
        ))->setLabel($this->translate(sprintf('sylius.backend.menu.%s.prototypes', $section)));*/
    }

    /**
     * Add sales menu.
     *
     * @param ItemInterface $menu
     * @param array         $childOptions
     */
    protected function addSalesMenu(ItemInterface $menu, array $childOptions, $section)
    {
        $child = $menu
            ->addChild('sales', $childOptions)
            ->setLabel($this->translate(sprintf('sylius.backend.menu.%s.sales', $section)))
        ;

        $child->addChild('orders', array(
            'route' => 'sylius_backend_order_index',
            'labelAttributes' => array('icon' => 'icon-shopping-cart'),
        ))->setLabel($this->translate(sprintf('sylius.backend.menu.%s.orders', $section)));
        /*$child->addChild('new_order', array(
            'route' => 'sylius_backend_order_create',
            'labelAttributes' => array('icon' => 'icon-plus-sign'),
        ))->setLabel($this->translate(sprintf('sylius.backend.menu.%s.new_order', $section)));*/
        /*$child->addChild('payments', array(
            'route' => 'sylius_backend_payment_index',
            'labelAttributes' => array('icon' => 'icon-money'),
        ))->setLabel($this->translate(sprintf('sylius.backend.menu.%s.payments', $section)));*/

        /*$child->addChild('promotions', array(
            'route' => 'sylius_backend_promotion_index',
            'labelAttributes' => array('icon' => 'icon-bullhorn'),
        ))->setLabel($this->translate(sprintf('sylius.backend.menu.%s.promotions', $section)));
        $child->addChild('new_promotion', array(
            'route' => 'sylius_backend_promotion_create',
            'labelAttributes' => array('icon' => 'icon-plus-sign'),
        ))->setLabel($this->translate(sprintf('sylius.backend.menu.%s.new_promotion', $section)));*/
    }

    /**
     * Add customers menu.
     *
     * @param ItemInterface $menu
     * @param array         $childOptions
     */
    protected function addCustomersMenu(ItemInterface $menu, array $childOptions, $section)
    {
        $child = $menu
            ->addChild('customer', $childOptions)
            ->setLabel($this->translate(sprintf('sylius.backend.menu.%s.customer', $section)))
        ;

        $child->addChild('users', array(
            'route' => 'sylius_backend_user_index',
            'labelAttributes' => array('icon' => 'icon-group'),
        ))->setLabel($this->translate(sprintf('sylius.backend.menu.%s.users', $section)));
    }

    /**
     * Add configuration menu.
     *
     * @param ItemInterface $menu
     * @param array         $childOptions
     */
    protected function addConfigurationMenu(ItemInterface $menu, array $childOptions, $section)
    {
        $child = $menu
            ->addChild('configuration', $childOptions)
            ->setLabel($this->translate(sprintf('sylius.backend.menu.%s.configuration', $section)))
        ;

        /*$child->addChild('general_settings', array(
            'route' => 'sylius_backend_general_settings',
            'labelAttributes' => array('icon' => 'icon-cog'),
        ))->setLabel($this->translate(sprintf('sylius.backend.menu.%s.general_settings', $section)));

        $child->addChild('payment_methods', array(
            'route' => 'sylius_backend_payment_method_index',
            'labelAttributes' => array('icon' => 'icon-credit-card'),
        ))->setLabel($this->translate(sprintf('sylius.backend.menu.%s.payment_methods', $section)));

        $child->addChild('taxation_settings', array(
            'route' => 'sylius_backend_taxation_settings',
            'labelAttributes' => array('icon' => 'icon-cog'),
        ))->setLabel($this->translate(sprintf('sylius.backend.menu.%s.taxation_settings', $section)));

        $child->addChild('tax_categories', array(
            'route' => 'sylius_backend_tax_category_index',
            'labelAttributes' => array('icon' => 'icon-folder-close-alt'),
        ))->setLabel($this->translate(sprintf('sylius.backend.menu.%s.tax_categories', $section)));

        $child->addChild('tax_rates', array(
            'route' => 'sylius_backend_tax_rate_index',
            'labelAttributes' => array('icon' => 'icon-adjust'),
        ))->setLabel($this->translate(sprintf('sylius.backend.menu.%s.tax_rates', $section)));

        $child->addChild('shipping_categories', array(
            'route' => 'sylius_backend_shipping_category_index',
            'labelAttributes' => array('icon' => 'icon-folder-close-alt'),
        ))->setLabel($this->translate(sprintf('sylius.backend.menu.%s.shipping_categories', $section)));

        $child->addChild('shipping_methods', array(
            'route' => 'sylius_backend_shipping_method_index',
            'labelAttributes' => array('icon' => 'icon-truck'),
        ))->setLabel($this->translate(sprintf('sylius.backend.menu.%s.shipping_methods', $section)));
*/
        $child->addChild('shipments', array(
            'route' => 'sylius_backend_shipment_index',
            'labelAttributes' => array('icon' => 'icon-plane'),
        ))->setLabel($this->translate(sprintf('sylius.backend.menu.%s.shipments', $section)));

        /*$child->addChild('countries', array(
            'route' => 'sylius_backend_country_index',
            'labelAttributes' => array('icon' => 'icon-flag'),
        ))->setLabel($this->translate(sprintf('sylius.backend.menu.%s.countries', $section)));

        $child->addChild('zones', array(
            'route' => 'sylius_backend_zone_index',
            'labelAttributes' => array('icon' => 'icon-globe'),
        ))->setLabel($this->translate(sprintf('sylius.backend.menu.%s.zones', $section)));*/
    }
    
    /**
     * Add account menu.
     *
     * @param ItemInterface $menu
     * @param array         $childOptions
     */
    protected function addAccountMenu(ItemInterface $menu, array $childOptions, $section)
    {
    	$child = $menu
    		->addChild('account', $childOptions)
    		->setLabel($this->translate(sprintf('sylius.backend.menu.%s.account', $section)))
    	;
    
    	$user = $this->securityContext->getToken()->getUser();
    	
    	$child->addChild('edit', array(
    			'route' => 'sylius_backend_user_update',
    			'routeParameters' => array('id' => $user->getId()),
    			'labelAttributes' => array('icon' => 'icon-pencil'),
    	))->setLabel($this->translate(sprintf('sylius.backend.menu.%s.account_edit', $section)));
    
    	$child->addChild('view_site', array(
    			'route' => 'pigalle_homepage',
    			'linkAttributes' => array('target' => '_blank'),
    			'labelAttributes' => array('icon' => 'icon-eye-open', 'with_divider' => true),
    	))->setLabel($this->translate(sprintf('sylius.backend.menu.%s.view_site', $section)));
    
    	$child->addChild('signout', array(
    			'route' => 'fos_user_security_logout',
    			'labelAttributes' => array('icon' => 'icon-off'),
    	))->setLabel($this->translate(sprintf('sylius.backend.menu.%s.signout', $section)));
    }
    
}
