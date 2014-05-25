<?php

namespace Xmontero\Emperors\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class BoardEditorController extends Controller
{
	public function showEditorAction( Request $request )
	{
		$boardConverter = $this->get( 'emperors.client.board.converter' );
		$boardManager = $this->get( 'emperors.board.manager' );
		$pieceManager = $this->get( 'emperors.piece.manager' );
		
		$scope = array();
		
		$board = $boardManager->loadBoardFromTemplate( 'uukhumaki' );
		
		$chestA = $pieceManager->createNewPieceFromScratch( 'chest' );
		$chestA->close();
		
		$board->getTile( 4, 4 )->attachVisiblePiece( $chestA );
		
		$clientBoard = $boardConverter->convert( $board );
		$scope[ 'clientBoard' ] = $clientBoard;
		$scope[ 'modelBoard' ] = $board;
		
		return $this->render( 'XmonteroEmperorsAdminBundle:Pages/BoardEditor:editor.html.twig', $scope );
	}
}
