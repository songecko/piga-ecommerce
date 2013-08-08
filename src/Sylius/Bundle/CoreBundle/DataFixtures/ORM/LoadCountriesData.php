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
use Sylius\Bundle\AddressingBundle\Model\CountryInterface;
use Symfony\Component\Locale\Locale;

/**
 * Default country fixtures.
 *
 * @author Paweł Jędrzejewski <pjedrzejewski@diweb.pl>
 */
class LoadCountriesData extends DataFixture
{
    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager)
    {
        $locale = $this->container->getParameter('sylius.locale');
        $countryRepository = $this->getCountryRepository();
        $countries = Locale::getDisplayCountries($locale);

        foreach ($countries as $isoName => $name) {
            $country = $countryRepository->createNew();

            $country->setName($name);
            $country->setIsoName($isoName);

            if ('US' === $isoName) {
                $this->addUsStates($country);
            }elseif ('AR' === $isoName) 
            {
            	$this->addArProvinces($country);
            }

            $manager->persist($country);

            $this->setReference('Sylius.Country.'.$isoName, $country);
        }

        $manager->flush();
    }

    /**
     * Adds all US states as provinces to given country.
     *
     * @param CountryInterface $country
     */
    private function addUsStates(CountryInterface $country)
    {
        $states = array(
            'AL' => 'Alabama',
            'AK' => 'Alaska',
            'AZ' => 'Arizona',
            'AR' => 'Arkansas',
            'CA' => 'California',
            'CO' => 'Colorado',
            'CT' => 'Connecticut',
            'DE' => 'Delaware',
            'DC' => 'District Of Columbia',
            'FL' => 'Florida',
            'GA' => 'Georgia',
            'HI' => 'Hawaii',
            'ID' => 'Idaho',
            'IL' => 'Illinois',
            'IN' => 'Indiana',
            'IA' => 'Iowa',
            'KS' => 'Kansas',
            'KY' => 'Kentucky',
            'LA' => 'Louisiana',
            'ME' => 'Maine',
            'MD' => 'Maryland',
            'MA' => 'Massachusetts',
            'MI' => 'Michigan',
            'MN' => 'Minnesota',
            'MS' => 'Mississippi',
            'MO' => 'Missouri',
            'MT' => 'Montana',
            'NE' => 'Nebraska',
            'NV' => 'Nevada',
            'NH' => 'New Hampshire',
            'NJ' => 'New Jersey',
            'NM' => 'New Mexico',
            'NY' => 'New York',
            'NC' => 'North Carolina',
            'ND' => 'North Dakota',
            'OH' => 'Ohio',
            'OK' => 'Oklahoma',
            'OR' => 'Oregon',
            'PA' => 'Pennsylvania',
            'RI' => 'Rhode Island',
            'SC' => 'South Carolina',
            'SD' => 'South Dakota',
            'TN' => 'Tennessee',
            'TX' => 'Texas',
            'UT' => 'Utah',
            'VT' => 'Vermont',
            'VA' => 'Virginia',
            'WA' => 'Washington',
            'WV' => 'West Virginia',
            'WI' => 'Wisconsin',
            'WY' => 'Wyoming'
        );

        $this->addProvinces($country, $states);        
    }

    /**
     * Adds all Argentina provinces to given country.
     *
     * @param CountryInterface $country
     */
    private function addArProvinces(CountryInterface $country)
    {
    	$provinces = array(
    			'AR-A' => 'Salta',
    			'AR-B' => 'Buenos Aires',
    			'AR-C' => 'Capital Federal',
    			'AR-D' => 'San Luis',
    			'AR-E' => 'Entre Ríos',
    			'AR-F' => 'La Rioja',
    			'AR-G' => 'Santiago del Estero',
    			'AR-H' => 'Chaco',
    			'AR-J' => 'San Juan',
    			'AR-K' => 'Catamarca',
    			'AR-L' => 'La Pampa',
    			'AR-M' => 'Mendoza',
    			'AR-N' => 'Misiones',
    			'AR-P' => 'Formosa',
    			'AR-Q' => 'Neuquén',
    			'AR-R' => 'Río Negro',
    			'AR-S' => 'Santa Fe',
    			'AR-T' => 'Tucumán',
    			'AR-U' => 'Chubut',
    			'AR-V' => 'Tierra del Fuego',
    			'AR-W' => 'Corrientes',
    			'AR-X' => 'Córdoba',
    			'AR-Y' => 'Jujuy',
    			'AR-Z' => 'Santa Cruz'
    	);
    
    	$this->addProvinces($country, $provinces);
    }
    
    public function addProvinces($country, $provinces)
    {
    	$provinceRepository = $this->getProvinceRepository();
    	
    	foreach ($provinces as $isoName => $name) 
    	{
    		$province = $provinceRepository->createNew();
    		$province->setName($name);
    	
    		$country->addProvince($province);
    	
    		$this->setReference('Sylius.Province.'.$isoName, $province);
    	}    	
    }
    
    /**
     * {@inheritdoc}
     */
    public function getOrder()
    {
        return 1;
    }
}
