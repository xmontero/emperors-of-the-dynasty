<?php

namespace Xmontero\Emperors\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
	public function dashboardAction( Request $request )
	{
		$scope = array();
		
		$scope[ 'games' ] = $this->get( 'emperors.game.manager' )->getOpenGames( 10 );
		
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
	
	public function getGameAction( $gameId, $turn )
	{
		$boardConverter = $this->get( 'emperors.client.board.converter' );
		$gameManager = $this->get( 'emperors.game.manager' );
		
		$scope = array();
		
		$game = $gameManager->getGameById( $gameId );
		$game->setTurn( $turn );
		
		$board = $game->getBoard();
		$clientBoard = $boardConverter->convert( $board );
		$scope[ 'game' ] = $game;
		$scope[ 'clientBoard' ] = $clientBoard;
		$scope[ 'modelBoard' ] = $board;
		$scope[ 'lastOpenTurn' ] = $game->getLastOpenTurn();
		
		return $this->render( 'XmonteroEmperorsAdminBundle:Pages/GameEditor:game.html.twig', $scope );
	}
}
