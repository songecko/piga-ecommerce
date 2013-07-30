<?php

namespace Gecko\PigalleBundle\Menu;

use Knp\Menu\ItemInterface;
use Symfony\Component\HttpFoundation\Request;
use Knp\Menu\FactoryInterface;
use Symfony\Component\Translation\TranslatorInterface;
use Symfony\Component\Security\Core\SecurityContextInterface;
use JMS\TranslationBundle\Annotation\Ignore;
use Symfony\Component\DependencyInjection\ContainerAware;

/**
 * Main menu builder.
 *
 */
class MenuBuilder extends ContainerAware
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
     * Builds main menu left
     *
     * @param Request $request
     *
     * @return ItemInterface
     */
    public function createMainMenuLeft(Request $request)
    {
        $menu = $this->factory->createItem('root', array(
        	'childrenAttributes' => array('class' => 'pull-left')
        ));
        
        $this->addMainMenuLeft($menu);
                
        $menu->setCurrent($request->getRequestUri());

        return $menu;
    }
    
    /**
     * Builds main menu right
     *
     * @param Request $request
     *
     * @return ItemInterface
     */
    public function createMainMenuRight(Request $request)
    {
    	$menu = $this->factory->createItem('root', array(
    			'childrenAttributes' => array('class' => 'pull-right')
    	));
    
    	$this->addMainMenuRight($menu);
    
    	$menu->setCurrent($request->getRequestUri());
    
    	return $menu;
    }
    
    /**
     * Add main menu left.
     *
     * @param ItemInterface $menu
     */
    protected function addMainMenuLeft(ItemInterface $menu)
    {
        $menu->addChild('la_marca', array(
            //'route' => 'sylius_backend_taxonomy_index',
        ))->setLabel('LA MARCA');
        
        $menu->addChild('colecciones', array(
        	//'route' => 'sylius_backend_taxonomy_index',
        ))->setLabel('COLECCIONES');
        
        $menu->addChild('online_store', array(
        	'route' => 'pigalle_product_index',
        ))->setLabel('ONLINE STORE');
    }
    
    /**
     * Add main menu right.
     *
     * @param ItemInterface $menu
     */
    protected function addMainMenuRight(ItemInterface $menu)
    {
    	$menu->addChild('facebook', array(
    		'uri' => '#'
    	))
    	->setExtra('safe_label', true)
    	->setLabel('<img src="'.$this->container->get('templating.helper.assets')->getUrl('bundles/pigalle/images/ico-facebook.png').'" alt="Facebook" />');
    	
    	$menu->addChild('contacto', array(
    		//'route' => 'sylius_backend_taxonomy_index',
    	))->setLabel('CONTACTO');
    
    	$menu->addChild('locales', array(
    		//'route' => 'sylius_backend_taxonomy_index',
    	))->setLabel('LOCALES');
    
    	$menu->addChild('preventa', array(
    		//'route' => 'pigalle_product_index',
    	))->setLabel('PREVENTA');
    }
}
