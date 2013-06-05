<?php

use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;

class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = array(
        	// symfony-standard bundles
        	new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
        	new Symfony\Bundle\SecurityBundle\SecurityBundle(),
        	new Symfony\Bundle\TwigBundle\TwigBundle(),
        	new Symfony\Bundle\MonologBundle\MonologBundle(),
        	new Symfony\Bundle\SwiftmailerBundle\SwiftmailerBundle(),
        	new Symfony\Bundle\AsseticBundle\AsseticBundle(),
        	//new Doctrine\Bundle\DoctrineBundle\DoctrineBundle(), Added at the end by SyliusProductBundle requeriments
        	new Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle(),       	
        		
        	//Sylius dependencies bundles
        	new FOS\RestBundle\FOSRestBundle(),
        	new JMS\SerializerBundle\JMSSerializerBundle($this),
        	new Stof\DoctrineExtensionsBundle\StofDoctrineExtensionsBundle(),
        	new WhiteOctober\PagerfantaBundle\WhiteOctoberPagerfantaBundle(),
        		
        	//Sylius bundles
        	new Sylius\Bundle\ResourceBundle\SyliusResourceBundle(),
        	new Sylius\Bundle\ProductBundle\SyliusProductBundle(),
        		
        	//Doctrine bundle
        	new Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),

        	//Pigalle bundles
            new Gecko\PigalleBundle\PigalleBundle(),        	
        );

        if (in_array($this->getEnvironment(), array('dev', 'test'))) {
            $bundles[] = new Symfony\Bundle\WebProfilerBundle\WebProfilerBundle();
            $bundles[] = new Sensio\Bundle\DistributionBundle\SensioDistributionBundle();
            $bundles[] = new Sensio\Bundle\GeneratorBundle\SensioGeneratorBundle();
            
            //3rd party bundles
            $bundles[] = new RaulFraile\Bundle\LadybugBundle\RaulFraileLadybugBundle();
        }
        
        return $bundles;
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load(__DIR__.'/config/config_'.$this->getEnvironment().'.yml');
    }
}
