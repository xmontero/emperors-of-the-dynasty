<?php
namespace Xmontero\Emperors\UserBundle\Entity;

use FOS\UserBundle\Entity\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * ORM\Entity(repositoryClass="Xmontero\Emperors\UserBundle\Repository\UserRepository")
 * @ORM\Entity
 * @ORM\Table(name="users")
 */
class User extends BaseUser
{
	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	protected $id;
	
	/** @ORM\Column(name="facebook_id", type="string", length=255, nullable=true) */
	protected $facebook_id;
	
	/** @ORM\Column(name="facebook_access_token", type="string", length=255, nullable=true) */
	protected $facebook_access_token;
	
	/** @ORM\Column(name="google_id", type="string", length=255, nullable=true) */
	protected $google_id;
	
	/** @ORM\Column(name="google_access_token", type="string", length=255, nullable=true) */
	protected $google_access_token;
	
	/** @ORM\Column(name="oauth_response", type="text", nullable=true) */
	protected $oauth_response;
	
	public function __construct()
	{
		parent::__construct();
		// your own logic
	}
	
	public function setFacebookId( $id )
	{
		$this->facebook_id = $id;
	}
	
	public function getFacebookId()
	{
		return $this->facebook_id;
	}
	
	public function setFacebookAccessToken( $token )
	{
		$this->facebook_access_token = $token;
	}
	
	public function getFacebookAccessToken()
	{
		return $this->facebook_access_token;
	}
	
	public function setGoogleId( $id )
	{
		$this->google_id = $id;
	}
	
	public function getGoogleId()
	{
		return $this->google_id;
	}
	public function setGoogleAccessToken( $token )
	{
		$this->google_access_token = $token;
	}
	
	public function getGoogleAccessToken()
	{
		return $this->google_access_token;
	}
	
	public function setOAuthResponse( $response )
	{
		$this->oauth_response = $response;
	}
	
	public function getOAuthResponse()
	{
		return $this->oauth_response;
	}
	
	public function getOAuthName()
	{
		$response = json_decode( $this->oauth_response );
		return $response->name;
	}
}
