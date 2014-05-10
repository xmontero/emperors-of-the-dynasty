<?php

namespace Xmontero\Emperors\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
	public function rootAction( Request $request )
	{
		$scope = array();
		$scope[ 'name' ] = $this->get( 'security.context' )->getToken();
		
		$message = \Swift_Message::newInstance()
			->setSubject('Emperors are sending mails now!!!')
			->setFrom('emperorsofthedynasty@gmail.com')
			->setTo('xmontero@dsitelecom.com')
			->setBody( $this->renderView( 'XmonteroEmperorsAdminBundle:Pages:dirty.html.twig', $scope ) );
		
		$this->get('mailer')->send($message);
		
		return $this->render( 'XmonteroEmperorsAdminBundle:Pages:dirty.html.twig', $scope );
	}
	
	public function dirtyAction( Request $request )
	{
		$scope = array();
		$scope[ 'name' ] = $this->get( 'security.context' )->getToken();
		
		$message = \Swift_Message::newInstance()
			->setSubject('Emperors are sending mails now!!!')
			->setFrom('emperorsofthedynasty@gmail.com')
			->setTo('xmontero@dsitelecom.com')
			->setBody( $this->renderView( 'XmonteroEmperorsAdminBundle:Pages:dirty.html.twig', $scope ) );
		
		$this->get('mailer')->send($message);
		
		return $this->render( 'XmonteroEmperorsAdminBundle:Pages:dirty.html.twig', $scope );
	}
}
