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
		
		// Chests
		
		$chestClosed = $pieceManager->createNewPieceFromScratch( 'chest' );
		$chestClosed->setId( 1 );
		$chestClosed->close();
		
		$chestOpen = $pieceManager->createNewPieceFromScratch( 'chest' );
		$chestOpen->setId( 2 );
		$chestOpen->open();
		
		$chestHidden = $pieceManager->createNewPieceFromScratch( 'chest' );
		$chestHidden->setId( 3 );
		$chestHidden->close();
		
		$board->getTile( 4, 7 )->attachVisiblePiece( $chestClosed );
		$board->getTile( 5, 7 )->attachVisiblePiece( $chestOpen );
		$board->getTile( 6, 7 )->attachHiddenPiece( $chestHidden );
		
		// Show
		
		$clientBoard = $boardConverter->convert( $board );
		$scope[ 'clientBoard' ] = $clientBoard;
		$scope[ 'modelBoard' ] = $board;
		
		return $this->render( 'XmonteroEmperorsAdminBundle:Pages/BoardEditor:editor.html.twig', $scope );
	}
}
