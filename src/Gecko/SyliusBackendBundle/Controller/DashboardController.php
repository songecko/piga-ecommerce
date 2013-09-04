<?php

namespace Gecko\SyliusBackendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use DateTime;

/**
 * Backend dashboard controller.
 *
 */
class DashboardController extends Controller
{
    /**
     * Backend dashboard display action.
     *
     * @return Response
     */
    public function mainAction()
    {
        $orderRepository = $this->get('sylius.repository.order');
        $months = array("enero", "febrero", "marzo", "abril", "mayo", "junio", "julio", "agosto", "septiempre", "diciembre");

        return $this->render('SyliusBackendBundle:Dashboard:main.html.twig', array(
            'revenue' => $orderRepository->revenueBetweenDates(new DateTime('1 week ago'), new DateTime()),
            'ordersCount' => $orderRepository->countBetweenDates(new DateTime('1 week ago'), new DateTime()),
            'orders' => $orderRepository->findBy(array(), array('createdAt' => 'desc'), 5),
            'users' => $this->get('sylius.repository.user')->findBy(array(), array('id' => 'desc'), 5),
            'charts' => array(
                'chart_order_total' => array(
                    'label' => "Ingresos",
                    'type' => 'Line',
                    'data' => array(
                        'labels' => $months,
                        'datasets' => array(
                            array(
                                'fillColor' => "rgba(151,187,205,0.5)",
                                'strokeColor' => "rgba(151,187,205,1)",
                                'data' => $orderRepository->getTotalStatistics()
                            )
                        )
                    )
                ),
                'chart_order_count' => array(
                    'label' => "Cantidad de pedidos",
                    'type' => 'Line',
                    'data' => array(
                        'labels' => $months,
                        'datasets' => array(
                            array(
                                'fillColor' => "rgba(151,187,205,0.5)",
                                'strokeColor' => "rgba(151,187,205,1)",
                                'data' => $orderRepository->getCountStatistics()
                            )
                        )
                    )
                )
            )
        ));
    }
}
