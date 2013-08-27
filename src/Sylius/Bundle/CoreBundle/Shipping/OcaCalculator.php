<?php

namespace Sylius\Bundle\CoreBundle\Shipping;

use Sylius\Bundle\ShippingBundle\Calculator\Calculator;
use Sylius\Bundle\ShippingBundle\Model\ShipmentInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class OcaCalculator extends Calculator
{
	const OCA     = 'oca';
	
	public function calculate(ShipmentInterface $shipment)
    {
        $configuration = $shipment->getMethod()->getConfiguration();
        $order = $shipment->getOrder();
        
        $postCode = $order->getShippingAddress()->getPostCode();
		$itemsTotal = count($order->getInventoryUnits());
        
        $url = "http://webservice.oca.com.ar/oep_tracking/Oep_Track.asmx/Tarifar_Envio_Corporativo"
        		."?PesoTotal=".(2*$itemsTotal)
        		."&VolumenTotal=".(0.000012*$order->getItemsTotal())
        		."&CodigoPostalOrigen=1704"
        		."&CodigoPostalDestino=".$postCode
        		."&CantidadPaquetes=".$itemsTotal
        		."&Cuit=30-68338893-5"
        		."&Operativa=".$configuration['Operativa'];
        
        $xmlString = file_get_contents($url);;
        preg_match('/<Total(.*)?>(.*)?<\/Total>/', $xmlString, $total);
        
        return $total[2]*100;
    }
    
    /**
     * {@inheritdoc}
     */
    public function isConfigurable()
    {
    	return true;
    }
    
    public function setConfiguration(OptionsResolverInterface $resolver)
    {
    	$resolver
    	->setDefaults(array(
    		'CodigoPostalOrigen' => 1704,
    		'Cuit' => '30-68338893-5'
    	))
    	->setAllowedTypes(array(
    		'CodigoPostalOrigen' => array('integer'),
    		'Cuit' => array('string'),
    		'Operativa' => array('integer'),
    	))
    	;
    }
}