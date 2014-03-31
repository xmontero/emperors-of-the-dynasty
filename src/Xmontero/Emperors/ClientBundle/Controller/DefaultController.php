<?php

namespace Xmontero\Emperors\ClientBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use JMS\SecurityExtraBundle\Annotation\Secure; // Acces control in comments
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
	public function rootAction( Request $request )
	{
		return $this->render( 'XmonteroEmperorsClientBundle:Pages:home.html.twig', array( 'name' => $this->get( 'security.context' )->getToken() ) );
	}
}
