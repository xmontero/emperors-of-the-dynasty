<?php

namespace Xmontero\Emperors\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class UsersController extends Controller
{
	public function mainAction( Request $request )
	{
		$userManager = $this->get( 'fos_user.user_manager' );
		$users = $userManager->findUsers();
		$uiManager = $this->get( 'emperors.layout.uimanager' );
		
		$table = $uiManager->createTable();
		
		$header = $table->addHeader();
		
		$header->addCell( 'Id' );
		$header->addCell( 'Username' );
		//$header->addCell( 'CreationDate' );
		$header->addCell( 'Facebook' );
		$header->addCell( 'Name' );
		//$header->addCell( 'Email' );
		$header->addCell( 'Last login' );
		//$header->addCell( 'Total games' );
		//$header->addCell( 'Active games' );
		//$header->addCell( 'Dynasty' );
		$header->addCell( 'Actions' );
		
		foreach( $users as $key => $user )
		{
			$username = $user->getUsername();
			$impersonateUrl = $this->getImpersonateUrl( $username );
			
			if( $isFacebook = $user->isFacebook() )
			{
				$facebookUrl = $user->getFacebookUrl();
			}
			
			$row = $table->addDataRow();
			
			$row->addCell( $user->getId() );
			$row->addCell( $user->getUsername() );
			
			if( $isFacebook )
			{
				$row->addCell( 'View' )->setNewWindowLink( $facebookUrl );
			}
			else
			{
				$row->addCell( 'N/A' );
			}
			
			$row->addCell( $user->getRealCompleteName() );
			$row->addCell( $user->getLastLogin()->format( 'd-M H:i T' ) );
			$row->addCell( 'Impersonate' )->setSameWindowLink( $impersonateUrl );
		}
		
		$scope = array();
		$scope[ 'usersTable' ] = $table;
		
		return $this->render( 'XmonteroEmperorsAdminBundle:Pages:users.html.twig', $scope );
	}
	
	private function getImpersonateUrl( $username )
	{
		$router = $this->get('router');
		$url = $router->generate( 'XmonteroEmperorsClientBundle_root', array( '_switch_user' => $username ) );
		
		return $url;
	}
}
