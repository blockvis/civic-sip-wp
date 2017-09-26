<?php

/**
 *
 * @link       https://blockvis.com
 * @since      1.0.0
 *
 * @package    Civic_Sip
 * @subpackage Civic_Sip/includes
 */

use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Parser;
use Lcobucci\JWT\Signer\Ecdsa\Sha256;
use Lcobucci\JWT\Signer\Key;
use Lcobucci\JWT\Token;
use Mdanter\Ecc\EccFactory;
use Mdanter\Ecc\Serializer\PrivateKey\DerPrivateKeySerializer;
use Mdanter\Ecc\Serializer\PrivateKey\PemPrivateKeySerializer;
use Mdanter\Ecc\Serializer\PublicKey\DerPublicKeySerializer;
use Mdanter\Ecc\Serializer\PublicKey\PemPublicKeySerializer;
use Ramsey\Uuid\Uuid;

/**
 * Decode JWT and log in user
 *
 * @package    Civic_Sip
 * @subpackage Civic_Sip/includes
 * @author     Blockvis <blockvis@blockvis.com>
 */
class Civic_Sip_Client {

	const SIP_PUB_HEX = '049a45998638cfb3c4b211d72030d9ae8329a242db63bfb0076a54e7647370a8ac5708b57af6065805d5a6be72332620932dbb35e8d318fce18e7c980a0eb26aa1';

	/**
	 * @var array
	 */
	private $config;

	public function __construct($settings) {
		$this->config = $settings;
	}

	public function exchangeToken($jwt) {
		$body = json_encode(array('authToken' => $jwt));
		$response = wp_remote_post('https://api.civic.com/sip' . '/prod/' . 'scopeRequest/authCode', array(
			'body' => $body,
			'headers' => array(
				'Authorization' => $this->makeAuthorizationHeader( 'scopeRequest/authCode', 'POST', $body),
				'Accept' => 'application/json',
				'Content-Type' => 'application/json',
				'Content-Length' => strlen($body),
			)
		));

		$payload = json_decode( wp_remote_retrieve_body( $response));

		$token = (new Parser())->parse((string) $payload->data);
		$this->verify($token);

		$userData = $token->getClaim( 'data');
		if ($payload->encrypted) {
			$userData = $this->decrypt($userData);
		}

		return json_decode($userData, true);
	}

	/**
	 * @param string $encrypted
	 * @return string
	 */
	private function decrypt($encrypted)
	{
		$iv = substr($encrypted,0, 32);
		$encodedData = substr($encrypted, 32);

		return openssl_decrypt(
			base64_decode($encodedData),
			'AES-128-CBC',
			hex2bin($this->config['secret']),
			OPENSSL_RAW_DATA,
			hex2bin($iv)
		);
	}

	/**
	 * @param Token $token
	 *
	 * @throws Exception
	 */
	private function verify(Token $token)
	{
		if (!$token->verify( new Sha256(), $this->getTokenVerificationKey()))  {
			throw new Exception();
		}
	}

	/**
	 * @return Key
	 */
	private function getTokenVerificationKey()
	{
		$publicKeySerializer = new PemPublicKeySerializer(new DerPublicKeySerializer(EccFactory::getAdapter()));
		$publicKey = EccFactory::getSecgCurves()->generator256r1()->getPublicKeyFrom(
			gmp_init(substr(self::SIP_PUB_HEX, 2, 64), 16),
			gmp_init(substr(self::SIP_PUB_HEX, 66,64), 16)
		);

		return new Key($publicKeySerializer->serialize($publicKey));
	}

	/**
	 * @param string $targetPath
	 * @param string $requestMethod
	 * @param $requestBody
	 * @return string
	 */
	private function makeAuthorizationHeader( $targetPath, $requestMethod, $requestBody)
	{
		$builder = new Builder();
		$builder = $builder
			->setIssuer( $this->config['app_id'] )
			->setAudience( 'https://api.civic.com/sip' )
			->setSubject( $this->config['app_id'] )
			->setId( Uuid::uuid4() )
			->setIssuedAt( time() )
			->setNotBefore( time() + 60 )
			->setExpiration( time() + 3*60 )
			->set( 'data', array(
				'method' => $requestMethod,
				'path' => $targetPath,
			))
			->sign( new \Lcobucci\JWT\Signer\Ecdsa\Sha256(), $this->getTokenSingingKey());
		$token = $builder->getToken();
//
//			->permittedFor($this->baseUri)
//			->relatedTo($this->config['app_id'])
//			->identifiedBy(Uuid::uuid4())
//			->issuedAt(new DateTimeImmutable())
//			->canOnlyBeUsedAfter((new DateTimeImmutable())->modify('+1 minute'))
//			->expiresAt((new DateTimeImmutable())->modify('+3 minute'))
//			->withClaim('data', [
//				'method' => $requestMethod,
//				'path' => $targetPath
//			]);
		// Generate signed token.
		// $token = $tokenBuilder->getToken(\Lcobucci\JWT\Signer\Ecdsa\Sha256::create(), $this->getTokenSingingKey());
		$extension = base64_encode(
			(new \Lcobucci\JWT\Signer\Hmac\Sha256())->sign($requestBody, new Key($this->config['secret']))
		);
		return sprintf('Civic %s.%s', $token, $extension);
	}

	/**
	 * @return Key
	 */
	private function getTokenSingingKey()
	{
		$privateKeySerializer = new PemPrivateKeySerializer(new DerPrivateKeySerializer(EccFactory::getAdapter()));
		$privateKey = EccFactory::getSecgCurves()->generator256r1()->getPrivateKeyFrom(gmp_init($this->config['privkey'], 16));
		return new Key($privateKeySerializer->serialize($privateKey));
	}

}
