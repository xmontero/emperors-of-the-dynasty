<?php

namespace Xmontero\Emperors\ClientBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use JMS\SecurityExtraBundle\Annotation\Secure; // Acces control in comments
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
	public function rootAction( Request $request )
	{
		$scope = array();
		$scope[ 'name' ] = $this->get( 'security.context' )->getToken();
		
		return $this->render( 'XmonteroEmperorsClientBundle:Pages:2014-04apr-home.html.twig', $scope );
		//return $this->render( 'XmonteroEmperorsClientBundle:Pages:home.html.twig', array( 'name' => $this->get( 'security.context' )->getToken() ) );
	}
	
	public function dirtyAction( Request $request )
	{
		$scope = array();
		$scope[ 'name' ] = $this->get( 'security.context' )->getToken();
		
		$message = \Swift_Message::newInstance()
			->setSubject('Emperors are sending mails now!!!')
			->setFrom('emperorsofthedynasty@gmail.com')
			->setTo('xmontero@dsitelecom.com')
			->setBody( $this->renderView( 'XmonteroEmperorsClientBundle:Pages:2014-04apr-dirty.html.twig', $scope ) );
		
		$this->get('mailer')->send($message);
		
		return $this->render( 'XmonteroEmperorsClientBundle:Pages:2014-04apr-dirty.html.twig', $scope );
	}
	
	public function playersListAction( Request $request )
	{
		$connection = $this->getDoctrine()->getManager()->getConnection();
		
		$statement = $connection->prepare( 'SELECT * FROM emperorsFirstGame ORDER BY id' );
		$statement->execute();
		$emperors = $statement->fetchAll();
		
		$statement = $connection->prepare( 'SELECT * FROM pawnsFirstGame ORDER BY id' );
		$statement->execute();
		$pawns = $statement->fetchAll();
		
		$scope = array();
		$scope[ 'name' ] = $this->get( 'security.context' )->getToken();
		$scope[ 'emperors' ] = $emperors;
		$scope[ 'pawns' ] = $pawns;
		
		return $this->render( 'XmonteroEmperorsClientBundle:Pages/FirstGame:players.html.twig', $scope );
	}
	
	public function dumpPageAction( Request $request, $template )
	{
		$scope = array();
		$scope[ 'name' ] = $this->get( 'security.context' )->getToken();
		
		return $this->render( $template, $scope );
	}
	
}
