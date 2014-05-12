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
		
		$scope[ 'games' ] = $this->get( 'emperors.game.manager' )->getOpenGames( 10 );
		$scope[ 'name' ] = $this->get( 'security.context' )->getToken();
		
		return $this->render( 'XmonteroEmperorsClientBundle:Pages:games.html.twig', $scope );
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
		
		$query = 'select pfg.id, pfg.emperor, pfg.gender, pfg.dynasty, u.signupDate, u.playerName, u.facebookPage from emperorsFirstGame pfg LEFT JOIN players u ON pfg.playerId = u.id ORDER BY pfg.id';
		$statement = $connection->prepare( $query );
		$statement->execute();
		$emperors = $statement->fetchAll();
		
		$query = 'select pfg.id, pfg.emperor, pfg.gender, pfg.dynasty, u.signupDate, u.playerName, u.facebookPage from pawnsFirstGame pfg LEFT JOIN players u ON pfg.playerId = u.id ORDER BY pfg.id';
		$statement = $connection->prepare( $query );
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
	
	public function boardAction( Request $request, $turn )
	{
		$dev = in_array( $this->get( 'kernel' )->getEnvironment(), array( 'test', 'dev' ) );
		
		if( ( $turn != 1 ) && ( ! $dev ) )
		{
			throw new \DomainException( 'Invalid turn in non-dev environment.' );
		}
		
		$manager = $this->get( 'emperors.game.manager' );
		$game = $manager->getGameById( 1 );
		
		$scope = array();
		$scope[ 'name' ] = $this->get( 'security.context' )->getToken();
		$scope[ 'game' ] = $game;
		$scope[ 'turn' ] = $turn;
		
		return $this->render( 'XmonteroEmperorsClientBundle:Pages/FirstGame:board.html.twig', $scope );
	}
}
