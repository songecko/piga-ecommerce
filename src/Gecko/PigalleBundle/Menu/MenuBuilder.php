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
     * Creates user menu
     *
     * @param Request $request
     *
     * @return ItemInterface
     */
    public function createUserMenu(Request $request)
    {
    	if($this->securityContext->isGranted('ROLE_USER'))
    	{
	    	$menu = $this->factory->createItem('root', array(
	    		'childrenAttributes' => array('class' => 'granted')
	    	));
	    	$this->addGrantedUserMenu($menu);
    	}else 
    	{
    		$menu = $this->factory->createItem('root');
    		$this->addNotGrantedUserMenu($menu);
    	}
    	 
    	return $menu;
    }
    
    /**
     * Creates user account menu
     *
     * @param Request $request
     *
     * @return ItemInterface
     */
    public function createAccountMenu(Request $request)
    {
    	$menu = $this->factory->createItem('root', array(
    			'childrenAttributes' => array('class' => 'accountMenu')    		
    	));
        	
    	$menu->addChild('profile', array(
    			'route' => 'sylius_account_profile',
    	))->setLabel("Perfil");
    	
    	$menu->addChild('password', array(
    			'route' => 'sylius_account_change_password',
    	))->setLabel("Contraseña");
    	
    	$menu->setCurrent($request->getRequestUri());
    	
    	return $menu;
    }
    
    /**
     * Create footer menu.
     *
     * @param Request $request
     * @return ItemInterface
     */
    public function createFooterMenu(Request $request)
    {
    	$menu = $this->factory->createItem('root');
    	 
    	$menu->addChild('la_marca', array(
    			//'route' => 'sylius_backend_taxonomy_index',
    	))->setLabel('Marca');
    
    	$menu->addChild('colecciones', array(
    			//'route' => 'sylius_backend_taxonomy_index',
    	))->setLabel('Colecciones');
    
    	$menu->addChild('online_store', array(
    			'route' => 'pigalle_product_index',
    	))->setLabel('Online store');
    	 
    	$menu->addChild('locales', array(
    			//'route' => 'pigalle_product_index',
    	))->setLabel('Locales');
    	 
    	$menu->addChild('cart', array(
    			'route' => 'sylius_cart_summary',
    	))->setLabel('Bolsa de compras');
    	 
    	if($this->securityContext->isGranted('ROLE_USER'))
    	{
    		$menu->addChild('account', array(
    				'route' => 'sylius_account_profile',
    		))->setLabel('Mi cuenta');
    	}else
    	{
    		$menu->addChild('login', array(
    				'route' => 'fos_user_security_login',
    		))->setLabel('Login');
    	}
    
    	$menu->addChild('contacto', array(
    			//'route' => 'sylius_backend_taxonomy_index',
    	))->setLabel('Contacto');
    	 
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
    
    	$menu->addChild('mayorista', array(
    		//'route' => 'pigalle_product_index',
    	))->setLabel('Mayorista');
    }
   
    /**
     * Add granted user menu
     *
     * @param ItemInterface $menu
     */
    protected function addGrantedUserMenu(ItemInterface $menu)
    {
    	$user = $this->securityContext->getToken()->getUser();
    	
    	$menu->addChild('account', array(
    		'route' => 'sylius_account_profile',
    		'attributes' => array(
    			'class' => 'account'
    		)
    	))->setLabel("mi cuenta");
    	
    	if($this->securityContext->isGranted('ROLE_SYLIUS_ADMIN'))
    	{
	    	$menu->addChild('admin', array(
	    			'route' => 'sylius_backend_index',
	    			'attributes' => array(
	    					'class' => 'admin'
	    			),
	    			'linkAttributes' => array(
	    				'target' => '_blank'
	    			)
	    	))->setLabel("administrador");
    	}
    	
    	$menu->addChild('logout', array(
    			'route' => 'fos_user_security_logout',
    			'attributes' => array(
    					'class' => 'logout'
    			)
    	))->setLabel("salir");
    	
    	$menu->addChild('user', array(
    			'route' => 'sylius_account_profile',
    			'attributes' => array(
    					'class' => 'user'
    			)
    	))->setLabel("Hola ".$user->getShortedName()."!");
    }
    
    /**
     * Add not granted user menu
     *
     * @param ItemInterface $menu
     */
    protected function addNotGrantedUserMenu(ItemInterface $menu)
    {
    	$menu->addChild('login', array(
    			'route' => 'fos_user_security_login'
    	))->setLabel("LOGIN");
    	 
    	$menu->addChild('register', array(
    			'route' => 'fos_user_registration_register'
    	))->setLabel("REGISTRARSE");
    }
}
