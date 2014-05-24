<?php

namespace Xmontero\Emperors\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class BoardEditorController extends Controller
{
	public function showEditorAction( Request $request )
	{
		$boardManager = $this->get( 'emperors.board.manager' );
		
		$scope = array();
		
		$board = $boardManager->loadBoardFromTemplate( 'uukhumaki' );
		
		$scope[ 'board' ] = $board;
		
		return $this->render( 'XmonteroEmperorsAdminBundle:Pages/BoardEditor:editor.html.twig', $scope );
	}
}
